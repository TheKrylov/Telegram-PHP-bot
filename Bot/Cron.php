<?php

namespace bot;
/**
 *
 */

use Bot\Winner as Winner;



class Cron
{


    public static function start()
    {
        date_default_timezone_set("Europe/Moscow");

        $time = date("H:i:s");

        //var_dump($time);


        global $userGay;

        if($time == '23:30:00') {
            $userGay = 0;
        }

        if($time == '00:00:00' and $userGay == 0) {
            Winner::fiesta();
            $userGay = 1;
        }



    }

}
