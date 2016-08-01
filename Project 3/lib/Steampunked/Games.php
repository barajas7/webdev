<?php
/**
 * Created by PhpStorm.
 * User: kayla
 * Date: 4/3/2016
 * Time: 9:09 PM
 */

namespace Steampunked;


class Games extends Table
{
    /**
     * Constructor
     * @param $site Site object
     */
    public function __construct(Site $site) {
        parent::__construct($site, "game");

        $seed = time();
        srand($seed);
    }

    /**
     * Get a game based on the id
     * @param $id int ID of the game
     * @returns Game object if successful, null otherwise.
     */
    public function get($id) {
        $sql =<<<SQL
SELECT * from $this->tableName
where id=?
SQL;

        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);

        $statement->execute(array($id));
        if($statement->rowCount() === 0) {
            return null;
        }

        return new Game($statement->fetch(\PDO::FETCH_ASSOC));
    }

    public function delete($id) {
        $sql = <<<SQL
DELETE from $this->tableName
where id=?
SQL;

        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);

        return $statement->execute(array($id));

    }

    public function getOpenGames() {
        $sql = <<<SQL
SELECT *
from $this->tableName
where player2 is NULL
SQL;
        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);

        $statement->execute();

        $array = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $return_array = array();
        foreach($array as $row) {
            $return_array[] = new Game($row);
        }
        return $return_array;
    }

    public function getOpenGamesForPlayer($userid) {
        $sql = <<<SQL
SELECT *
from $this->tableName
where player2 is NULL and player1 <> ?
SQL;
        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);

        $statement->execute(array($userid));

        $array = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $return_array = array();
        foreach($array as $row) {
            $return_array[] = new Game($row);
        }
        return $return_array;
    }

    /**
     * Create a new game.
     * @return game id on success null on error
     */
    public function newGame(User $player1, $size) {

        // Add a record to the user table
        $sql = <<<SQL
INSERT INTO $this->tableName(player1, currentplayer, size)
values(?, ?, ?)
SQL;

        $statement = $this->pdo()->prepare($sql);

        try {
            if($statement->execute(array($player1->getId(), $player1->getId(), $size)) === false) {
                return null;
            }
        } catch(\PDOException $e) {
            return null;
        }

        return $this->pdo()->lastInsertId();
    }

    /**
     * Set player2 for a game
     */
    public function setPlayer2(User $player2, Game $game) {
        $sql =<<<SQL
UPDATE $this->tableName
SET player2=?
WHERE id=?
SQL;

        $statement = $this->pdo()->prepare($sql);
        try {
            $ret = $statement->execute(array($player2->getId(), $game->getId()));
        } catch(\PDOException $e) {
            return false;
        }

        if(!$ret || $statement->rowCount() === 0) {
            return false;
        }

        return true;
    }

    /**
     * Set player2 for a game
     */
    public function setWinner($userid, Game $game) {
        $sql =<<<SQL
UPDATE $this->tableName
SET winner=?
WHERE id=?
SQL;

        $statement = $this->pdo()->prepare($sql);
        try {
            $ret = $statement->execute(array($userid, $game->getId()));
        } catch(\PDOException $e) {
            return false;
        }

        if(!$ret || $statement->rowCount() === 0) {
            return false;
        }

        return true;
    }

    /**
     * Change turns from player 1->2 or vice versa
     */
    public function changeTurns(Game $game) {
        $currentplayer = $game->getCurrentplayer();
        if ($currentplayer == $game->getPlayer1()) {
            $currentplayer = $game->getPlayer2();
        }
        else {
            $currentplayer = $game->getPlayer1();
        }

        $sql =<<<SQL
UPDATE $this->tableName
SET currentplayer=?
WHERE id=?
SQL;

        $statement = $this->pdo()->prepare($sql);
        try {
            $ret = $statement->execute(array($currentplayer, $game->getId()));
        } catch(\PDOException $e) {
            return false;
        }

        if(!$ret || $statement->rowCount() === 0) {
            return false;
        }

        return true;
    }

    public function getGridForGame(Game &$game) {
        $pipes = new Pipes($this->site);
        $pipe_list = $pipes->getPipesForGame($game);
        $game->createGridfromList($pipe_list);
    }

    public function initializeGridForGame(Game $game) {
        $pipe_list = array();
        $center_line = (int)($game->getSize() / 2);

        //Valves
        $valve_row = array('id' => NULL,
            'gameid' => $game->getId(),
            'userid' => $game->getPlayer1(),
            'row' => $center_line - 3,
            'col' => 0,
            'type' => Pipes::VALVE,
            'orientation' => ValvePipe::CLOSED,
            'handposition' => NULL);
        $pipe_list[] = new ValvePipe($valve_row);


        $valve_row = array('id' => NULL,
            'gameid' => $game->getId(),
            'userid' => $game->getPlayer2(),
            'row' => $center_line + 2,
            'col' => 0,
            'type' => Pipes::VALVE,
            'orientation' => ValvePipe::CLOSED,
            'handposition' => NULL);
        $pipe_list[] = new ValvePipe($valve_row);

        //Leaks
        /*
        $leak_row = array('id' => NULL,
            'gameid' => $game->getId(),
            'userid' => $game->getPlayer1(),
            'row' => $center_line - 3,
            'col' => 1,
            'type' => Pipes::LEAK,
            'orientation' => Leak::WEST,
            'handposition' => NULL);
        $pipe_list[] = new Leak($leak_row);

        $leak_row = array('id' => NULL,
            'gameid' => $game->getId(),
            'userid' => $game->getPlayer2(),
            'row' => $center_line + 2,
            'col' => 1,
            'type' => Pipes::LEAK,
            'orientation' => Leak::WEST,
            'handposition' => NULL);
        $pipe_list[] = new Leak($leak_row);
*/
        //Gauges
        $gauge_row = array('id' => NULL,
            'gameid' => $game->getId(),
            'userid' => $game->getPlayer1(),
            'row' => $center_line - 3,
            'col' => $game->getSize() + 1,
            'type' => Pipes::GAUGE,
            'orientation' => GaugePipe::TOP_ZERO,
            'handposition' => NULL);
        $pipe_list[] = new GaugePipe($gauge_row);

        $gauge_row = array('id' => NULL,
            'gameid' => $game->getId(),
            'userid' => $game->getPlayer1(),
            'row' => $center_line - 2,
            'col' => $game->getSize() + 1,
            'type' => Pipes::GAUGE,
            'orientation' => GaugePipe::ZERO,
            'handposition' => NULL);
        $pipe_list[] = new GaugePipe($gauge_row);

        $gauge_row = array('id' => NULL,
            'gameid' => $game->getId(),
            'userid' => $game->getPlayer2(),
            'row' => $center_line,
            'col' => $game->getSize() + 1,
            'type' => Pipes::GAUGE,
            'orientation' => GaugePipe::TOP_ZERO,
            'handposition' => NULL);
        $pipe_list[] = new GaugePipe($gauge_row);

        $gauge_row = array('id' => NULL,
            'gameid' => $game->getId(),
            'userid' => $game->getPlayer2(),
            'row' => $center_line + 1,
            'col' => $game->getSize() + 1,
            'type' => Pipes::GAUGE,
            'orientation' => GaugePipe::ZERO,
            'handposition' => NULL);
        $pipe_list[] = new GaugePipe($gauge_row);
        $pipes = new Pipes($this->site);

        foreach($pipe_list as $pipe) {
            $result = $pipes->createNewPipe($pipe);
            //var_dump($result);
        }
    }

    public function initializeHandsForGame(Game $game) {
        $pipe_list = array();

        for($i = 0; $i < 5; $i++) {
            $row = array('id' => NULL,
                'gameid' => $game->getId(),
                'userid' => $game->getPlayer1(),
                'row' => NULL,
                'column' => NULL,
                'type' => rand(0, 3),
                'orientation' => 0,
                'handposition' => $i);

            $pipe_list[] = new Pipe($row);

            $row = array('id' => NULL,
                'gameid' => $game->getId(),
                'userid' => $game->getPlayer2(),
                'row' => NULL,
                'col' => NULL,
                'type' => rand(0, 3),
                'orientation' => 0,
                'handposition' => $i);

            $pipe_list[] = new Pipe($row);
        }

        $pipes = new Pipes($this->site);

        foreach($pipe_list as $pipe) {
            $pipes->createNewPipe($pipe);
        }
    }

    public function getCurrentGameState(User $user, $gameid) {
        $game = $this->get($gameid);
        if ($game == null) {
            return null;
        }
        $this->getGridForGame($game);
        $game->setThisPlayer($user);

        $pipes = new Pipes($this->site);
        $game->setThisPlayerHand($pipes->getHandForPlayer($user, $game));

        $users = new Users($this->site);
        $game->setPlayer1Obj($users->get($game->getPlayer1()));
        $game->setPlayer2Obj($users->get($game->getPlayer2()));

        $game->indicateLeaks();

        return $game;
    }


}