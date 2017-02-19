<?php

namespace Websocket\Routes;

use API\Models\User\Messages\UserMessage;
use Ratchet\ConnectionInterface;

class Chat extends WebsocketServer
{
    private $subscribers = array();

    public function onPublish(ConnectionInterface $connection, $topic, $event, array $exclude, array $eligible)
    {
        $connection->WAMP->subscriptions->rewind();
        $senderUserid = $connection->WAMP->subscriptions->current()->getId();
        $recieverUserid = $topic->getId();

        echo "Sending message from $senderUserid to userid $recieverUserid: $event\n";
        
        (new UserMessage())->setFromEventUserid($senderUserid)
                           ->setToEventUserid($recieverUserid)
                           ->setMessage($event)
                           ->setDate(time())
                           ->save();

        $message = array('sender' => $senderUserid,
                         'message' => $event);

        $topic->broadcast(json_encode($message));
    }
    
    public function onCall(ConnectionInterface $connection, $id, $topic, array $params)
    {
        if ($topic->getId() == 'systemMessage') {
            $recieverUserid = $params[0];
            $message = $params[1];

            echo "Sending System Message to userid $recieverUserid: $message\n";
            
            (new UserMessage())->setToEventUserid($recieverUserid)
                               ->setMessage($message)
                               ->setDate(time())
                               ->save();

            $message = array('sender' => '',
                             'message' => $message);

            if (isset($this->subscribers[$recieverUserid])) {
                $targetTopic = $this->subscribers[$recieverUserid]['topic'];

                $targetTopic->broadcast(json_encode($message));
            }

            return $connection->callResult($id);
        }

        return $connection->callError($id, $topic, 'RPC not supported');
    }

    public function onSubscribe(ConnectionInterface $connection, $topic)
    {
        echo "Chat Subscriber: $connection->resourceId for Userid: $topic\n";

        if (!isset($this->subscribers[$topic->getId()])) {
            $this->subscribers[$topic->getId()] = array('amount' => 1,
                                                            'topic' => $topic);
        } else {
            $this->subscribers[$topic->getId()]['amount']++;
        }
    }

    public function onUnSubscribe(ConnectionInterface $connection, $topic)
    {
        echo "Chat Unsubscriber: $connection->resourceId for Userid: $topic\n";

        if (isset($this->subscribers[$topic->getId()])) {
            $this->subscribers[$topic->getId()]['amount']--;

            if ($this->subscribers[$topic->getId()]['amount'] == 0) {
                unset($this->subscribers[$topic->getId()]);
            }
        }
    }
}