<?php

namespace bot;
/**
 *
 */

use Bot\Bot as Bot;
use Bot\Winner as Winner;
use Bot\Chat as Chat;
use Bot\Cron as Cron;
use Bot\User as User;

class Bootstrap
{

    //public $text;

    public static function getMessage($update)
    {
        //$update = Bot::getUpdate($update_id);

        $msg = $update["message"];

        //$chat = $chat["message"];

        $chat_id = $msg["chat"]["id"];
        $user_id = $msg["from"]["id"];
        $user_username = $msg["from"]["username"];
        $user_name = $msg["from"]["first_name"];



        $text = $msg["text"];


        if($text == '/gayon')
        {
            Chat::on($chat_id, 1, $user_id);
        }
        if($text == '/gayoff')
        {
            Chat::off($chat_id, 0, $user_id);
        }

        if($text == '/test')
        {
            Bot::sendMessage($chat_id, 'Успех!');
        }
        elseif ($text == '/top') {

            Winner::top($chat_id);

        }
        elseif ($text == '/force' and $user_username == 'NinjaCat') {

            Winner::fiesta();

        }
        elseif ($text == '/reg') {

            User::reg($chat_id, $user_id, $user_username, $user_name);

        }
        if($msg){
            var_dump($text);
        }

    }

    public static function start()
    {
        while(true){
            if( isset ($last_update) ){
                $last_update = $last_update;
            } else {
                $last_update = 0;
            }

            $get_update = Bot::getUpdate($last_update + 1);
            $last_update = $get_update['update_id'];

            self::getMessage($get_update);
            Cron::start();



            sleep(0.1);
        }
    }

}
