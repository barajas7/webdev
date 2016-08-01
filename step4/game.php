<?php
require 'format.inc.php';
require 'lib/game.inc.php';
$view = new Wumpus\WumpusView($wumpus);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stalking the Wumpus</title>
    <link href="wumpus.css" type="text/css" rel="stylesheet" />
</head>
<body>
<?php echo present_header("Stalking the Wumpus"); ?>

<div class="game">
    <figure>
        <img src="cave.jpg" width="600" height="325" alt="Cave" />
    </figure>

    <div id="scene">
        <?php echo $view->presentStatus(); ?>
    </div>

    <div class="rooms">
        <?php
        echo $view->presentRoom(0);
        echo $view->presentRoom(1);
        echo $view->presentRoom(2);
        ?>
    </div>

    <footer>
        <?php echo $view->presentArrows(); ?>
    </footer>
</div>

</body>
</html>