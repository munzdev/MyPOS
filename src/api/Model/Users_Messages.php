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

    public function GetMessages($i_events_userid)
    {
        $o_statement = $this->o_db->prepare("SELECT from_events_userid,
                                                    to_events_userid
                                             FROM users_messages
                                             WHERE from_events_userid = :events_userid
                                                   OR to_events_userid = :events_userid
                                             GROUP BY from_events_userid,
                                                      to_events_userid");

        $o_statement->bindParam(':events_userid', $i_events_userid);
        $o_statement->execute();

        $a_message_groupes = $o_statement->fetchAll();
        $a_message_groupes_done = array();
        $a_return = array();

        $o_statement = $this->o_db->prepare("SELECT from_events_userid,
                                                    to_events_userid,
                                                    `date`,
                                                    message,
                                                    readed
                                             FROM users_messages
                                             WHERE (:from_events_userid IS NULL
                                                    AND from_events_userid IS NULL
                                                    AND to_events_userid = :to_events_userid)
                                                   OR
                                                   (from_events_userid = :from_events_userid
                                                    AND to_events_userid = :to_events_userid)
                                                   OR
                                                   (from_events_userid = :to_events_userid
                                                    AND to_events_userid = :from_events_userid)
                                             ORDER BY `date` DESC
                                             LIMIT 15");

        foreach($a_message_groupes as $a_message_groupe)
        {
            $str_group1 = $a_message_groupe['from_events_userid'] . ',' . $a_message_groupe['to_events_userid'];
            $str_group2 = $a_message_groupe['to_events_userid'] . ',' . $a_message_groupe['from_events_userid'];

            if(in_array($str_group1, $a_message_groupes_done) ||
               in_array($str_group2, $a_message_groupes_done))
                continue;

            $a_message_groupes_done[] = $str_group1;
            $a_message_groupes_done[] = $str_group2;

            $o_statement->bindParam(':from_events_userid', $a_message_groupe['from_events_userid'], PDO::PARAM_INT);
            $o_statement->bindParam(':to_events_userid', $a_message_groupe['to_events_userid'], PDO::PARAM_INT);
            $o_statement->execute();

            $a_return = array_merge($a_return, $o_statement->fetchAll());

            $o_statement->closeCursor();
        }

        return $a_return;
    }

    public function MarkReaded($i_events_userid, $str_channel)
    {
        if($str_channel == '')
        {
            $o_statement = $this->o_db->prepare("UPDATE users_messages
                                                 SET readed = 1
                                                 WHERE from_events_userid IS NULL
                                                       AND to_events_userid = :to_events_userid");
        }
        else
        {
            $o_statement = $this->o_db->prepare("UPDATE users_messages
                                                 SET readed = 1
                                                 WHERE from_events_userid = :from_events_userid
                                                       AND to_events_userid = :to_events_userid");

            $o_statement->bindParam(':from_events_userid', $str_channel);
        }

        $o_statement->bindParam(':to_events_userid', $i_events_userid);
        $o_statement->execute();
    }
}