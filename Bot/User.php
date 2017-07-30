<?php

namespace bot;
/**
 *
 */

use Bot\Bot as Bot;
use Bot\Chat as Chat;

class User
{

    public static function reg($tg_chat_id, $user_id, $user_username, $user_name)
    {
        $db = new Db();

        $chat_id = Chat::findId($tg_chat_id);


        //var_dump($chat);

        $sql_user =
        '
        INSERT INTO users (tg_id, tg_username, tg_name, chats)
        VALUES (:tg_id , :tg_username, :tg_name, :chats)
        ';

        $params = [
            ':tg_id'=>$user_id,
            ':tg_username'=>$user_username,
            ':tg_name'=>$user_name,
            ':chats'=>$chat_id,
        ];

        $res = $db->execute($sql_user, $params);

        $message = 'Поздравляю! Ты совершил каминг-аут, теперь ты в игре 🌈';

        if(false !== $res){

            Bot::sendMessage($tg_chat_id, $message);
        }else{
            $sql_update =
            '
            UPDATE users
            SET chats = CONCAT( chats, ",", '.$chat_id.' )
            WHERE tg_id = '.$user_id.' AND FIND_IN_SET('.$chat_id.', chats) = 0
            ';

            $res = $db->execute($sql_update);

            //var_dump($res);
            if( $res ){
                Bot::sendMessage($tg_chat_id, $message);
            }else{
                Bot::sendMessage($tg_chat_id, '😡');
            }
        }

    }



}
