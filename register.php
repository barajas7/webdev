<?php
require __DIR__ . '/lib/site.inc.php';
$view = new Steampunked\RegisterView($site, $_GET);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SteamPunked Register</title>
    <link href="steampunked.css" type="text/css" rel="stylesheet" />
</head>
<body>
<?php
echo $view->present_header();
echo $view->present();
?>
</body>
</html>