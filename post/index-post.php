<?php
require '../lib/site.inc.php';
$controller = new Steampunked\StartController($site, $_SESSION, $_POST);

//phpinfo();

header("location: " . $controller->getPage());
exit;