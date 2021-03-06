<?php
/**
 * Error class
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
 * Error class
 *
 * Manejador de errores y exepciones.
 * Setea los handles para manejar de igual forma los errores y los handlers, para ello transforma los errores en exepciones del tipo
 * ErrorException
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
class Error
{
    /**
     * Indica si se esta capturando los errores o no
     * @var bool
     */
    static public $isRunning = false;
    
    static protected $codeError = array(
        1 => 'E_ERROR',
        2 => 'E_WARNING',
        4 => 'E_PARSE',
        8 => 'E_NOTICE',
        16 => 'vCORE_ERROR',
        32 => 'E_CORE_WARNING',
        64 => 'E_COMPILE_ERROR ',
        128 => 'E_COMPILE_WARNING',
        256 => 'E_USER_ERROR',
        512 => 'E_USER_WARNING ',
        1024 => 'E_USER_NOTICE',
        2048 => 'E_STRICT',
        4096 => 'E_RECOVERABLE_ERROR',
        8192 => 'E_DEPRECATED',
        16384 => 'E_USER_DEPRECATED',
        30719 => 'E_ALL'
    );
    /**
     * Configuracion de los handlers
     * @var array
     */
    static protected $config = array();
    /**
     * Configura como se actuara al capturar un error/exepcion
     *
     * @param array $config array donde code indica el nivel de error para el cual se setea el handle,
     *                      sino se define code entonces es para para todos los niveles de error
     *                      array(
     *                          0 => array(
     *                              'code'=> (E_ERROR | E_USER_WARNING | E_COMPILE_WARNING) //opcional
     *                              'handler' => function ($info) { //..} //accion a realizar
     *                          )
     *                      )
     *
     * @return void
     * @static
     */
    static public function config($config = null)
    {
        if (!is_null($config)) {
            static::$config = array_merge((array) $config, static::$config);
        }
        return static::$config;
    }
    /**
     * Inicializa los handler
     *
     * @return void
     * @static
     */
    static public function run()
    {
        $self = get_called_class();

        set_exception_handler(
            function(\Exception $exception) use ($self)
            {
                $self::handle($exception);
            }
        );

        set_error_handler(
            function($code, $message, $file, $line = 0, $context = null) use ($self)
            {
                $self::handle(new \ErrorException($message, $code, $code, $file, $line));
            }
        );
        static::$isRunning = true;
    }
    /**
     * Lógica para tratar los errores.
     *
     * @param \Exception $exception excepcion generada
     *
     * @return void
     * @static
     */
    static public function handle(\Exception $exception)
    {
        $info = array(
            'trace' => static::getTrace($exception->getTrace()), //trace del error
            'origin' => static::getOrigen($exception)            //datos de la linea donde se genero el error
        );
        //recorre todas los handler definidos para tratar la excepcion
        foreach (static::$config as $conf) {
            if (is_array($conf) && isset($conf['handler'])) {
                if (static::validCodeHandle($conf, $info['origin']['code'])) {
                    static::invokeHandler($conf['handler'], $info);
                }
            }
        }
        return true;
    }
    /**
     * Valida si el codigo de validacion del handler es = que el del error producido, si es asi, entonces invoca al handler
     *
     * @param int $codeHandler Codigo de error para el cual el handler se tiene que invocar
     * @param int $codeError   Codigo de error producido
     *
     * @return bool
     * @static
     */
    static protected function validCodeHandle(&$codeHandler, $codeError)
    {
        return (!isset($codeHandler['code']) || (isset($codeHandler['code']) && $codeHandler['code'] & $codeError));
    }
    /**
     * Invoca a un handler
     *
     * @param mixed &$handler handler a invocar para que procese el error
     * @param array &$info    array con la información del error producido
     *
     * @return void
     * @static
     */
    static protected function invokeHandler(&$handler, &$info)
    {
        if (is_array($handler) && is_callable($handler)) {
            $handler[0]->$handler[1]($info);
        } elseif (is_callable($handler)) {
            $handler($info);
        }
    }
    /**
     * Recupera y devuelve los datos originales de donde se genero el error
     *
     * @param \Exception $exception excepcion generada
     *
     * @return array array(
     *                  'message' => $exception->getMessage(),
     *                  'code' => $exception->getCode(),
     *                  'file' => $exception->getFile(),
     *                  'line' => $exception->getLine()
     *               );
     * @static
     */
    static protected function getOrigen(\Exception $exception)
    {
        return array(
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'codeName' => static::getCodeName($exception->getCode()),
            'file' => $exception->getFile(),
            'line' => $exception->getLine()
        );
    }
    
    static protected function getCodeName($code)
    {
        return isset(static::$codeError[$code])?static::$codeError[$code]:'n/a';
    }
    /**
     * Devuelve el trace del error
     *
     * @param array $trace trace del error (\Exception::getTrace())
     *
     * @return array
     * @static
     */
    static protected function getTrace(array $trace)
    {
        return array_map(
            function ($frame)
            {
                if (isset($frame['function'])) {
                    if (isset($frame['class'])) {
                            $frame['function'] = trim($frame['class'], '\\') . '::' . $frame['function'];
                            unset($frame['class']);
                            unset($frame['type']);
                    }
                        return $frame;
                }
            },
            $trace
        );
    }
    /**
     * Restituye los handler de errr y excepcion originales
     *
     * @return void
     * @static
     */
    static public function stop()
    {
        restore_error_handler();
        restore_exception_handler();
        static::$isRunning = false;
    }
    /**
     * Resetela configuracion del handler
     *
     * @return void
     * @static
     */
    static public function reset()
    {
        static::$config = array();
    }
}
