<?php
/**
 * Created by PhpStorm.
 * User: Pyromaniac Girl
 * Date: 2/16/2016
 * Time: 11:19 PM
 */

namespace Steampunked;


class StartController
{

    /**
     * Constructor
     * @param Site $site
     * @param $post $_POST array
     * @param &$session $_SESSION array reference
     */
    public function __construct(Site $site, array &$session, $post)
    {

        // If the player is creating a game
        if (isset($post['create'])) {

            $games = new Games($site);
            $users = new Users($site);

            // If player 1 set
            if (isset($_SESSION[User::SESSION_NAME])) {
                // Add code here to add user to game if logged in

                $id = $games->newGame($_SESSION[User::SESSION_NAME], $post['size']);
            } else {
                // Else Create Guest User

                // Add guest to users Table
                $user_id = $users->addGuest('1st Player Guest');

                // Add guest to session
                $player1 = $users->get($user_id);
                $session[User::SESSION_NAME] = $player1;

                $id = $games->newGame($player1, $post['size']);
            }

            $session['game'] = $id;
            $this->setGame($id);

            /*
            * PHP code to cause a push on a remote client.
            */
            $msg = json_encode(array('key' => 'garret_index', 'cmd' => 'reload'));

            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

            $sock_data = socket_connect($socket, '127.0.0.1', 8078);
            if (!$sock_data) {
                echo "Failed to connect";
            } else {
                socket_write($socket, $msg, strlen($msg));
            }
            socket_close($socket);

        } // Else if the player is joining a game
        else if (isset($post['join'])) {

            if (!isset($post['game'])) {
                return;
            }

            $id = $post['game'];

            $games = new Games($site);
            $users = new Users($site);
            $game = $games->get($id);
            if($game == null) {
                $this->pushReload();
                return;
            }

            if (isset($_SESSION[User::SESSION_NAME])) {
                // Add code here to add user to game if logged in

                $player2 = $_SESSION[User::SESSION_NAME];
            } else {
                // Else Create Guest User

                // Add guest to users Table
                $user_id = $users->addGuest('2nd Player Guest');

                // Add guest to session
                $player2 = $users->get($user_id);
                $session[User::SESSION_NAME] = $player2;
            }

            $games->setPlayer2($player2, $game);
            $game->setPlayer2($player2->getId());
            $games->initializeGridForGame($game);
            $games->initializeHandsForGame($game);
            $this->setGame($id);

            $session['game'] = $id;

            /*
            * PHP code to cause a push on a remote client.
            */
            $this->pushReload();
        }
    }

    public function getPage() {
        return $this->page;
    }

    public function setGame($id)
    {
        $this->page = "../game.php?id=$id";
        return;
    }

    public function isReset(){
        return $this->reset;
    }

    private $page = '../index.php';     // The next page we will go to
    private $reset = false;

    public function pushReload()
    {
        $msg = json_encode(array('key' => 'garret_game', 'cmd' => 'reload'));

        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        $sock_data = socket_connect($socket, '127.0.0.1', 8078);
        if (!$sock_data) {
            echo "Failed to connect";
        } else {
            socket_write($socket, $msg, strlen($msg));
        }
        socket_close($socket);
    }         // True if we need to reset the game
}