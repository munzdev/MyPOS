<?php

namespace Websocket;

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;
use Model\Users_Messages;
use Model\Users;

class Chat implements WampServerInterface {

    private $o_users_messages = null;

    public function __construct(Users_Messages $o_users_messages)
    {
        $this->o_users_messages = $o_users_messages;
    }

    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible) {
        $conn->WAMP->subscriptions->rewind();
        $i_sender_userid = $conn->WAMP->subscriptions->current()->getId();
        $i_reciever_userid = $topic->getId();

        echo "Sending message from $i_sender_userid to userid $i_reciever_userid: $event\n";
        $this->o_users_messages->AddMessage($i_sender_userid, $i_reciever_userid, $event);

        $a_message = array('sender' => $i_sender_userid,
                           'message' => $event);

        $topic->broadcast(json_encode($a_message));
    }

    public function onCall(ConnectionInterface $conn, $id, $topic, array $params) {
        if($id == 'systemMessage')
        {
            $i_sender_userid = $conn->WAMP->subscriptions->current()->getId();
            $i_reciever_userid = $params['userid'];
            $str_message = $params['message'];

            echo "Sending System Message from $i_sender_userid to userid $i_reciever_userid: $str_message\n";
            $this->o_users_messages->AddMessage(null, $i_reciever_userid, $str_message);

            $a_message = array('sender' => 0,
                               'message' => $str_message);

            $topic->broadcast(json_encode($a_message));
        }

        $conn->callError($id, $topic, 'RPC not supported');
    }

    // No need to anything, since WampServer adds and removes subscribers to Topics automatically
    public function onSubscribe(ConnectionInterface $conn, $topic)
    {
        echo "Chat Subscriber: $conn->resourceId for Userid: $topic\n";
    }

    public function onUnSubscribe(ConnectionInterface $conn, $topic)
    {
        echo "Chat Unsubscriber: $conn->resourceId for Userid: $topic\n";
    }

    public function onOpen(ConnectionInterface $conn)
    {
        echo "Chat Connection open: $conn->resourceId\n";
    }

    public function onClose(ConnectionInterface $conn)
    {
        echo "Chat Connection closed: $conn->resourceId\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Chat Connection error: $conn->resourceId -> $e\n";
    }
}