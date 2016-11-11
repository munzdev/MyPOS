<?php

namespace Websocket\Routes;

use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;
use Ratchet\Wamp\WampServerInterface;
use const API\DEBUG;

class WebsocketServer implements WampServerInterface {

    /**
     * 
     * @param ConnectionInterface $o_connection
     * @param Topic $o_topic
     * @param string $str_event
     * @param array $a_exclude
     * @param array $a_eligible
     */
    public function onPublish(ConnectionInterface $o_connection, $o_topic, $str_event, array $a_exclude, array $a_eligible) {
        if(DEBUG) {
            $o_connection->WAMP->subscriptions->rewind();
            $i_sender = $o_connection->WAMP->subscriptions->current()->getId();
            $i_reciever = $o_topic->getId();

            echo get_class($this) . " Publish from $i_sender to $i_reciever: $str_event\n";
        }
    }
    
    /**
     * 
     * @param ConnectionInterface $o_connection
     * @param string $str_id
     * @param Topic $o_topic
     * @param array $a_params
     */
    public function onCall(ConnectionInterface $o_connection, $str_id, $o_topic, array $a_params) {
        if(DEBUG) echo get_class($this) . " command " . $o_topic->getId() . "\n";
    }

    /**
     * 
     * @param ConnectionInterface $o_connection
     * @param Topic $o_topic
     */
    public function onSubscribe(ConnectionInterface $o_connection, $o_topic)
    {
        if(DEBUG) echo get_class($this) . " Subscriber: $o_connection->resourceId for : $o_topic\n";
    }

    /**
     * 
     * @param ConnectionInterface $o_connection
     * @param Topic $o_topic
     */
    public function onUnSubscribe(ConnectionInterface $o_connection, $o_topic)
    {
        if(DEBUG) echo get_class($this) . " Unsubscriber: $o_connection->resourceId for : $o_topic\n";
    }

    public function onOpen(ConnectionInterface $o_connection)
    {
        if(DEBUG) echo get_class($this) . " Connection open: $o_connection->resourceId\n";
    }

    public function onClose(ConnectionInterface $o_connection)
    {
        if(DEBUG) echo get_class($this) . " Connection closed: $o_connection->resourceId\n";
    }

    public function onError(ConnectionInterface $o_connection, Exception $o_exception)
    {
        if(DEBUG) echo get_class($this) . " Connection error: $o_connection->resourceId -> $o_exception\n";
    }
}