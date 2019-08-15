<?php


namespace App\Http\ResourcePack;

/**
 * 系统全局的注册树
 */
class Register
{

    protected static $objects;

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
