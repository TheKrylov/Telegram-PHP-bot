<?php

namespace bot;
/**
 *
 */

class Router
{

    public static function dispatch()
    {
      $controller = isset(self::$params[0]) ? self::$params[0]: 'DefaultController';
      $action = isset(self::$params[1]) ? self::$params[1]: 'default_method';
      $params = array_slice(self::$params, 2);

      echo call_user_func_array(array($controller, $action), $params);
    }
}
