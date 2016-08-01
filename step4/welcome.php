<?php
require 'format.inc.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Stalking the Wumpus</title>
    <link href="wumpus.css" type="text/css" rel="stylesheet" />
</head>
<body>
<?php echo present_header("Stalking the Wumpus"); ?>

<div class="game">
    <figure>
        <img src="cave-evil-cat.png" width="600" height="325" alt="Evil Wumpus" />
    </figure>

    <div class="return">
        <p>Welcome to <span>Stalking the Wumpus</span></p>
        <p><a href="instructions.php">Instructions</a></p>
        <p><a href="game-post.php?n">Start Game</a></p>
    </div>
</div>
</body>
</html>