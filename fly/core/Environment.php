<?php
namespace fly\core;
class Environment
{
    protected static $current = null;
    
    protected static $detector = null;
    
    public static function reset()
    {
        static::$detector = null;
        static::$current = null;
    }
    
    public static function is($env)
    {
        return static::$current === $env;
    }
    
    public static function get($params = null)
    {
        if (is_null(static::$current)) {
            static::$current = static::detect()->__invoke($params);
        }
        return static::$current;
    }
    
    public static function set($data)
    {
        if (is_callable($data)) {
            return static::$detector = $data;
        }
        static::$current = (string)$data;
    }
    
    public static function detect()
    {
        return static::$detector?:function($params) {
            return 'development';
        };
    }
}
