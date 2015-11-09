<?php

/*
 * This file is part of the AppleApnPush package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Apple\ApnPush\Command;

use Apple\ApnPush\Certificate\Certificate;
use Apple\ApnPush\Feedback\Connection;
use Apple\ApnPush\Feedback\Feedback;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Apn feedback command
 */
class FeedbackCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('apple:apn-push:feedback')
            ->setDescription('View all invalid device tokens')
            ->addArgument('certificate-file', InputArgument::REQUIRED, 'Certificate file')
            ->addOption('pass-phrase', 'p', InputOption::VALUE_OPTIONAL, 'Pass phrase for certificate file')
            ->addOption('sandbox', null, InputOption::VALUE_NONE, 'Usage sandbox mode')
            ->addOption('inline', null, InputOption::VALUE_NONE, 'Inline view');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Create connection
        $certificate = new Certificate(
            $input->getArgument('certificate-file'),
            $input->getArgument('pass-phrase')
        );

        $connection = new Connection(
            $certificate,
            (bool) $input->getOption('sandbox')
        );

        $feedback = new Feedback($connection);

        /** @var \Apple\ApnPush\Feedback\Device[] $invalidDevices */
        $invalidDevices = $feedback->getInvalidDevices();

        if (!count($invalidDevices)) {
            /** @var \Symfony\Component\Console\Helper\FormatterHelper $formatter */
            $formatter = $this->getHelperSet()->get('formatter');
            $message = $formatter->formatBlock(array(
                'Successfully!',
                'Invalid device tokens not found.'
            ), 'info', true);

            $output->writeln($message);

            return 0;
        }

        if (!$input->getOption('inline') && class_exists('Symfony\Component\Console\Helper\TableHelper')) {
            // Symfony/Console >= 2.3, can use table helper
            /** @var \Symfony\Component\Console\Helper\TableHelper $table */
            $table = $this->getHelperSet()->get('table');

            $table->setHeaders(array(
                'Timestamp',
                'Device token'
            ));

            foreach ($invalidDevices as $invalidDevice) {
                $table->addRow(array(
                    $invalidDevice->getTimestamp(),
                    $invalidDevice->getDeviceToken()
                ));
            }

            $table->render($output);

        } else {
            // Symfony/Console < 2.3, table helper not found
            foreach ($invalidDevices as $invalidDevice) {
                $output->writeln($invalidDevice->getTimestamp() . ':' . $invalidDevice->getDeviceToken());
            }
        }

        return 0;
    }
}
