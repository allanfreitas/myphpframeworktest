<?php
namespace fly\core;
use fly\helps as Help;
class Config
{
    protected static $config = array();
    
    public static function reset()
    {
        static::$config = array();
    }
    
    public static function get($name = null)
    {
        if (is_null($name)) {
            return static::$config;
        }
        return isset(static::$config[$name])?:null;
    }
    
    public static function set($name, $value)
    {
        static::$config[$name] = $value;
    }
        
    protected static function getConfig($simplexml_from)
    {
        $r = array();
        foreach ($simplexml_from->children() as $simplexml_child) {
            $v = trim((string)$simplexml_child);
            if ($v == '') {
                $r[$simplexml_child->getName()] =  static::getConfig($simplexml_child);
            } else {
                $r[$simplexml_child->getName()] = trim((string)$simplexml_child);
            }
        }
        return $r;
    }
        
    public static function load($file)
    {
        $xml = simplexml_load_file($file);
        //config por default para todos los ambientes
        $default = $xml->xpath('/configuration/default');
        //config para el ambiente acutal
        $env = $xml->xpath('/configuration/'.Environment::get());
        //realizo un merge donde los datos del ambiente no son sobreescritos x los default
        Help\SXml::plus($env[0], $default[0]);
        static::$config = static::getConfig($env[0]);
    }
}
