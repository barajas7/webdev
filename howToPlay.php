<?php
require __DIR__ . '/lib/site.inc.php';
$instruction_view = new Steampunked\InstructionView();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SteamPunked</title>
    <link href="steampunked.css" type="text/css" rel="stylesheet" />
</head>
<body>
<?php
echo $instruction_view->present_header();
echo $instruction_view->present();
?>

</body>
</html>