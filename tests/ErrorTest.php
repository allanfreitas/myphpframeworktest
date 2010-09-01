<?php
/**
 * ErrorTest
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
use \fly\core\Error;
require_once '/home/freddy/public_html/fly/core/Error.php';
/**
 * ErrorTest
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
 class ErrorTest extends \PHPUnit_Framework_TestCase
{
	public function setUp() {
		Error::run();
		Error::reset();		
	}

	public function tearDown() {
		Error::stop();		
	}
	
	public function testExceptionCatching()
	{
		$e = array();
		Error::setConfig(array(
			array(
				'handler' => function($info) use (&$e) {
					$e = $info;
				}
			)
		));
		
		Error::handle(new \Exception('Testeando handlers'));
		
		$this->assertEquals(2, count($e));
		
		$this->assertEquals('Testeando handlers', $e['origin']['message']);
	}
	
	public function testErrorCatching()
	{
		$e = array();
		Error::setConfig(array(
			array(
				'code' => E_WARNING,
				'handler' => function($info) use (&$e) {
					$e = $info;
				}
			)
		));
		
		@file_get_contents(false);
		
		$this->assertEquals(2, count($e));
		
		$this->assertEquals('file_get_contents(): Filename cannot be empty', $e['origin']['message']);
		
		$r = 5/0;
		
		$this->assertEquals(2, count($e));
		$this->assertEquals('Division by zero', $e['origin']['message']);
	}
}
