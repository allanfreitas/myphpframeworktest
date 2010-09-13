<?php
require_once '../core/Loader.php';

$p  = dirname(__FILE__).'/../../';

use fly\core as C;
use fly\tests\cases\mocks as M;


class Loadingg 
{
	function loading ($className)
	{
		$paths = array(
			dirname(__FILE__).'/../../',
			dirname(__FILE__).'/../../'.'fly/tests/mocks/'
		);
		foreach ($paths as $path) {
			$file = $path.str_replace('\\', '/', $className).'.php';
            echo '---'.$file.'--';
			if (file_exists($file) && is_file($file)) {
				include_once $file;
				return true;
			}
		}
	}
}


$l = new Loadingg();

C\Loader::setPaths(array($p,$p.'fly/tests/mocks/'));
//C\Loader::run(array(array($l,'loading')));
C\Loader::run();


class obj1 extends C\Object
{
	
}


$o = new obj1();


C\Loader::load('ObjectMock');

/*
if (class_exists('ObjectMock'))
	echo "existe<br/>";
else
	echo 'no existe<br/>';
*/

$o = new M\ObjectMock();

echo "<br/><br/>";

?>
<pre>
<?php print_r($o);?>
</pre>
<?php

echo "<br/><br/>";
echo "aaaaa4";
