<?php

require '../lib/site.inc.php';
//phpinfo();
$controller = new Steampunked\GameController($site, $_POST);

if($controller->isReset()) {
    unset($_SESSION['user']);
    header("location: " . $controller->getPage());
}

//header("location: " . $controller->getPage());

if($controller->isReload()) {
    $result = json_encode(array('ok' => true, 'reload' => true));
}
else {
    $result = json_encode(array('ok' => true));
}
echo $result;
exit;