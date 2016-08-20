<?php

namespace Websocket;

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;
use Model\Users_Messages;
use Model\Users;

class Chat implements WampServerInterface {

    private $o_users_messages = null;

    private $a_subscribers = array();

    public function __construct(Users_Messages $o_users_messages)
    {
        $this->o_users_messages = $o_users_messages;
    }

    public function onPublish(ConnectionInterface $o_connection, $o_topic, $str_event, array $a_exclude, array $a_eligible) {
        $o_connection->WAMP->subscriptions->rewind();
        $i_sender_userid = $o_connection->WAMP->subscriptions->current()->getId();
        $i_reciever_userid = $o_topic->getId();

        echo "Sending message from $i_sender_userid to userid $i_reciever_userid: $str_event\n";
        $this->o_users_messages->AddMessage($i_sender_userid, $i_reciever_userid, $str_event);

        $a_message = array('sender' => $i_sender_userid,
                           'message' => $str_event);

        $o_topic->broadcast(json_encode($a_message));
    }

    public function onCall(ConnectionInterface $o_connection, $str_id, $o_topic, array $a_params) {
        if($o_topic->getId() == 'systemMessage')
        {
            $i_reciever_userid = $a_params[0];
            $str_message = $a_params[1];

            echo "Sending System Message to userid $i_reciever_userid: $str_message\n";
            $this->o_users_messages->AddMessage(null, $i_reciever_userid, $str_message);

            $a_message = array('sender' => '',
                               'message' => $str_message);

            if(isset($this->a_subscribers[$i_reciever_userid]))
            {
                $o_target_topic = $this->a_subscribers[$i_reciever_userid]['topic'];

                $o_target_topic->broadcast(json_encode($a_message));
            }

            return $o_connection->callResult($str_id);
        }

        return $o_connection->callError($str_id, $o_topic, 'RPC not supported');
    }

    // No need to anything, since WampServer adds and removes subscribers to Topics automatically
    public function onSubscribe(ConnectionInterface $o_connection, $o_topic)
    {
        echo "Chat Subscriber: $o_connection->resourceId for Userid: $o_topic\n";

        if(!isset($this->a_subscribers[$o_topic->getId()]))
        {
            $this->a_subscribers[$o_topic->getId()] = array('amount' => 1,
                                                          'topic' => $o_topic);
        }
        else
        {
            $this->a_subscribers[$o_topic->getId()]['amount']++;
        }
    }

    public function onUnSubscribe(ConnectionInterface $o_connection, $o_topic)
    {
        echo "Chat Unsubscriber: $o_connection->resourceId for Userid: $o_topic\n";

        if(isset($this->a_subscribers[$o_topic->getId()]))
        {
            $this->a_subscribers[$o_topic->getId()]['amount']--;

            if($this->a_subscribers[$o_topic->getId()]['amount'] == 0)
                unset($this->a_subscribers[$o_topic->getId()]);
        }
    }

    public function onOpen(ConnectionInterface $o_connection)
    {
        echo "Chat Connection open: $o_connection->resourceId\n";
    }

    public function onClose(ConnectionInterface $o_connection)
    {
        echo "Chat Connection closed: $o_connection->resourceId\n";
    }

    public function onError(ConnectionInterface $o_connection, \Exception $o_exception)
    {
        echo "Chat Connection error: $o_connection->resourceId -> $o_exception\n";
    }
}