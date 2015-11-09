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
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Apple\ApnPush\Notification\Message;
use Apple\ApnPush\Notification\Connection;
use Apple\ApnPush\Notification\PayloadFactory;
use Apple\ApnPush\Notification\Notification;

/**
 * Apn push notification command
 */
class PushCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('apple:apn-push:send')
            ->setDescription('Push notification to iOS devices')
            ->addArgument('certificate-file', InputArgument::REQUIRED, 'Certificate file')
            ->addArgument('device-token', InputArgument::REQUIRED, 'Device token')
            ->addArgument('message', InputArgument::REQUIRED, 'Push message')
            ->addOption('sound', 's', InputOption::VALUE_OPTIONAL, 'Sound option')
            ->addOption('badge', 'b', InputOption::VALUE_OPTIONAL, 'Badge option')
            ->addOption('pass-phrase', 'p', InputOption::VALUE_OPTIONAL, 'Pass phrase for certificate file')
            ->addOption('sandbox', null, InputOption::VALUE_NONE, 'Usage sandbox mode');
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

        // Create payload factory
        $payloadFactory = new PayloadFactory();

        // Create notification system
        $notification = new Notification;
        $notification->setPayloadFactory($payloadFactory);
        $notification->setConnection($connection);

        // Create message
        $message = new Message();
        $message->setDeviceToken($input->getArgument('device-token'));

        $apsData = $message->getApsData();
        $apsData->setBody($input->getArgument('message'));
        $apsData->setSound($input->getOption('sound'));
        $apsData->setBadge($input->getOption('badge'));

        // Send message
        try {
            $notification->send($message);
            $output->writeln('<info>Success send push.</info>');
        } catch (\Exception $e) {
            $output->writeln('<error>Error send push notification with message: ' . $e->getMessage() . '.</error>');
        }
    }
}
