<?php


namespace App\Http\Library;

/**
 * 系统全局的注册树
 */
class Register
{

    const PRODUCT_INFO_KEY = 'product';

    public static $objects;

    public static function set($alias, $object)
    {
        self::$objects[$alias] = $object;
    }


    public static function get($alias)
    {
        return self::$objects[$alias];
    }

    public function _unset($alias)
    {
        unset(self::$objects[$alias]);
    }
}
