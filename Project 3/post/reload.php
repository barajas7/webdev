<?php
require '../lib/site.inc.php';

$controller = new Steampunked\ReloadController($site);
echo $controller->getResult();