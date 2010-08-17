<?php
/**
 * Environment
 *  
 * PHP version 5.3
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @category   Core
 * @package    Fly
 * @subpackage Core
 * @author     Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright  2010 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license    http://www.opensource.org/licenses/gpl-2.0.php  GNU General Public License (GPL)
 * @version    SVN: $Id$
 * @link       http://www.mostofreddy.com.ar
 */
namespace fly\core;
/**
 * Environment
 *  
 * Almacena la configuración para los distintos ambientes del proyecto
 * 
 * @category   Core
 * @package    Fly
 * @subpackage Core
 * @author     Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright  2010 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license    http://www.opensource.org/licenses/gpl-2.0.php  GNU General Public License (GPL)
 * @version    Release: @package_version@
 * @link       http://www.mostofreddy.com.ar
 * @static
 */
class Environment
{
    static protected $configs = array();
    
    static protected $currentEnvironmet = '';
    
    static protected $detector = null;
    /**
     * Resetea la configuracion de todos los ambientes
     * 
     *  @return void
     */
    static public function reset()
    {
        static::$configs = array();
        static::$currentEnvironmet = '';
        static::$detector = null;
    }
    /**
     * Devuelve datos de los ambients
     * 
     * @param string $name puede ser
     *                      - nombre de un ambiente: entonces devuelve toda la configuracion de ese ambiente
     *                      - nombre de un valor de la config de un ambiente: devuelve una config particular de un ambiente
     * 
     * @return mixed
     */
    static public function gets($name = null)
    {
        //devuelte todas las configuraciones de todos los ambientes
        if (is_null($name)) {
            return static::$configs;
        }
        //devuelve las configuraciones del ambiente $name
        if (isset(static::$configs[$name])) {
            return static::$configs[$name];
        }
        //devuelve $name de la configuracion del ambiente actual
        if (isset(static::$configs[static::$currentEnvironmet])
            || isset(static::$configs[static::$currentEnvironmet][$name])
        ) {
            return static::$configs[static::$currentEnvironmet][$name];
        }
        
        return null;
    }
    /**
     * Setea la configuracion de un ambiente
     * Cada vez que se llama a este metodo se hace un merge con la configuracion existe y la nueva
     * 
     * @param string $env    nombre del ambiente
     * @param array  $config array con los valores del ambiente
     * 
     * @return void
     */
    static public function set($env, $config = null)
    {
        if (is_null($config) || !is_array($config)) {
            return null;
        }
        if (!isset(static::$configs[$env])) {
            static::$configs[$env] = array();
        }
        static::$configs[$env] = array_merge(static::$configs[$env], $config);
    }
    /**
     * Setea el ambiente actual
     * 
     * @param string $env nombre del ambiente acutal. Si es null entonces se llama al mentodo Enviroment::detect()
     * 
     * @return void
     */
    static public function setEnv($env = null)
    {
        if (is_null($env) || !is_string($env)) {
            static::$currentEnvironmet = static::detector()->__invoke($env);
        } else {
            static::$currentEnvironmet = $env;
        }
        if (!isset(static::$configs[static::$currentEnvironmet])) {
            static::$configs[static::$currentEnvironmet] = array();
        }
    }
    /**
     * Detecta el ambiente actual
     * 
     * @return string
     */
    static public function detector()
    {
        return static::$detector?:function ($env) {
            return 'development';
        };
    }
    /**
     * Setea la funcion para detectar el ambiente en que se encuentra corriendo el proyecto.
     * 
     * @param closure $closure funcion lambda para detectar el ambiente
     * 
     * @return void
     */
    static public function setDetector($closure = null)
    {
        if (is_callable($closure)) {
            static::$detector = $closure;
        }
    }
    /**
     * Valida si la configuracion del ambiente corresponde con el que se paso como parametro
     * 
     * @param string $env nombre del ambiente
     * 
     * @return bool
     */
    static public function isEnv($env)
    {
        return (static::$currentEnvironmet === $env);
    }
    /**
     * toString
     * 
     * @return string
     */
    static public function toString()
    {
        return json_encode(
            array(
                'configs' => static::$configs,
                'currentEnvironmet' => static::$currentEnvironmet
            )
        );
    }
}