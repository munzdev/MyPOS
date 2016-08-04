<?php

namespace Websocket;

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;

class Chat implements WampServerInterface {

    private $o_db = null;

    public function __construct($o_db)
    {
        $this->o_db = $o_db;
    }

    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible) {
        $topic->broadcast($event);
    }

    public function onCall(ConnectionInterface $conn, $id, $topic, array $params) {
        $conn->callError($id, $topic, 'RPC not supported');
    }

    // No need to anything, since WampServer adds and removes subscribers to Topics automatically
    public function onSubscribe(ConnectionInterface $conn, $topic) {}
    public function onUnSubscribe(ConnectionInterface $conn, $topic) {}

    public function onOpen(ConnectionInterface $conn) {}
    public function onClose(ConnectionInterface $conn) {}
    public function onError(ConnectionInterface $conn, \Exception $e) {}
}