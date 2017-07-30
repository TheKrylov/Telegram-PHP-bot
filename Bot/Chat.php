<?php

namespace bot;
/**
 *
 */

use Bot\Bot as Bot;

class Chat
{
    const TABLE = 'chats';


    public static function on($chat_id, $status, $user_id)
    {
        $admin = Bot::getUserStatus($chat_id, $user_id);

        if($admin == 'administrator' or $admin == 'creator') {
            $chat_type = Bot::getChatType($chat_id);
            if($chat_type == 'group' or $chat_type == 'supergroup'){

                $db = new Db();

                $sql_reg =
                '
                INSERT INTO chats (chat, status) VALUES (:chat, :status)
                ';

                $params = [
                    ':chat'=>(string)$chat_id,
                    ':status'=>$status,
                ];
                //var_dump($params);
                $res = $db->execute($sql_reg, $params);

                if (false !== $res){
                    Bot::sendMessage($chat_id, 'Let\'s get this party started!');
                    //return $res;
                }else{
                    $sql_update =
                    '
                    UPDATE chats
                    SET status = :status
                    WHERE chat = :chat
                    ';
                    $params = [
                        ':chat'=>(string)$chat_id,
                        ':status'=>$status,
                    ];
                    $res = $db->execute($sql_update, $params);
                    if (false !== $res) {
                        Bot::sendMessage($chat_id, 'Let\'s get this party started!');
                    } else {
                        return $res;
                    }

                }

            } else {
                Bot::sendMessage($chat_id, 'Гей-вечеринки только для групп!');
            }
        }else{
            Bot::sendMessage($chat_id, 'Ты тут не командуешь!');
        }



        //return $db->query($sql);
    }

    public static function off($chat_id, $status, $user_id)
    {
        $admin = Bot::getUserStatus($chat_id, $user_id);

        if($admin == 'administrator' or $admin == 'creator') {
            $db = new Db();
            $sql_update =
            '
            UPDATE chats
            SET status = :status
            WHERE chat = :chat
            ';
            $params = [
                ':chat'=>(string)$chat_id,
                ':status'=>$status,
            ];

            $res = $db->execute($sql_update, $params);

            if(false !== $res) {
                Bot::sendMessage($chat_id, 'Вечеринка окончена!');
            }else{
                Bot::sendMessage($chat_id, '0');
            }
        }else{
            Bot::sendMessage($chat_id, 'Ты тут не командуешь!');
        }


    }
    public static function win($date, $chat, $user)
    {
        $db = new Db();

        $sql_win =
        "
        INSERT INTO winners (date, chat, user) VALUES (:date , :chat, :user)
        ";

        $params = [
            ':date'=>$date,
            ':chat'=>$chat,
            ':user'=>$user,
        ];
        return $db->execute($sql, $params);
    }


    public static function findId($id)
    {
        $db = new Db();

        $sql_chat =
        '
        SELECT *
        FROM chats
        WHERE chat = '.$id.'
        ';

        $chat = $db->query($sql_chat);
        return $chat['0']['id'];
    }
}
