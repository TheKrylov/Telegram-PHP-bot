<?php

namespace bot;
/**
 *
 */

use Bot\Bot as Bot;

class Winner
{
    const TABLE = 'winners';


    public static function top($chat_id)
    {

        $db = new Db();

        $sql =
        '
        SELECT w.user, w.chat, COUNT(w.user) AS count
        FROM winners w
        WHERE w.chat = '.$chat_id.'
        GROUP BY w.user
        ORDER BY count DESC, w.user
        ';


        $res_top = $db->query($sql);

        //var_dump($res_top);

        $top = "Рейтинг пидоров:\n\n";
        foreach ($res_top as $key => $value) {
            $username = Bot::getUsername('-1001114488725', $value['user']);
            $top .= "<b>".$username."</b> - ". $value['count'] ."\n";
        }
        Bot::sendMessage($chat_id, $top);
    }

    public static function fiesta(){
        $db = new Db();

        $sql =
        '
        SELECT *
        FROM chats
        WHERE status = 1
        ';

        $chats = $db->query($sql);

        foreach ($chats as $key => $value) {
            $id = $value['id'];
            $chat_id = $value['chat'];
            self::win($chat_id, $id);
        }

        //var_dump($chats);

    }
    public static function win($chat_id, $id)
    {

        $db = new Db();
        //$id = 1;

        $sql_users =
        '
        SELECT u.*
        FROM users u
        WHERE FIND_IN_SET ('.$id.', u.chats)
        ';

        $users = $db->query($sql_users);

        $winner_arr = [];

        foreach ($users as $key => $user) {
            $winner_arr[$key] = $user['tg_id'];
        }

        $winner_key = array_rand($winner_arr, 1);

        $winner = $winner_arr[$winner_key];
        //var_dump($winner_arr);

        $date = date("Y-m-d");

        $sql_win =
        "
        INSERT INTO winners (date, chat, user) VALUES (:date , :chat, :user)
        ";

        $params = [
            ':date'=>$date,
            ':chat'=>$chat_id,
            ':user'=>$winner,
        ];
        $res = $db->execute($sql_win, $params);
        if(false !== $res) {
            $winner_name = Bot::getUsername($chat_id, $winner);

            $message = "Согласно моей информации, пидор дня - <b>".$winner_name."</b>";

//             $message2 =
//             "
//             .∧＿∧
// ( ･ω･｡)つ━☆・*。
// ⊂　 ノ 　　　・゜+.
// しーＪ　　　°。+ *´¨)
// 　　　　　　　　　.· ´¸.·*´¨)
// 　　　　　　　　　　(¸.·´ (¸.·'* ☆ Вжух и пидор дня - <b>$winner_name</b> 👑
//             ";

            Bot::sendMessage($chat_id, $message);

        }else{
            Bot::sendMessage($chat_id, '🙈');
        }
    }



}
