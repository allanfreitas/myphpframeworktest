<?php
/**
 * Object
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
 * Object
 * 
 * Clase base para todos los objetos del framework.
 * Brinda un constructor general que permite configurar al objeto en el momento de la instanciación, logrando que los objetos se adapten
 * (programación adaptativa) al entorno en el cual se instancian 
 *  
 * @category   Core
 * @package    Fly
 * @subpackage Core
 * @author     Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright  2010 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license    http://www.opensource.org/licenses/gpl-2.0.php  GNU General Public License (GPL)
 * @version    Release: @package_version@
 * @link       http://www.mostofreddy.com.ar
 * @abstract
 */
abstract class Object
{
    /**
     * Almacena la configuración del objeto en tiempo de ejecucion
     * @var array
     */
    protected $config = array();
    /**
     * Contiene los nombres de los atributos que machearan con la configuracion al instanciar el objeto. Cada elemento de este array
     * debera ser el nombre de un atributo de la clase. Tambien puede ser que la clave del array sea el nombre del atributo y tenga como 
     * valor 'mergeAll' que indica que los valores pasados en la configuracion no sustituyen a los valores por default sino que hace un 
     * merge con todos 
     * @var array
     */
    protected $configAttr = array();
    /**
     * Constructor generico.
     * 
     * Se el pasa un array con la configuracion del objeto, este será almacenado en Object::$config y si alguna clave machea 
     * con algún elemento de Object::$configAttr entonces su valor sera asignado al atributo con el mismo nombre
     * 
     * @param array $config array asositivo con la configuración del objeto.
     *                      - init _boolean: Indica si se llama a init().
     *  
     * @return void
     */
    public function __construct(array $config = array())
    {
        $defaults = array('init' => true);
        $this->config = $config + $defaults;
        if ($this->config['init']) {
            $this->init();
        }
    }
    /**
     * Inicializa al objeto.
     * 
     * Itera sobre configAttr para asignar los valores de configuracion a los atributos correspondientes
     * 
     * @return void
     * @final
     * @see Object::$configAttr
     */
    final protected function init()
    {   
        foreach ($this->configAttr as $k => $v) {
            if (!isset($this->config[$k]) && !isset($this->config[$v])) {
                continue;
            }
            if ($v === 'mergeAll') {
                $this->$k = $this->config[$k] + $this->$k;
                unset($this->config[$k]);
            } else {
                $this->$v = $this->config[$v];
                unset($this->config[$v]);
            }
        }
    }
}
