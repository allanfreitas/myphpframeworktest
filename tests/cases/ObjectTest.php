<?php
/**
 * ObjectTest
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
 * @subpackage Tests
 * @author     Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright  2010 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license    http://www.opensource.org/licenses/gpl-2.0.php  GNU General Public License (GPL)
 * @version    SVN: $Id$
 * @link       http://www.mostofreddy.com.ar
 */
namespace fly\tests;
require_once '/home/freddy/public_html/fly/core/Object.php';
require_once '/home/freddy/public_html/fly/tests/cases/mocks/ObjectMock.php';
/**
 * ObjectTest
 * 
 * @category   Tests
 * @package    Fly
 * @subpackage Tests
 * @author     Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright  2010 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license    http://www.opensource.org/licenses/gpl-2.0.php  GNU General Public License (GPL)
 * @version    Release: @package_version@
 * @link       http://www.mostofreddy.com.ar
 */
class ObjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Testea instanciar un objeto
     * 
     * @return void
     */
    public function testInitWhitOutConfig()
    {
        $mock = new \fly\tests\mocks\ObjectMock();
        $this->assertEquals(1, count($mock->getConfig()));
    }
    /**
     * Testea instanciar un objeto pasandole como parametro la configuracion
     * 
     * @return void
     */
    public function testInitWhitConfig()
    {
        $mock = new \fly\tests\mocks\ObjectMock(array('saludo'=>'buen dia'));
        $this->assertEquals('buen dia', $mock->getConfig('saludo'));
    }
    /**
     * Testea  instaciar un objeto pasandole como parametros la configuracion y la configuración de los atributos
     * 
     * @return void
     */
    public function testInitWhitConfigAttr()
    {
        $mock = new \fly\tests\mocks\ObjectMock(array('saludo'=>'buen dia','attr1'=>'atributo1'));
        $this->assertEquals('atributo1', $mock->getAttr1());
    }
}
