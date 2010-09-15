<?php

require_once '../fly/core/Loader.php';
use fly\core as C;
use fly\helps as Help;


$p  = dirname(__FILE__).'/../';
C\Loader::setPaths(array($p));
C\Loader::run();


$xml = new Help\SXml('config.xml',null,true);

$xml1 = $xml->xpath('/configuration/default');
$xml2 = $xml->xpath('/configuration/development');
?>

<pre>
<?php print_r($xml1[0]->merge($xml2[0]));?>
</pre>


<?php
echo "<br/><br/>FIN!";
