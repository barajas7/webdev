<?php
require __DIR__ . '/lib/site.inc.php';
$start_view = new Steampunked\StartView($site);
if (isset($_SESSION['game'])) {
    header("location: game.php?=" . $_SESSION['game']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SteamPunked</title>
    <link href="steampunked.css" type="text/css" rel="stylesheet" />
    <script>
        /**
         * Initialize monitoring for a server push command.
         * @param key Key we will receive.
         */
        function pushInit(key) {
            var conn = new WebSocket('ws://webdev.cse.msu.edu:8079');
            conn.onopen = function (e) {
                console.log("Connection to push established!");
                conn.send(key);
            };

            conn.onmessage = function (e) {
                try {
                    var msg = JSON.parse(e.data);
                    if (msg.cmd === "reload") {
                        location.reload();
                    }
                } catch (e) {
                }
            };
        }

        pushInit("garret_index");
    </script>
</head>
<body>
<header>

    <?php
        $no_user_html = <<<HTML
    <nav>
        <p><a href="howToPlay.php">How To Play</a> | <a href="register.php">Register</a> | <a href="login.php">Login</a></p>
    </nav>
HTML;
        $user_html = <<<HTML
    <nav>
        <p><a href="howToPlay.php">How To Play</a> | <a href="post/logout.php">Log Out</a></p>
    </nav>
HTML;
        if(is_null($user)){
            echo $no_user_html;
        } else {
            echo $user_html;
        }
    ?>

    <img src = "images/title.png" width="600" height="104" alt="Steampunked" />
</header>

<?php
echo $start_view->present();
?>

</body>
</html>