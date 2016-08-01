<?php
require __DIR__ . '/lib/site.inc.php';
$view = new Steampunked\LoginView($_SESSION, $_GET);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SteamPunked Login</title>
    <link href="steampunked.css" type="text/css" rel="stylesheet" />
</head>
<body>
<?php
echo $view->present_header();
echo $view->present();
echo $view->error_message();
?>

</body>
</html>