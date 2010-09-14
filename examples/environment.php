<?php
require_once '../fly/core/Loader.php';
use fly\core as C;

$p  = dirname(__FILE__).'/../';
C\Loader::setPaths(array($p));
C\Loader::run();

/*
C\Environment::load('config.xml');
*/

C\Config::load('config.xml');
echo "<br/><br/>FIN!";


