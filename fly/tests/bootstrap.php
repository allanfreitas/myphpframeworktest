<?php
include '../core/Loader.php';
use fly\core as C;

$p  = dirname(__FILE__).'/../../';

C\Loader::setPaths(array($p));
C\Loader::run();
