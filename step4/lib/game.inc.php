<?php
require __DIR__ . "/../vendor/autoload.php";

/**
 * Created by PhpStorm.
 * User: Arturo Barajas
 * Date: 2/7/2016
 * Time: 6:06 PM
 */

// Start the PHP session system
session_start();

define("WUMPUS_SESSION", 'wumpus');

// If there is a Wumpus session, use that. Otherwise, create one
if(!isset($_SESSION[WUMPUS_SESSION])) {
    $_SESSION[WUMPUS_SESSION] = new Wumpus\Wumpus();   // Cheat Seed: 1422668587
}

$wumpus = $_SESSION[WUMPUS_SESSION];