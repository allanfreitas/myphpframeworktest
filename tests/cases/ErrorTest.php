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
namespace fly\tests\cases;
use \fly\core\Error;
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
	static public $errors = array();
    
    static public function setUpBeforeClass() {
		Error::run();
        Error::config(array(
			array(
				'handler' => function($info){
					ErrorTest::$errors[] = $info;
				}
			)
		));
	}

	static public function tearDownAfterClass() {
		Error::stop();		
	}
    
    protected function setUp()
    {
        ErrorTest::$errors = array();
    }
    
    public function testIsRunning()
    {
        $this->assertTrue(Error::$isRunning);
    }
    
	public function testExceptionCatching()
	{
		$e = array();
		Error::handle(new \Exception('Testeando handlers'));
		$this->assertEquals('Testeando handlers', ErrorTest::$errors[0]['origin']['message']);
	}
    	
	public function testErrorCatching()
	{
		@file_get_contents(false);
		$this->assertEquals('file_get_contents(): Filename cannot be empty', ErrorTest::$errors[0]['origin']['message']);
	}
    
    public function testCodeName()
	{
		@file_get_contents(false);
        $this->assertEquals('E_WARNING', ErrorTest::$errors[0]['origin']['codeName']);
	}
    
    public function testReset()
    {
        Error::reset();
        $this->assertEquals(0,count(Error::config()));
    }
    
    public function testStop()
    {
        Error::stop();
        $this->assertFalse(Error::$isRunning);
    }
}
