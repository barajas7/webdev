<?php
/**
 * Created by PhpStorm.
 * User: Arturo Barajas
 * Date: 2/8/2016
 * Time: 1:13 AM
 */

require 'lib/game.inc.php';
$controller = new Wumpus\WumpusController($wumpus, $_REQUEST);
if($controller->isReset()) {
    unset($_SESSION[WUMPUS_SESSION]);
}

else if($controller->isCheat()) {
    unset($_SESSION[WUMPUS_SESSION]);
    $_SESSION[WUMPUS_SESSION] = new Wumpus\Wumpus(1422668587);
}

header('Location: ' . $controller->getPage());