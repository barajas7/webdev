<?php
require '../lib/site.inc.php';

$controller = new Steampunked\LoginController($site, $_SESSION, $_POST);

echo $controller->getRedirect();
header("location: " . $controller->getRedirect());