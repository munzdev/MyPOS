<?php

namespace Websocket;

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;
use MyPOS;

class API implements WampServerInterface {

    private $a_subscribers = array();

    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible) {
        $conn->callError($conn->getId(), $topic, 'Publish not supported');
    }

    public function onCall(ConnectionInterface $o_connection, $id, $o_topic, array $params) {
        switch($o_topic->getId())
        {
            case 'manager-callback':
                if(isset($this->a_subscribers[MyPOS\USER_ROLE_MANAGER]))
                {
                    echo "API command manager-callback\n";

                    $o_target_topic = $this->a_subscribers[MyPOS\USER_ROLE_MANAGER]['topic'];

                    $a_message = array('command' => $o_topic->getId(),
                                       'options' => array('systemMessage' => 'Eine Rückrufanforderung wurde hinzugefügt!'));

                    $o_target_topic->broadcast(json_encode($a_message));
                }
                break;

            default:
                $o_connection->callError($id, $o_topic, 'Command not supported');
                break;
        }
    }

    // No need to anything, since WampServer adds and removes subscribers to Topics automatically
    public function onSubscribe(ConnectionInterface $o_connection, $o_topic)
    {
        echo "API Subscriber: $o_connection->resourceId for Role: $o_topic\n";

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
    public function onUnSubscribe(ConnectionInterface $o_connection, $o_connection)
    {
        echo "API Unsubscriber: $o_connection->resourceId for Role: $o_topic\n";

        if(isset($this->a_subscribers[$o_topic->getId()]))
        {
            $this->a_subscribers[$o_topic->getId()]['amount']--;

            if($this->a_subscribers[$o_topic->getId()]['amount'] == 0)
                unset($this->a_subscribers[$o_topic->getId()]);
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