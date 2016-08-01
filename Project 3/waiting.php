<?php
require __DIR__ . '/lib/site.inc.php';
$view = new Steampunked\GameView($site);
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Steampunked Game</title>
    <link href="steampunked.css" type="text/css" rel="stylesheet"/>

</head>
<body>
<?php
echo $view->present_header();
?>
<br>
<br>
<h2>Waiting for player to join. Please wait...</h2>
</body>
</html>