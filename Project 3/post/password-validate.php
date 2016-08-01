<?php
require '../lib/site.inc.php';

$controller = new Steampunked\PasswordValidateController($site, $_POST);
header("location: " . $controller->getRedirect());