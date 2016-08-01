<!DOCTYPE html>
<?php
require 'format.inc.php';
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>You killed the Wumpus</title>
    <link href="wumpus.css" type="text/css" rel="stylesheet" />
</head>
<body>
<?php echo present_header("Stalking the Wumpus"); ?>

<div class="game">
    <figure>
        <img src="dead-wumpus.jpg" width="600" height="325" alt="Dead Wumpus" />
    </figure>

    <div class="return">
        <p>You killed the wumpus</p>
        <p><a href="welcome.php">New Game</a></p>
    </div>
</div>
</body>
</html>