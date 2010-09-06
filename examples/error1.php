<?php
require_once '../core/Loader.php';
use fly\core as C;

$p  = dirname(__FILE__).'/../../';
C\Loader::setPaths(array($p));
C\Loader::run();


$code = (E_ERROR | E_USER_WARNING | E_COMPILE_WARNING);
//echo $code;


if ($code & 0) {
    echo "ok";
} else {
    echo "nok";
}

echo "<br/><br/>";

class e
{
    public function err($info)
    {
        print_r($info['origin']);
    }
}

$rr = new e();

$errores = array();
$r = array(
    0 => array(
        'code' => E_ERROR,
        'handler' => function($info) { echo "buuu<br/>"; }
        ),
    1 => array(
        'code' => 0,
        'handler' => function($info) use (&$errores) { echo print_r($info['origin']); }
        ),
    2 => array(
        //'code' => 0,
        'handler' => array($rr,'err')
        )
);


C\Error::setConfig($r);
C\Error::run();


function foo()
{
$r = 9/0;
//throw new Exception('holaaaaaaaaaaaaaaaaaaaaa');
}

class bar
{
    static function foo()
    {
        foo();
    }
}

bar::foo();
//strpos();
file_get_contents(false);


echo "fin";
