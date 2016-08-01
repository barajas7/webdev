<?php
require '../lib/site.inc.php';

$controller = new Steampunked\RegisterController($site, $_POST);
header("location: " . $controller->getRedirect());