<?php

namespace Websocket\Routes;

use Ratchet\ConnectionInterface;
use const API\USER_ROLE_DISTRIBUTION;
use const API\USER_ROLE_MANAGER;

class API extends WebsocketServer
{
    private $subscribers = array();
    
    public function onPublish(ConnectionInterface $connection, $topic, $event, array $exclude, array $eligible)
    {
        $connection->callError($connection->getId(), $topic, 'Publish not supported');
    }

    public function onCall(ConnectionInterface $connection, $id, $topic, array $params)
    {
        echo "API command " . $topic->getId() . "\n";

        switch ($topic->getId()) {
            case 'manager-callback':
                if (isset($this->subscribers[USER_ROLE_MANAGER])) {
                    $targetTopic = $this->subscribers[USER_ROLE_MANAGER]['topic'];

                    $message = array('command' => $topic->getId(),
                                     'options' => array('systemMessage' => 'Eine Rückrufanforderung wurde hinzugefügt!'));

                    $targetTopic->broadcast(json_encode($message));
                }
                break;

            case 'manager-check':
                if (isset($this->subscribers[USER_ROLE_MANAGER])) {
                    $targetTopic = $this->subscribers[USER_ROLE_MANAGER]['topic'];

                    $message = array('command' => $topic->getId(),
                                     'options' => array('systemMessage' => 'Ein Sonderwunsch wurde hinzugefügt!'));

                    $targetTopic->broadcast(json_encode($message));
                }
                break;

            case 'manager-groupmessage':
                $userRoleid = $params[0];

                $userids = array();

                if (isset($this->subscribers[$userRoleid])) {
                    $userids = array_values($this->subscribers[$userRoleid]['users']);
                }

                return $connection->callResult($id, $userids);

            case 'distribution-update':
                if (isset($this->subscribers[USER_ROLE_DISTRIBUTION])) {
                    $targetTopic = $this->subscribers[USER_ROLE_DISTRIBUTION]['topic'];

                    $message = array('command' => $topic->getId());

                    $targetTopic->broadcast(json_encode($message));
                }
                break;

            case 'global:product-update':
                $userids = array();

                foreach ($this->subscribers as $subscriber) {
                    $targetTopic = $subscriber['topic'];

                    $useridsMessageRecieved = array_intersect($subscriber['users'], $userids);

                    $exclude = array();

                    foreach ($subscriber['users'] as $sessionId => $userid) {
                        if (array_search($userid, $useridsMessageRecieved) !== false) {
                            $exclude[] = $sessionId;
                        }
                    }

                    $message = array('command' => $topic->getId());

                    $targetTopic->broadcast(json_encode($message), $exclude);

                    $userids = array_merge($subscriber['users'], $userids);
                }
                break;

            default:
                $connection->callError($id, $topic, 'Command not supported');
                break;
        }
    }

    public function onSubscribe(ConnectionInterface $connection, $topic)
    {
        list($userid, $userRoleid) = explode('-', $topic->getId());

        echo "API Subscriber: $connection->resourceId with Userid $userid for Role: $userRoleid\n";

        if (!isset($this->subscribers[$userRoleid])) {
            $this->subscribers[$userRoleid] = array('users' => array($connection->WAMP->sessionId => $userid),
                                                         'topic' => $topic);
        } else {
            $this->subscribers[$userRoleid]['users'][$connection->resourceId] = $userid;
        }
    }

    public function onUnSubscribe(ConnectionInterface $connection, $topic)
    {
        list($userid, $userRoleid) = explode('-', $topic->getId());

        echo "API Unsubscriber: $connection->resourceId with Userid $userid  for Role: $userRoleid\n";

        if (isset($this->subscribers[$userRoleid])) {
            $resourceId = array_search($userid, $this->subscribers[$userRoleid]['users']);

            if ($resourceId) {
                unset($this->subscribers[$userRoleid]['users'][$resourceId]);
            }

            if (count($this->subscribers[$userRoleid]['users']) == 0) {
                unset($this->subscribers[$userRoleid]);
            }
        }
    }
}
