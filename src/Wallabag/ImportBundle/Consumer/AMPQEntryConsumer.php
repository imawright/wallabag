<?php

namespace Wallabag\ImportBundle\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class AMPQEntryConsumer extends AbstractConsumer implements ConsumerInterface
{
    /**
     * {@inheritdoc}
     */
    public function execute(AMQPMessage $msg)
    {
        return $this->handleMessage($msg->body);
    }
}
