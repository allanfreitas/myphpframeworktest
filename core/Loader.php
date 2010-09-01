<?php
/**
 * Loader class
 *
 * PHP version 5.3
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
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
 * Loader class
 *
 * Autoload
 *
 * @category   Core
 * @package    Fly
 * @subpackage Core
 * @author     Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright  2010 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license    http://www.opensource.org/licenses/gpl-2.0.php  GNU General Public License (GPL)
 * @version    Release: @package_version@
 * @link       http://www.mostofreddy.com.ar
 */
class Loader
{
    /**
     * Array con los paths en donde se buscaran los archivos
     * @var array
     */
    static protected $paths = array();
    /**
     * Setea nuevos paths
     *
     * @param array|string $paths paths en donde se buscaran los archivos
     *
     * @return void
     * @static
     */
    static public function setPaths(array $paths)
    {
		if (is_array($paths)) {
    		static::$paths = array_merge(static::$paths, $paths);
		} else {
		    static::$paths[] = $paths;
		}
	}
    /**
     * Invoca a todas las funcionas registradas en el autolad para cargar la clase invocada
     * 
     * @param string $name nombre de la clase o nombre del archivo a cargar
     * 
     * @return void
     * @static
     */
    static protected function call($name)
    {
		spl_autoload_call($name);
	}    
    /**
     * Regisrta una nueva funcion para la implementacion de auntolad
     * 
     * @param callback $callback funcion que es llamada por el autolad para la carga de archivos, puede ser:
     * 							  - un closure
     * 							  - un metodo estatic (NombreClase::nombreFunc)
     * 							  - un metodo de una instancia (array($instancia,'nombreMetodo'))
     * @param bool     $throw    indica si spl_autoload_register genera un error una execpcion
     * @param bool     $prepend  If true, spl_autoload_register() will prepend the autoloader on the autoload stack instead of appending it
     * 
     * @return bool
     * @static
     */
    static public function register($callback, $throw=true, $prepend=false)
    {
		spl_autoload_register($callback, $throw, $prepend);
	}
	/**
	 * Quita una funcion del registro del autolad
	 * 
	 * @param callback $callback funcion que es llamada por el autolad para la carga de archivos
	 * 
	 * @return bool
	 * @static
	 */
	static public function unregister($callback)
	{
		return spl_autoload_unregister($callback);
	}	
	/**
	 * Inicializa el autoload registrando todas las funciones pasadas + la default
	 * 
	 * @param array $functToAutolad array con las funciones a invocar por el autolaod
	 * 								pueden ser un closure, un metodo estatico o un metodo de una instancia
	 * 								array(
	 * 									0 => function ($className) { include_once $className},
	 * 									1 => 'NombreClaseEstatica::nombreMetodoEstatico'
	 * 									2 => array($instancia,'nombreMetodod')
	 * 								)
	 * 
	 * @return void
	 * @static
	 */
	static public function run(array $functToAutolad=array())
	{
		array_unshift($functToAutolad, static::defaultAutoload());
		foreach ($functToAutolad as $func) {
			static::register($func);
		}
	}
	/**
	 * Reaiza un include del archivo invocado
	 * 
	 * @param string $fileName nombre del archivo a incluir
	 * 
	 * @return void
	 * @static
	 */
	static public function load($fileName)
	{
		static::call($fileName);
	}
	/**
	 * Funcion autoload por default
	 * 
	 * @return closure
	 * @static
	 */
	static protected function defaultAutoload()
	{
		$paths = &static::$paths;
		return function($className) use (&$paths)
		{
			foreach ($paths as $path) {
				$file = $path.str_replace('\\', '/', $className).'.php';
				if (file_exists($file) && is_file($file)) {
					include_once $file;
					return true;
				}
			}
		};
	}    
}
