<?php
/**
 * Created by PhpStorm.
 * User: Pyromaniac Girl
 * Date: 2/16/2016
 * Time: 11:19 PM
 */

namespace Steampunked;


class GameController
{

    /**
     * Constructor
     * @param Site $site Site object
     * @param $post $_POST array
     */
    public function __construct(Site $site, $post)
    {
        $this->site = $site;

        if(isset($post['id'])) {
            $game_id = $post['id'];
        }
        else  {
            return;
        }

        $this->user = $_SESSION[User::SESSION_NAME];

        $games = new Games($this->site);
        $game = $games->getCurrentGameState($this->user, $game_id);
        $this->setGame($game);

        if(isset($post['n'])) {
            $this->newGame();
            unset($_SESSION['game']);
            return;
        }

        $this->page = "../game.php?id=$game_id";

        if($game == null) {
            return;
        }
        // If Player is not current player, exit
        if ($this->user->getId() != $game->getCurrentplayer()) {
            return;
        }
        elseif(isset($post['o'])) {
            $this->openValve();
            //open valve
        }

        elseif(isset($post['g'])) {
            $this->giveUp();
            //give up
        }
        // If button is selected on game page
        elseif(isset($post['pipe'])) {
            $index = $post['pipe'];
            //$this->game->getCurrentPlayer()->setCurrentPipe($index);

            if(isset($post['r'])) {
                $this->rotatePipe($index);
                //rotate pipe
            }

            elseif(isset($post['d'])) {
                $this->discardPipe($index);
                //discard pipe
            }

            elseif(isset($post['leak'])) {
                $location = unserialize($post['leak']);

                $this->addPipe($index, $location[0], $location[1]);
            }
        }
    }

    /**
     * @param mixed $game
     */
    public function setGame($game)
    {
        $this->game = $game;
    }

    public function getPage() {
        return $this->page;
    }

    public function rotatePipe($index) {
        $pipes = new Pipes($this->site);
        $hand = $this->game->getThisPlayerHand();
        $pipe = $hand[$index];
        $pipe->rotate();
        $pipes->updatePipe($pipe);
        $this->reload = true;
        return;
    }

    public function discardPipe($index) {
        $pipes = new Pipes($this->site);
        $hand = $this->game->getThisPlayerHand();
        $pipe = $hand[$index];
        $pipes->discardPipe($pipe);
        $pipes->delete($pipe->getId());

        $games = new Games($this->site);
        $games->changeTurns($this->game);
        $this->pushReload();
        return;
    }

    public function openValve() {
        $this_is_player1 = $this->game->getThisPlayer()->getId() == $this->game->getPlayer1();

        //Get starting pipe
        $center_line = (int)($this->game->getSize() / 2);
        if($this_is_player1) {
            $valve_row = $center_line - 3;
        }
        else {
            $valve_row = $center_line + 2;
        }
        $valve = $this->game->getGrid()[$valve_row][0];

        //open valve
        $pipes = new Pipes($this->site);
        $valve->rotate();
        $pipes->updatePipe($valve);

        $games = new Games($this->site);


        if($this->game->indicateLeaks()) {
            if($this_is_player1) {
                $games->setWinner($this->game->getPlayer2(), $this->game);
            }
            else {
                $games->setWinner($this->game->getPlayer1(), $this->game);
            }
        }
        else {
            if($this_is_player1) {
                $gauge_top_row = $center_line - 3;
                $gauge_bottom_row = $center_line - 2;
                $games->setWinner($this->game->getPlayer1(), $this->game);
            }
            else {
                $gauge_top_row = $center_line;
                $gauge_bottom_row = $center_line + 1;
                $games->setWinner($this->game->getPlayer2(), $this->game);
            }
            $gauge_top = $this->game->getGrid()[$gauge_top_row][$this->game->getSize() + 1];
            $gauge_bottom = $this->game->getGrid()[$gauge_bottom_row][$this->game->getSize() + 1];

            $gauge_top->rotate();
            $gauge_bottom->rotate();

            $pipes->updatePipe($gauge_top);
            $pipes->updatePipe($gauge_bottom);
        }

        $this->pushReload();

        return;
    }

    public function giveUp() {
        $this_is_player1 = $this->game->getThisPlayer()->getId() == $this->game->getPlayer1();
        $games = new Games($this->site);

        if($this_is_player1) {
            $games->setWinner($this->game->getPlayer2(), $this->game);
        }
        else {
            $games->setWinner($this->game->getPlayer1(), $this->game);
        }

        $this->pushReload();

        return;
    }

    public function newGame(){
        if($this->game != null) {
            $games = new Games($this->site);
            $games->delete($this->game->getId());
        }

        //if($this->user->getEmail() == null) {
            $this->reset = true;
        //}

        $this->page = '../index.php';
        return;
    }

    public function isReset() {
        return $this->reset;
    }

    public function isReload() {
        return $this->reload;
    }



    public function addPipe($index, $location_row, $location_col) {
        if($this->game->isValid($index, $location_row, $location_col)) {
            $pipes = new Pipes($this->site);
            $hand = $this->game->getThisPlayerHand();
            $pipe = $hand[$index];
            $pipe->setRow($location_row);
            $pipe->setCol($location_col);

            $pipes->updatePipe($pipe);
            //discard pipe
            $pipes->discardPipe($pipe);

            //swap turns
            $games = new Games($this->site);
            $games->changeTurns($this->game);
            $this->pushReload();
        }

        return;
    }

    public function pushReload() {
        /*
         * PHP code to cause a push on a remote client.
         */
        $msg = json_encode(array('key' => 'garret_game', 'cmd' => 'reload'));

        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        $sock_data = socket_connect($socket, '127.0.0.1', 8078);
        if (!$sock_data) {
            echo "Failed to connect";
        } else {
            socket_write($socket, $msg, strlen($msg));
        }
        socket_close($socket);
    }

    private $reload = false;
    private $page = '../game.php?';     // The next page we will go to
    private $reset = false;         // True if we need to reset the game
    private $site;
    private $game;
    private $user;
}