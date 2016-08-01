<?php
/**
 * Created by PhpStorm.
 * User: Pyromaniac Girl
 * Date: 2/16/2016
 * Time: 11:19 PM
 */

namespace Steampunked;


class GameView
{
    /** Constructor */
    public function __construct(Site $site) {
        $this->site = $site;

        $games = new Games($site);
        $user = $_SESSION[User::SESSION_NAME];

        $this->setGameId($_SESSION['game']);

        $this->game = $games->getCurrentGameState($user, $this->getGameId());
    }

    public function setGame(Game $game)
    {
        $this->game = $game;
    }

    public function setGameId($id)
    {
        $this->game_id = $id;
    }

    /**
     * @return null|Game
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @return null
     */
    public function getGameId()
    {
        return $this->game_id;
    }

    public function present_header() {
        $user = $_SESSION[User::SESSION_NAME];
        $name = $user->getName();

        $html = <<<HTML
<header>
    <nav>
        <form method="post" action="post/game-post.php">
        <p><input type="submit" name="n" value="New Game"></p>
        <input type="hidden" name="id" value="$this->game_id">
        </form>
    </nav>
    <img src = "images/title.png" width="600" height="104" alt="Steampunked" />
</header>
HTML;

        $html .= "<h2>You are playing as $name</h2>";
        return $html;
    }

    // Presents whole game view
    public function present() {
        // If Player 2 has not joined yet
        if ($this->game == null || $this->game->getPlayer2() == null) {
            $html = $this->present_waiting();
        } else {
            $html = $this->present_grid();
            $html .= $this->present_player();
            if($this->game->getWinner() == null) {
                $html .= $this->present_buttons();
            }

        }

        return $html;
    }

    public function present_waiting()
    {
        if($this->game == null) {
            $html = <<<HTML
<br>
<br>
<h2>Your opponent has left the game. Select New Game to start a new one!</h2>
HTML;
        }
        else {
            $html = <<<HTML
<br>
<br>
<h2>Waiting for player to join. Please wait...</h2>
HTML;
        }

        return $html;
    }

    /**
     * Create the HTML for the grid
     * @return string HTML to present
     */

    public function present_grid() {

        $size = $this->game->getSize();
        $width = $size + 2;

        $html = '<form method="post" action="post/game-post.php">';
        $html .= '<div class="game">';

        for ($row = 0; $row < $size; $row++) {

            $html .= '<div class="row">';

            for ($col = 0; $col < $width; $col++) {
                $html .= '<div class="cell">';

                $current_pipe = $this->game->getGrid()[$row][$col];

                if($current_pipe != null) {
                    $current_img = $current_pipe->getImageUrl();

                    if($current_pipe instanceof Leak && $current_pipe->getUserid() == $this->game->getThisPlayer()->getId()) {

                        switch($current_img){
                            case 'images/leak-n.png':
                                $class = 'north';
                                break;
                            case 'images/leak-e.png':
                                $class = 'east';
                                break;
                            case 'images/leak-s.png':
                                $class = 'south';
                                break;
                            case 'images/leak-w.png':
                                $class = 'west';
                                break;
                        }

                        $html .= '<figure class="tint">';
                        $array = array($row, $col);
                        $html .= '<input class='."$class".' type="submit" name="leak" value="'. serialize($array) .'"/>';
                    }

                    else {
                        $html .= '<img src=' . "$current_img" . ' width="50" height="50" />';
                    }

                    if($current_pipe instanceof Leak) {

                        $html .= '</figure>';
                    }
                }

                $html .= '</div>';
            }

            $html .= '</div>';
        }

        $html .= '</div>';

        return $html;
    }

    public function present_player() {

        $users = new Users($this->site);

        if($this->game->getWinner() == null) {
            $user_id = $this->game->getCurrentPlayer();
            $user = $users->get($user_id);
            $name = $user->getName();

            $html = "<p class='player'>$name, it is your turn!</p>";
        }
        else {
            $winner_id = $this->game->getWinner();
            $users = new Users($this->site);
            $user = $users->get($winner_id);
            $name = $user->getName();
            $html = "<p class='player'>$name is the Winner!</p>";
        }

        return $html;
    }

    public function present_buttons() {
        //$html = "";

        $user = $_SESSION[User::SESSION_NAME];
        $pipes = new Pipes($this->site);

        $player_pipes = $this->game->getThisPlayerHand();
        $selected = 5;

        $html = '<p id="radio">';

        // Present each pipe option in current player's list
        for ($i=0; $i <= 4; $i++ ) {
            if ($player_pipes[$i] != null) {
                $img = $player_pipes[$i]->getImageUrl();
            }

            else {
                $img ="";
            }

            // Check radio button of currently selected pipe if one is selected
            if ($i === $selected) {
                $html .= '<input type="radio" name="pipe" value='."$i".' checked><img src=' . "$img" . ' width="50" height="50" />';
            }

            else {
                $html .= '<input type="radio" name="pipe" value='."$i".'><img src=' . "$img" . ' width="50" height="50" />';
            }
        }

        $html .= '</p>';

        // Present submission button options
        $html .= <<<HTML
<p id="buttons">
    <input type="submit" name="r" value="Rotate">
    <input type="submit" name="d" value="Discard">
    <input type="submit" name="o" value="Open Valve">
    <input type="submit" name="g" value="Give Up">
</p>
<input type="hidden" name="id" value="$this->game_id">
</form>
HTML;

        return $html;
    }

    private $site;
    private $game = null;
    private $game_id = null;
}