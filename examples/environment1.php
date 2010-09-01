<?php
require_once '../core/Environment.php';


\fly\core\Environment::setDetector(
    function ($param)
    {
        return "production";
    }
);
\fly\core\Environment::setEnv();
?>

<pre>
<?php print_r(\fly\core\Environment::toString());?>
</pre>