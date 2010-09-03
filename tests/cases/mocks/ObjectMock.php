<?php
/**
 * ObjectMock
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
 * @category   Tests
 * @package    Fly
 * @subpackage Tests/Mocks
 * @author     Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright  2010 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license    http://www.opensource.org/licenses/gpl-2.0.php  GNU General Public License (GPL)
 * @version    SVN: $Id$
 * @link       http://www.mostofreddy.com.ar
 */
namespace fly\tests\mocks;
/**
 * ObjectMock
 * 
 * @category   Tests
 * @package    Fly
 * @subpackage Tests/Mocks
 * @author     Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright  2010 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license    http://www.opensource.org/licenses/gpl-2.0.php  GNU General Public License (GPL)
 * @version    Release: @package_version@
 * @link       http://www.mostofreddy.com.ar
 * @abstract
 */
class ObjectMock extends \fly\core\Object
{
    protected $attr1;
    protected $configAttr = array('attr1');
    /**
     * Devuelve un valor de configuracion
     * 
     * @param mixed $nameConfig puede tomar varios valores
     *                          - null: devuelve el array de condfiguracion entero
     *                          - string: indica el nombre de una clave de configuración
     *                          
     * @return mixed
     */
    public function getConfig($nameConfig = null)
    {
        if (is_null($nameConfig)) {
            return $this->config;
        } elseif (isset($this->config[$nameConfig])) {
            return $this->config[$nameConfig];
        } else {
            return null;
        }
    }
    /**
     * Getter attr1
     * 
     * @return mixed
     */
    public function getAttr1()
    {
        return $this->attr1;
    }
}
