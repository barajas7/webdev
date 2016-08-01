<?php
require __DIR__ . '/lib/site.inc.php';
$view = new Steampunked\GameView($site);
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Steampunked Game</title>
    <link href="steampunked.css" type="text/css" rel="stylesheet" />
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <script src="lib/jslib/Game.js"></script>
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
                        //location.reload();
                        new Reload("body");
                    }
                } catch (e) {
                }
            };
        }

        pushInit("garret_game");
    </script>
    <script>
        $(document).ready(function() {
            new Game("form");
        });
    </script>
</head>
<body>
<?php
echo $view->present_header();
echo $view->present();
?>
</body>
</html>