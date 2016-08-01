<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 3/27/16
 * Time: 4:45 PM
 */

namespace Steampunked;


class Pipes extends Table
{
    /**
     * Constructors
     * @param $site The Site object
     */
    public function __construct(Site $site) {
        parent::__construct($site, "pipe");

        $seed = time();
        srand($seed);
    }

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

        return $this->getPipeFromRow($statement->fetch(\PDO::FETCH_ASSOC));
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


    public function getPipesForGame(Game $game) {
        $sql = <<<SQL
SELECT *
from $this->tableName
where gameid=? and row is not null and col is not null
SQL;
        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);

        $statement->execute(array($game->getId()));

        $array = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $return_array = array();
        foreach($array as $row) {
            $return_array[] = $this->getPipeFromRow($row);
        }
        return $return_array;
    }

    public function getHandForPlayer(User $user, Game $game) {
        $sql = <<<SQL
SELECT *
from $this->tableName
where gameid=? and userid=? and row is null and col is null
order by handposition
SQL;
        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);

        $statement->execute(array($game->getId(), $user->getId()));

        $array = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $return_array = array();
        foreach($array as $row) {
            $index = $row['handposition'];
            $return_array[$index] = $this->getPipeFromRow($row);
        }
        return $return_array;
    }

    public function updatePipe(Pipe $pipe) {
        $sql =<<<SQL
UPDATE $this->tableName
SET row=?, col=?, orientation=?, handposition=?
where id=?
SQL;

        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);

        try {
            $ret = $statement->execute(array($pipe->getRow(), $pipe->getCol(), $pipe->getOrientation(), $pipe->getHandposition(), $pipe->getId()));
        } catch(\PDOException $e) {
            return false;
        }

        if(!$ret || $statement->rowCount() === 0) {
            return false;
        }

        return true;
    }

    public function createNewPipe(Pipe $pipe) {
        $sql = <<<SQL
insert into $this->tableName(gameid, userid, row, col, type, orientation, handposition)
values(?, ?, ?, ?, ?, ?, ?)
SQL;

        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);

        try {
            if($statement->execute(array($pipe->getGameid(), $pipe->getUserid(), $pipe->getRow(), $pipe->getCol(), $pipe->getType(), $pipe->getOrientation(), $pipe->getHandposition())) === false) {
                return false;
            }
        } catch(\PDOException $e) {
            return $e;
        }

        return $pdo->lastInsertId();
    }

    public function discardPipe(Pipe $pipe) {
        $this->createNewPipe(new Pipe(array('id' => NULL,
            'gameid' => $pipe->getGameid(),
            'userid' => $pipe->getUserid(),
            'row' => NULL,
            'column' => NULL,
            'type' => rand(0, 3),
            'orientation' => 0,
            'handposition' => $pipe->getHandposition())));

        return $pipe->getId();
    }



    public function getPipeFromRow($row) {
        $type = $row['type'];
        switch($type) {
            case self::CAP:
                return new CapPipe($row);
            case self::GAUGE:
                return new GaugePipe($row);
            case self::LEAK:
                return new Leak($row);
            case self::NINETY:
                return new NinetyPipe($row);
            case self::STRAIGHT:
                return new StraightPipe($row);
            case self::TEE:
                return new TeePipe($row);
            case self::VALVE:
                return new ValvePipe($row);
            default:
                return null;
        }
    }

    const CAP = 0;
    const NINETY = 1;
    const STRAIGHT = 2;
    const TEE = 3;
    const VALVE = 4;
    const LEAK = 5;
    const GAUGE = 6;
}