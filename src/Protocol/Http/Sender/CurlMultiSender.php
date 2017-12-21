<?php

namespace Apple\ApnPush\Protocol\Http\Sender;

use Apple\ApnPush\Protocol\Http\Request;
use Apple\ApnPush\Protocol\Http\Response;
use RuntimeException;

/**
 * Class CurlMultiSender
 *
 * @package Apple\ApnPush\Protocol\Http\Sender
 */
class CurlMultiSender implements HttpSenderInterface
{
    /**
     * The curl version used
     *
     * @var
     */
    private $curlVersion;

    /**
     * Max number of simultaneous connections allowed
     *
     * @var int
     */
    private $maxConcurrentRequests = 10;

    /**
     * Global timeout all requests must be completed by this time
     *
     * @var int
     */
    private $timeout = 5000;

    /**
     * The request queue
     *
     * @var array
     */
    private $requests = [];

    /**
     * CurlMultiSender constructor.
     *
     * @param int $maxRequests
     * @param int $timeout
     */
    public function __construct($maxRequests = 10, $timeout = 5000)
    {
        $this->maxRequests($maxRequests);
        $this->timeout($timeout);

        $this->curlVersion = curl_version()['version'];
    }

    /**
     * Set max concurrent requests.
     *
     * @param int $maxRequests
     *
     * @return CurlMultiSender
     */
    public function maxRequests(int $maxRequests)
    {
        $this->maxConcurrentRequests = $maxRequests;

        return $this;
    }

    /**
     * Set the global requests timeout.
     *
     * @param int $timeout
     *
     * @return CurlMultiSender
     */
    public function timeout(int $timeout)
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * Add a request to the request queue
     *
     * @param Request  $request
     * @param callable $callback
     *
     * @return void
     */
    public function addRequest(Request $request, callable $callback): void
    {
        $inlineHeaders = [];
        $options = [];

        foreach ($request->getHeaders() as $name => $value) {
            $inlineHeaders[] = sprintf('%s: %s', $name, $value);
        }

        if ($request->getCertificate()) {
            $options[CURLOPT_SSLCERT] = $request->getCertificate();
            $options[CURLOPT_SSLCERTPASSWD] = $request->getCertificatePassPhrase();
        }

        $this->requests[] = [
            'url'       => $request->getUrl(),
            'post_data' => $request->getContent(),
            'callback'  => $callback,
            'options'   => $options,
            'headers'   => $inlineHeaders,
        ];
    }

    /**
     * Reset request queue
     *
     * @param $multiCurlHandle
     */
    private function reset($multiCurlHandle)
    {
        $this->requests = [];
        curl_multi_close($multiCurlHandle);
    }

    /**
     * Execute the request queue
     *
     */
    public function send(): void
    {
        $active = null;
        $requestsMap = [];
        $multiHandle = curl_multi_init();
        $requestsRunning = 0;

        $requestsToRun = $this->requestsToRun();
        for ($i = 0; $i < $requestsToRun; $i++) {
            $this->initRequest($i, $multiHandle, $requestsMap);
            $requestsRunning++;
        }

        do {
            do {
                $handlerStatus = curl_multi_exec($multiHandle, $active);
            } while ($handlerStatus === CURLM_CALL_MULTI_PERFORM);

            if ($handlerStatus !== CURLM_OK) {
                break;
            }

            while ($completed = curl_multi_info_read($multiHandle)) {
                $this->processSingleRequest($completed, $multiHandle, $requestsMap);
                $requestsRunning--;

                while ($this->shouldStartNewRequest($requestsRunning, $i)) {
                    $this->initRequest($i, $multiHandle, $requestsMap);
                    $requestsRunning++;
                    $i++;
                }
            }

            $this->saveCycles();
        } while ($active || count($requestsMap));

        $this->reset($multiHandle);
    }

    /**
     * Build individual cURL options for a request
     *
     * @param array $request
     *
     * @return array|mixed
     */
    private function buildOptions(array $request)
    {
        $url = $request['url'];
        $postData = $request['post_data'];

        $options = $request['options'];

        $options[CURLOPT_RETURNTRANSFER] = true;
        $options[CURLOPT_NOSIGNAL] = 1;
        $options[CURLOPT_HTTPHEADER] = $request['headers'];
        $options[CURLOPT_URL] = $url;
        $options[CURLOPT_POST] = 1;
        $options[CURLOPT_POSTFIELDS] = $postData;
        $options[CURLOPT_HTTP_VERSION] = 3;

        if (version_compare($this->curlVersion, '7.16.2') >= 0) {
            $options[CURLOPT_CONNECTTIMEOUT_MS] = $this->timeout;
            $options[CURLOPT_TIMEOUT_MS] = $this->timeout;
        } else {
            $options[CURLOPT_CONNECTTIMEOUT] = round($this->timeout / 1000);
            $options[CURLOPT_TIMEOUT] = round($this->timeout / 1000);
        }

        return $options;
    }


    /**
     * Initialize Curl request
     *
     * @param $requestNumber
     * @param $multiHandler
     * @param $requestsMap
     */
    private function initRequest($requestNumber, $multiHandler, &$requestsMap)
    {
        $curlHandler = curl_init();

        $request =& $this->requests[$requestNumber];

        $options = $this->buildOptions($request);
        $request['options_set'] = $options; //merged options

        if (false === curl_setopt_array($curlHandler, $options)) {
            throw new RuntimeException('Invalid options passed to curl');
        }

        $this->addTimer($request);

        curl_multi_add_handle($multiHandler, $curlHandler);

        //add curl handle of a new request to the request map
        $curlHandleHash = (int)$curlHandler;
        $requestsMap[$curlHandleHash] = $requestNumber;
    }


    /**
     * Process the response from a request.
     *
     * @param       $completed
     * @param       $multiHandle
     * @param array $requestsMap
     *
     * @return void
     */
    private function processSingleRequest($completed, $multiHandle, array &$requestsMap)
    {
        $curlHandle = $completed['handle'];
        $curlHandleHash = (int)$curlHandle;
        $request =& $this->requests[$requestsMap[$curlHandleHash]];

        $requestInfo = curl_getinfo($curlHandle);
        $requestInfo['time'] = $time = $this->stopTimer($request);

        if (true === $this->serverRespondedWithError($curlHandle, $requestInfo)) {
            $response = '';
        } else {
            $response = curl_multi_getcontent($curlHandle);
        }

        // remove completed request and its curl handle
        unset($requestsMap[$curlHandleHash]);
        curl_multi_remove_handle($multiHandle, $curlHandle);

        // get request info
        $callback = $request['callback'];

        $callback(
            new Response(
                (int) $requestInfo['http_code'],
                (string) $response,
                (float) $time
            )
        );

        unset($requestInfo);
        unset($request);
        unset($time);
    }

    /**
     * Add a timer on the request.
     *
     * @param array $request
     */
    private function addTimer(array &$request)
    {
        $request['timer'] = microtime(true); //start time
        $request['time'] = 0; //default if not overridden by time later
    }

    /**
     * Stop request timer.
     *
     * @param array $request
     *
     * @return float
     */
    private function stopTimer(array &$request)
    {
        $elapsed = $request['timer'] - microtime(true);
        $request['time'] = $elapsed;
        unset($request['timer']);

        return $elapsed;
    }

    /**
     * Determine how many requests should run.
     *
     * @return integer
     */
    private function requestsToRun(): int
    {
        return (int) min($this->maxConcurrentRequests, count($this->requests));
    }

    /**
     * Determine if the request failed
     *
     * @param $curlHandle
     * @param $requestInfo
     *
     * @return bool
     */
    private function serverRespondedWithError($curlHandle, array $requestInfo): bool
    {
        return curl_errno($curlHandle) !== 0 || (int) $requestInfo['http_code'] !== 200;
    }

    /**
     * Determine if the running requests is
     * still under the concurrent requests limit.
     *
     * @param $requestsRunning
     *
     * @return bool
     */
    private function isUnderRequestLimit(int $requestsRunning): bool
    {
        return $requestsRunning < $this->maxConcurrentRequests;
    }

    /**
     * Determine if we have any request left to run
     *
     * @param $iterator
     *
     * @return bool
     */
    private function hasRequestsLeft(int $iterator): bool
    {
        return $iterator < count($this->requests) && isset($this->requests[$iterator]);
    }

    /**
     * Determine if we should start a new request
     *
     * @param $requestsRunning
     * @param $iterator
     *
     * @return bool
     */
    private function shouldStartNewRequest(int $requestsRunning, int $iterator): bool
    {
        return $this->isUnderRequestLimit($requestsRunning) && $this->hasRequestsLeft($iterator);
    }

    /**
     * Save cpu cycles
     * prevent continuous checking
     */
    private function saveCycles()
    {
        return usleep(15);
    }
}
