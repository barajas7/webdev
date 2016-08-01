<?php
require '../lib/site.inc.php';

unset($_SESSION['user']);
$root = $site->getRoot();
header("location: $root");