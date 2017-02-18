<?php

namespace Websocket\Routes;

use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;
use Ratchet\Wamp\WampServerInterface;
use const API\DEBUG;

abstract class WebsocketServer implements WampServerInterface
{
    /**
     *
     * @param ConnectionInterface $connection
     * @param Topic               $topic
     * @param string              $event
     * @param array               $exclude
     * @param array               $eligible
     */
    public function onPublish(ConnectionInterface $connection, $topic, $event, array $exclude, array $eligible)
    {
        if (DEBUG) {
            $connection->WAMP->subscriptions->rewind();
            $sender = $connection->WAMP->subscriptions->current()->getId();
            $reciever = $topic->getId();

            echo get_class($this) . " Publish from $sender to $reciever: $event\n";
        }
    }
    
    /**
     *
     * @param ConnectionInterface $connection
     * @param string              $id
     * @param Topic               $topic
     * @param array               $params
     */
    public function onCall(ConnectionInterface $connection, $id, $topic, array $params)
    {
        if (DEBUG) {
            echo get_class($this) . " command " . $topic->getId() . "\n";
        }
    }

    /**
     *
     * @param ConnectionInterface $connection
     * @param Topic               $topic
     */
    public function onSubscribe(ConnectionInterface $connection, $topic)
    {
        if (DEBUG) {
            echo get_class($this) . " Subscriber: $connection->resourceId for : $topic\n";
        }
    }

    /**
     *
     * @param ConnectionInterface $connection
     * @param Topic               $topic
     */
    public function onUnSubscribe(ConnectionInterface $connection, $topic)
    {
        if (DEBUG) {
            echo get_class($this) . " Unsubscriber: $connection->resourceId for : $topic\n";
        }
    }

    public function onOpen(ConnectionInterface $connection)
    {
        if (DEBUG) {
            echo get_class($this) . " Connection open: $connection->resourceId\n";
        }
    }

    public function onClose(ConnectionInterface $connection)
    {
        if (DEBUG) {
            echo get_class($this) . " Connection closed: $connection->resourceId\n";
        }
    }

    public function onError(ConnectionInterface $connection, Exception $exception)
    {
        if (DEBUG) {
            echo get_class($this) . " Connection error: $connection->resourceId -> $exception\n";
        }
    }
}
