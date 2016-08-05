<?php
namespace Model;

use PDO;

class Users_Messages
{
    private $o_db;

    public function __construct(PDO $o_db)
    {
        $this->o_db = $o_db;
    }

    public function AddMessage($i_sender_events_userid, $i_reciever_events_userid, $str_message)
    {
        $o_statement = $this->o_db->prepare("INSERT INTO users_messages (from_events_userid,
                                                                         to_events_userid,
                                                                         message,
                                                                         date,
                                                                         readed)
                                                                VALUES  (:sender_events_userid,
                                                                         :reciever_events_userid,
                                                                         :message,
                                                                         NOW(),
                                                                         0)");

        $o_statement->bindParam(':sender_events_userid', $i_sender_events_userid);
        $o_statement->bindParam(':reciever_events_userid', $i_reciever_events_userid);
        $o_statement->bindParam(':message', $str_message);
        $o_statement->execute();

        return $this->o_db->lastInsertId();
    }

    public function GetMessages($i_sender_events_userid, $i_reciever_events_userid)
    {
        $o_statement = $this->o_db->prepare("SELECT message,
                                                    date,
                                                    readed
                                             FROM users_messages
                                             WHERE from_events_userid = :sender_events_userid
                                                   AND reciever_events_userid = :reciever_events_userid
                                             ORDER BY date DESC
                                             LIMIT 15");

        $o_statement->bindParam(':sender_events_userid', $i_sender_events_userid);
        $o_statement->bindParam(':reciever_events_userid', $i_reciever_events_userid);
        $o_statement->execute();

        return $o_statement->fetchAll();
    }
}