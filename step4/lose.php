<?php
require 'format.inc.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>The Wumpus Killed You</title>
    <link href="wumpus.css" type="text/css" rel="stylesheet" />
</head>
<body>
<?php echo present_header("Stalking the Wumpus"); ?>

<div class="game">
    <figure>
        <img src="wumpus-wins.jpg" width="600" height="325" alt="Wumpus eating brain" />
    </figure>

    <div class="return">
        <p>You died and the Wumpus ate your brain!</p>
        <p><a href="welcome.php">New Game</a></p>
    </div>
</div>
</body>
</html>