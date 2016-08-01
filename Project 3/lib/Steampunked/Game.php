<?php

namespace Steampunked;


class Game
{
    //const SESSION_NAME = 'user';

    /**
     * Constructor
     * @param $row Row from the user table in the database
     */
    public function __construct(array $row) {
        $this->id = $row['id'];
        $this->size = $row['size'];
        $this->player1 = $row['player1'];
        $this->player2 = $row['player2'];
        $this->currentplayer = $row['currentplayer'];
        $this->winner = $row['winner'];
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return mixed
     */
    public function getPlayer1()
    {
        return $this->player1;
    }

    /**
     * @param mixed $player1
     */
    public function setPlayer1($player1)
    {
        $this->player1 = $player1;
    }

    /**
     * @return mixed
     */
    public function getPlayer2()
    {
        return $this->player2;
    }

    /**
     * @param mixed $player2
     */
    public function setPlayer2($player2)
    {
        $this->player2 = $player2;
    }

    /**
     * @return mixed
     */
    public function getCurrentplayer()
    {
        return $this->currentplayer;
    }

    /**
     * @param mixed $currentplayer
     */
    public function setCurrentplayer($currentplayer)
    {
        $this->currentplayer = $currentplayer;
    }

    /**
     * @return mixed
     */
    public function getPlayer1Obj()
    {
        return $this->player1_obj;
    }

    /**
     * @param mixed $player1_obj
     */
    public function setPlayer1Obj($player1_obj)
    {
        $this->player1_obj = $player1_obj;
    }

    /**
     * @return mixed
     */
    public function getPlayer2Obj()
    {
        return $this->player2_obj;
    }

    /**
     * @param mixed $player2_obj
     */
    public function setPlayer2Obj($player2_obj)
    {
        $this->player2_obj = $player2_obj;
    }

    /**
     * @return mixed
     */
    public function getThisPlayer()
    {
        return $this->this_player;
    }

    /**
     * @param mixed $this_player
     */
    public function setThisPlayer($this_player)
    {
        $this->this_player = $this_player;
    }

    /**
     * @return mixed
     */
    public function getThisPlayerHand()
    {
        return $this->this_player_hand;
    }

    /**
     * @param mixed $this_player_hand
     */
    public function setThisPlayerHand($this_player_hand)
    {
        $this->this_player_hand = $this_player_hand;
    }

    /**
     * @return mixed
     */
    public function getWinner()
    {
        return $this->winner;
    }

    /**
     * @param mixed $winner
     */
    public function setWinner($winner)
    {
        $this->winner = $winner;
    }

    /**
     * @return mixed
     */
    public function getGrid()
    {
        return $this->grid;
    }

    public function createGridfromList($pipe_list) {
        for ($row = 0; $row < $this->size; $row++) {
            for ($col = 0; $col < $this->size + 2; $col++) {
                $this->grid[$row][$col] = null;
            }
        }

        foreach($pipe_list as $pipe) {
            $this->grid[$pipe->getRow()][$pipe->getCol()] = $pipe;
        }
    }

    public function isValid($currentPipeIndex, $row, $col)
    {
        $pipe = $this->this_player_hand[$currentPipeIndex];
        $directions = $pipe->getDirections();

        //Check that square is a leak
        if(!($this->grid[$row][$col] instanceof Leak)) {
            return false;
        }

        //array that holds valid directions
        //n e s w,
        $valid_directions = array("N" => false, "E" => false, "S" => false, "W" => false);
        $connects_to_a_pipe = false;

        //It is next to a pipe
        //If it opens up north and it isnt on the top row
        if($directions["N"] && $row > 0) {
            $north_pipe = $this->grid[$row - 1][$col];
            //if there is a pipe to the north, and that pipe is owned by the current player
            if($north_pipe != null && $north_pipe->getUserid() == $this->this_player->getId()) {
                //If it's a leak
                if($north_pipe instanceof Leak) {
                    $valid_directions["N"] = true;
                }
                $north_pipe_directions = $north_pipe->getDirections();
                //if it's a pipe, and that pipe opens up south
                if($north_pipe_directions["S"]) {
                    $valid_directions["N"] = true;
                    $connects_to_a_pipe = true;
                }
            }
            //if the north square is empty
            else if($north_pipe == null) {
                $valid_directions["N"] = true;
            }
        }
        if($directions["E"] && $col < $this->size + 1) {
            $east_pipe = $this->grid[$row][$col + 1];
            if($east_pipe != null && $east_pipe->getUserid() == $this->this_player->getId()) {
                if($east_pipe instanceof Leak) {
                    $valid_directions["E"] = true;
                }
                $east_pipe_directions = $east_pipe->getDirections();
                if($east_pipe_directions["W"]) {
                    $valid_directions["E"] = true;
                    $connects_to_a_pipe = true;
                }
            }
            else if ($east_pipe == null) {
                $valid_directions["E"] = true;
            }
        }
        if($directions["S"] && $row < $this->size - 1) {
            $south_pipe = $this->grid[$row + 1][$col];
            if($south_pipe != null && $south_pipe->getUserid() == $this->this_player->getId()) {
                if($south_pipe instanceof Leak) {
                    $valid_directions["S"] = true;
                }
                $south_pipe_directions = $south_pipe->getDirections();
                if($south_pipe_directions["N"]) {
                    $valid_directions["S"] = true;
                    $connects_to_a_pipe = true;
                }
            }
            else if ($south_pipe == null) {
                $valid_directions["S"] = true;
            }
        }
        if($directions["W"] && $col > 0) {
            $west_pipe = $this->grid[$row][$col - 1];
            if($west_pipe != null && $west_pipe->getUserid() == $this->this_player->getId()) {
                if($west_pipe instanceof Leak) {
                    $valid_directions["W"] = true;
                }
                $west_pipe_directions = $west_pipe->getDirections();
                if($west_pipe_directions["E"]) {
                    $valid_directions["W"] = true;
                    $connects_to_a_pipe = true;
                }
            }
            else if ($west_pipe == null) {
                $valid_directions["W"] = true;
            }
        }

        //If none of the valid directions were to a pipe, the pipe cannot be placed
        if(!$connects_to_a_pipe) {
            return false;
        }
        //If any one of the open directions is not valid, the pipe cannot be placed
        foreach(array("N", "E", "S", "W") as $direction) {
            if($directions[$direction] != $valid_directions[$direction]) {
                return false;
            }
        }

        return true;
    }

    public function unflagGrid() {
        for ($row = 0; $row < $this->size; $row++) {
            for ($col = 0; $col < $this->size + 2; $col++) {
                $pipe = $this->grid[$row][$col];
                if($pipe != null) {
                    $pipe->setFlag(false);
                    if($pipe instanceof Leak && $pipe->getUserid() == $this->this_player->getId()) {
                        $this->grid[$row][$col] = null;
                    }
                }
            }
        }
    }

    public function indicateLeaks() {
        $center_line = (int)($this->size / 2);

        $this->unflagGrid();

        $leaks = array();

        //Get starting pipe
        if($this->this_player->getId() == $this->player1) {
            $row = $center_line - 3;
        }
        else {
            $row = $center_line + 2;
        }
        $col = 0;
        $first_pipe = $this->grid[$row][$col];

        //Create stack for iterative DFS
        $stack = new \SplStack();
        if($first_pipe != null) {
            $stack->push($first_pipe);
        }


        while(!$stack->isEmpty()) {
            $current_pipe = $stack->pop();
            $current_directions = $current_pipe->getDirections();

            foreach(array("N", "W", "S", "E") as $direction) {
                if($current_directions[$direction] == true) {
                    $neighbor = $this->getNeighboringPipe($direction, $current_pipe->getRow(), $current_pipe->getCol());

                    if($neighbor === null) {
                        $leak = $this->createLeak($direction, $current_pipe->getRow(), $current_pipe->getCol());

                        //leak could be null if it would be outside the grid
                        if($leak != null) {
                            $leaks[] = $leak;
                        }

                    }
                    else if (!$neighbor->isFlag() && $neighbor->getUserid() == $this->this_player->getId()) {
                        $stack->push($neighbor);
                    }
                }
            }

            $current_pipe->setFlag(true);
        }

        if(count($leaks) == 0) {
            return false;
        }
        else {
            foreach($leaks as $leak) {
                $this->grid[$leak->getRow()][$leak->getCol()] = $leak;
            }
            return true;
        }

    }

    public function getNeighboringPipe($direction, $row, $col) {
        if($direction == "N") {
            $row -= 1;
        }
        else if ($direction == "E") {
            $col += 1;
        }
        else if ($direction == "S") {
            $row += 1;
        }
        else if ($direction == "W") {
            $col -= 1;
        }

        if($row < 0 || $col < 0 || $row >= $this->size || $col >= $this->size + 2) {
            return null;
        }
        else {
            return $this->grid[$row][$col];
        }
    }

    public function createLeak($direction, $row, $col) {
        if($direction == "N") {
            $row -= 1;
            $orientation = Leak::SOUTH;
        }
        else if ($direction == "E") {
            $col += 1;
            $orientation = Leak::WEST;
        }
        else if ($direction == "S") {
            $row += 1;
            $orientation = Leak::NORTH;
        }
        else if ($direction == "W") {
            $col -= 1;
            $orientation = Leak::EAST;
        }

        if($row < 0 || $col < 0 || $row >= $this->size || $col >= $this->size + 2) {
            return null;
        }
        else {
            $leak_row = array('id' => NULL,
                'gameid' => $this->id,
                'userid' => $this->this_player->getId(),
                'row' => $row,
                'col' => $col,
                'type' => Pipes::LEAK,
                'orientation' => $orientation,
                'handposition' => NULL);
            $leak = new Leak($leak_row);
            return $leak;
        }
    }


    private $id;
    private $size;
    private $player1;
    private $player2;
    private $currentplayer;
    private $winner;

    private $player1_obj;
    private $player2_obj;
    private $this_player;
    private $this_player_hand;
    private $grid;
}