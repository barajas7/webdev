<?php
/**
 * Created by PhpStorm.
 * User: Pyromaniac Girl
 * Date: 2/16/2016
 * Time: 11:18 PM
 */

namespace Steampunked;


class StartView
{
    /** Constructor
     * @param $game Game object */
    public function __construct(Site $site) {
        $this->site = $site;
    }

    public function present() {

        $html = <<<HTML
<p>Welcome. Please select a game to join or select grid size and create a game.</p>
<form method="post" action="post/index-post.php">
<h2>Join a game:</h2>
<table>
    <tr>
        <th>&nbsp;</th>
        <th>Game ID</th>
        <th>Creator</th>
        <th>Size</th>
    </tr>
HTML;

        $games = new Games($this->site);
        $users = new Users($this->site);

        if(isset($_SESSION[User::SESSION_NAME])) {
            $current_user = $_SESSION[User::SESSION_NAME];
            $all = $games->getOpenGamesForPlayer($current_user->getId());
        }
        else {
            $all = $games->getOpenGames();
        }

        foreach ($all as $game) {
            $id = $game->getId();

            // Get player name
            $player_id = $game->getPlayer1();
            $user = $users->get($player_id);
            /// This is where the error is Art
            $name = $user->getName();

            $size = $game->getSize();

            $html .= <<<HTML
    <tr>
        <td><input type="radio" name="game" value="$id"></td>
        <td>$id</td>
        <td>$name</td>
        <td>$size</td>
    </tr>
HTML;
        }
        $html .= <<<HTML
</table>
<br>
<br>
<input type="submit" name="join" id="join" value="Join Game">
<br>
<br>
<hr>
<h2>Create your own game:</h2>
<p>
<label for="size">Grid Size:</label>
<select name="size" id="size">
    <option value="6">6 by 6</option>
    <option value="10">10 by 10</option>
    <option value="20">20 by 20</option>
</select>
</p>
<p><input type="submit" name="create" value="Create Game"></p>
</form>
HTML;
        return $html;
    }

    private $site;
}