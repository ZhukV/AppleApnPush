## v3.1.1

* Add `subtitle` property to alert.


## v3.1.0

* Add support PHP 8.
* Add cached JWT signature generator (require any [cache adapter](https://packagist.org/packages/psr/simple-cache) based on [PSR-16](https://www.php-fig.org/psr/psr-16/)).

## v3.0.10

### Features

* Add possible to send sound as an [object](https://developer.apple.com/documentation/usernotifications/setting_up_a_remote_notification_server/generating_a_remote_notification#2990112).

### Impact

Make changes without impact. 

## v3.0.7

### Features

* Add possible for make a aps without alert for send a [background notification](https://developer.apple.com/documentation/usernotifications/setting_up_a_remote_notification_server/pushing_background_updates_to_your_app).
* Update phpunit package version to `8.*`.

### Impact

Make changes without impact.

## v3.0.6

### Features

* Add `JwtSignatureGenerator` for available use any libraries for generating the signature from JWT token. Support next libraries:
    * [spomky-labs/jose](https://github.com/Spomky-Labs/jose)
    * [web-token/jwt-*](https://www.gitbook.com/book/web-token/jwt-framework)
* Mark as **deprecated** method `Apple\ApnPush\Sender\Builder\Http20Builder::addDefaultVisitors`. This method executed from 
the `constructor` of builder. In next minor version, we remove this method.  

### Impact

Make changes without impact, all code has a BC. If previously you use `SpomkyLabs`, the factory has been successfully 
creating require generator.

## v3.0.5

* Fix code for Code Style

## v3.0.4

### Features

* Add lifetime for `JwtAuthenticator`.

## v3.0.3

### Feature

* Add able for getting the protocol from the builder `Apple\ApnPush\Sender\Builder\BuilderInterface::buildProtocol`.
* Add able to close the connection manually `Apple\ApnPush\Protoco\ProtocolInterface::closeConnection`.

### Impact

* The exception `Apple\ApnPush\Protocol\Http\Sender\Exception\CurlException` has been renamed to `Apple\ApnPush\Protocol\Http\Sender\Exception\HttpSenderException`

## v3.0.2

### Feature

* Add `apns-collapse-id` to notification.

## v3.0.1

* Fix code for Code Style

## v3.0.0 (New version of library)

### Feature

* Implement a new version of the library for use HTTP/2 Protocol and work with the strict mode in PHP (>= 7.1)

### Impact to v2.*

* Does not support for migration from `v2.*`.
* Does not support the binary protocol.
