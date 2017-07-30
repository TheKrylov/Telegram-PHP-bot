<?php

namespace bot;
/**
 *
 */


class Bot
{


    public static function connect($method, $data=[])
    {
        $config = include __DIR__ . '/../config.php';

        $url = "https://api.telegram.org/bot".$config['token']."/".$method;

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $res = curl_exec($ch);
        curl_close($ch);

         //$res = file_get_contents($url . '?' . http_build_query($data));

         return json_decode($res,true);

    }

    public static function getUpdate($update_id)
    {

        $update = self::connect('getUpdates',[
          'offset'=>$update_id,
        ]);
        if(is_array($update)){
            return current($update["result"]);
        } else {
            return $update["result"];
        }
    }

    public static function sendMessage($chat, $text)
    {
        $send = self::connect('sendMessage', [
            'chat_id' => $chat,
            'text'    => $text,
            'parse_mode' => 'html',
        ]);
    }

    public static function getUsername($chat, $user)
    {
        $get = self::connect('getChatMember', [
            'chat_id'=>$chat,
            'user_id'=>$user,
        ]);
        return $get['result']['user']['username'];
    }
    public static function getUserStatus($chat, $user)
    {
        $get = self::connect('getChatMember', [
            'chat_id'=>$chat,
            'user_id'=>$user,
        ]);
        return $get['result']['status'];
    }

    public static function getChatType($chat_id)
    {

        $chat = self::connect('getChat',[
          'chat_id'=>$chat_id,
        ]);
        return $chat['result']['type'];
    }
}
