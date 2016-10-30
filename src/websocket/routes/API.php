<?php

namespace Websocket\Routes;

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;
use MyPOS;

class API implements WampServerInterface {

    private $a_subscribers = array();

    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible) {
        $conn->callError($conn->getId(), $topic, 'Publish not supported');
    }

    public function onCall(ConnectionInterface $o_connection, $id, $o_topic, array $params) {
        echo "API command " . $o_topic->getId() . "\n";

        switch($o_topic->getId())
        {
            case 'manager-callback':
                if(isset($this->a_subscribers[MyPOS\USER_ROLE_MANAGER]))
                {
                    $o_target_topic = $this->a_subscribers[MyPOS\USER_ROLE_MANAGER]['topic'];

                    $a_message = array('command' => $o_topic->getId(),
                                       'options' => array('systemMessage' => 'Eine Rückrufanforderung wurde hinzugefügt!'));

                    $o_target_topic->broadcast(json_encode($a_message));
                }
                break;

            case 'manager-check':
                if(isset($this->a_subscribers[MyPOS\USER_ROLE_MANAGER]))
                {
                    $o_target_topic = $this->a_subscribers[MyPOS\USER_ROLE_MANAGER]['topic'];

                    $a_message = array('command' => $o_topic->getId(),
                                       'options' => array('systemMessage' => 'Ein Sonderwunsch wurde hinzugefügt!'));

                    $o_target_topic->broadcast(json_encode($a_message));
                }
                break;

            case 'manager-groupmessage':
                $i_user_roleid = $params[0];

                $a_user_ids = array();

                if(isset($this->a_subscribers[$i_user_roleid]))
                {
                    $a_user_ids = array_values($this->a_subscribers[$i_user_roleid]['users']);
                }

                return $o_connection->callResult($id, $a_user_ids);
                break;

            case 'distribution-update':
                if(isset($this->a_subscribers[MyPOS\USER_ROLE_DISTRIBUTION]))
                {
                    $o_target_topic = $this->a_subscribers[MyPOS\USER_ROLE_DISTRIBUTION]['topic'];

                    $a_message = array('command' => $o_topic->getId());

                    $o_target_topic->broadcast(json_encode($a_message));
                }
                break;

            case 'global:product-update':
                $a_userids = array();

                foreach ($this->a_subscribers as $a_subscriber)
                {
                    $o_target_topic = $a_subscriber['topic'];

                    $a_userids_message_recieved = array_intersect($a_subscriber['users'], $a_userids);

                    $a_exclude = array();

                    foreach($a_subscriber['users'] as $i_sessionId => $i_userid)
                    {
                        if(array_search($i_userid, $a_userids_message_recieved) !== false)
                        {
                            $a_exclude[] = $i_sessionId;
                        }
                    }

                    $a_message = array('command' => $o_topic->getId());

                    $o_target_topic->broadcast(json_encode($a_message), $a_exclude);

                    $a_userids = array_merge($a_subscriber['users'], $a_userids);
                }
                break;

            default:
                $o_connection->callError($id, $o_topic, 'Command not supported');
                break;
        }
    }

    public function onSubscribe(ConnectionInterface $o_connection, $o_topic)
    {
        list($i_userid, $i_user_roleid) = explode('-', $o_topic->getId());

        echo "API Subscriber: $o_connection->resourceId with Userid $i_userid for Role: $i_user_roleid\n";

        if(!isset($this->a_subscribers[$i_user_roleid]))
        {
            $this->a_subscribers[$i_user_roleid] = array('users' => array($o_connection->WAMP->sessionId => $i_userid),
                                                         'topic' => $o_topic);
        }
        else
        {
            $this->a_subscribers[$i_user_roleid]['users'][$o_connection->resourceId] = $i_userid;
        }
    }

    public function onUnSubscribe(ConnectionInterface $o_connection, $o_topic)
    {
        list($i_userid, $i_user_roleid) = explode('-', $o_topic->getId());

        echo "API Unsubscriber: $o_connection->resourceId with Userid $i_userid  for Role: $i_user_roleid\n";

        if(isset($this->a_subscribers[$i_user_roleid]))
        {
            $i_resourceId = array_search($i_userid, $this->a_subscribers[$i_user_roleid]['users']);

            if($i_resourceId)
                unset($this->a_subscribers[$i_user_roleid]['users'][$i_resourceId]);

            if(count($this->a_subscribers[$i_user_roleid]['users']) == 0)
                unset($this->a_subscribers[$i_user_roleid]);
        }
    }

    public function onOpen(ConnectionInterface $o_connection)
    {
        echo "API Connection open: $o_connection->resourceId\n";
    }

    public function onClose(ConnectionInterface $o_connection)
    {
        echo "API Connection closed: $o_connection->resourceId\n";
    }

    public function onError(ConnectionInterface $o_connection, \Exception $o_exception)
    {
        echo "API Connection error: $o_connection->resourceId -> $o_exception\n";
    }
}