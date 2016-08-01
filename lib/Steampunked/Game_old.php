<?php
/**
 * Created by PhpStorm.
 * User: Pyromaniac Girl
 * Date: 2/16/2016
 * Time: 11:19 PM
 */

namespace Steampunked;


class Game_old
{

    /**
     * Constructor
     * @param $seed Random number seed
     */
    //todo: remove seed 1234
    public function __construct($seed = 1234)
    {
        if ($seed === null) {
            $seed = time();
        }

        srand($seed);

        $this->setupPipes();
    }

    public function setupGrid($size)
    {
        $this->height = $size;
        $this->size = $size;
        $this->width = $size + 2;


        for ($row = 0; $row < $size; $row++) {
            for ($col = 0; $col < $this->width; $col++) {
                $this->grid[$row][$col] = null;
            }
        }

        $this->center_line = (int)($size / 2);

        //Valves
        $valve = new ValvePipe(ValvePipe::CLOSED, $this->player1);
        $valve->setRow($this->center_line - 3);
        $valve->setCol(0);
        $this->grid[$this->center_line - 3][0] = $valve;

        $valve = new ValvePipe(ValvePipe::CLOSED, $this->player2);
        $valve->setRow($this->center_line + 2);
        $valve->setCol(0);
        $this->grid[$this->center_line + 2][0] = $valve;

        //Leaks
        $leak = new Leak(Leak::WEST, $this->player1);
        $leak->setRow($this->center_line - 3);
        $leak->setCol(1);
        $this->grid[$this->center_line - 3][1] = $leak;

        $leak = new Leak(Leak::WEST, $this->player2);
        $leak->setRow($this->center_line + 2);
        $leak->setCol(1);
        $this->grid[$this->center_line + 2][1] = $leak;

        //Gauges
        $gauge_top = new GaugePipe(GaugePipe::TOP_ZERO, $this->player1);
        $gauge_top->setRow($this->center_line - 3);
        $gauge_top->setCol($this->width - 1);
        $this->grid[$this->center_line - 3][$this->width - 1] = $gauge_top;

        $gauge_bottom = new GaugePipe(GaugePipe::ZERO, $this->player1);
        $gauge_bottom->setRow($this->center_line - 2);
        $gauge_bottom->setCol($this->width - 1);
        $this->grid[$this->center_line - 2][$this->width - 1] = $gauge_bottom;

        $gauge_top = new GaugePipe(GaugePipe::TOP_ZERO, $this->player2);
        $gauge_top->setRow($this->center_line);
        $gauge_top->setCol($this->width - 1);
        $this->grid[$this->center_line][$this->width - 1] = $gauge_top;

        $gauge_bottom = new GaugePipe(GaugePipe::ZERO, $this->player2);
        $gauge_bottom->setRow($this->center_line + 1);
        $gauge_bottom->setCol($this->width - 1);
        $this->grid[$this->center_line + 1][$this->width - 1] = $gauge_bottom;
    }

    public function setupPipes()
    {
        $this->pipes = array(
            0 => new CapPipe(CapPipe::NORTH),
            1 => new CapPipe(CapPipe::EAST),
            2 => new CapPipe(CapPipe::SOUTH),
            3 => new CapPipe(CapPipe::WEST),
            4 => new NinetyPipe(NinetyPipe::EAST_SOUTH),
            5 => new NinetyPipe(NinetyPipe::NORTH_EAST),
            6 => new NinetyPipe(NinetyPipe::SOUTH_WEST),
            7 => new NinetyPipe(NinetyPipe::WEST_NORTH),
            8 => new StraightPipe(StraightPipe::HORIZONTAL),
            9 => new StraightPipe(StraightPipe::VERTICAL),
            10 => new TeePipe(TeePipe::EAST_SOUTH_WEST),
            11 => new TeePipe(TeePipe::NORTH_EAST_SOUTH),
            12 => new TeePipe(TeePipe::SOUTH_WEST_NORTH),
            13 => new TeePipe(TeePipe::WEST_NORTH_EAST));
    }

    public function setPlayerOne(Player $player1)
    {
        $this->player1 = $player1;

        $this->player1->setPipeList(array(
            0 => $this->getRandomPipe($player1),
            1 => $this->getRandomPipe($player1),
            2 => $this->getRandomPipe($player1),
            3 => $this->getRandomPipe($player1),
            4 => $this->getRandomPipe($player1)));
        $this->player1->setCurrentPipe(0);

        $this->currentPlayer = $this->player1;
    }

    public function setPlayerTwo(Player $player2){
        $this->player2 = $player2;

        $this->player2->setPipeList(array(
            0 => $this->getRandomPipe($player2),
            1 => $this->getRandomPipe($player2),
            2 => $this->getRandomPipe($player2),
            3 => $this->getRandomPipe($player2),
            4 => $this->getRandomPipe($player2)));
        $this->player2->setCurrentPipe(0);
    }

    public function getCurrentPlayer()
    {
        return $this->currentPlayer;
    }

    public function switchPlayers()
    {
        if ($this->currentPlayer == $this->player1) {
            $this->currentPlayer = $this->player2;
        } else {
            $this->currentPlayer = $this->player1;
        }
    }

    public function getWinner()
    {
        return $this->winner;
    }

    public function getLoser()
    {
        return $this->loser;
    }

    public function isValid($row, $col)
    {
        $pipe = $this->currentPlayer->getCurrentPipe();
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
            if($north_pipe != null && $north_pipe->getOwner() == $this->currentPlayer) {
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
        if($directions["E"] && $col < $this->width - 1) {
            $east_pipe = $this->grid[$row][$col + 1];
            if($east_pipe != null && $east_pipe->getOwner() == $this->currentPlayer) {
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
        if($directions["S"] && $row < $this->height - 1) {
            $south_pipe = $this->grid[$row + 1][$col];
            if($south_pipe != null && $south_pipe->getOwner() == $this->currentPlayer) {
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
            if($west_pipe != null && $west_pipe->getOwner() == $this->currentPlayer) {
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

    public function addPipe($row, $col) {
        if($this->winner != null) {
            return false;
        }

        if(!$this->isValid($row, $col)) {
            return false;
        }

        $pipe = $this->currentPlayer->getCurrentPipe();
        $this->grid[$row][$col] = $pipe;
        $pipe->setRow($row);
        $pipe->setCol($col);

        $this->indicateLeaks();

        $this->discardPipe();

        return true;
    }

    public function discardPipe() {
        if($this->winner != null) {
            return false;
        }

        $index = $this->currentPlayer->getCurrentPipeIndex();
        $this->currentPlayer->setPipeAtIndex($index, $this->getRandomPipe($this->currentPlayer));
        $this->currentPlayer->setCurrentPipe($index);

        $this->switchPlayers();
    }

    public function unflagGrid() {
        for ($row = 0; $row < $this->size; $row++) {
            for ($col = 0; $col < $this->width; $col++) {
                $pipe = $this->grid[$row][$col];
                if($pipe != null) {
                    $pipe->setFlag(false);
                    if($pipe instanceof Leak && $pipe->getOwner() == $this->currentPlayer) {
                        $this->grid[$row][$col] = null;
                    }
                }
            }
        }
    }

    public function indicateLeaks() {
        $this->unflagGrid();

        $leaks = array();

        //Get starting pipe
        if($this->currentPlayer == $this->player1) {
            $row = $this->center_line - 3;
        }
        else {
            $row = $this->center_line + 2;
        }
        $col = 0;
        $first_pipe = $this->grid[$row][$col];

        //Create stack for iterative DFS
        $stack = new \SplStack();
        $stack->push($first_pipe);

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
                    else if (!$neighbor->isFlag() && $neighbor->getOwner() == $this->currentPlayer) {
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

        if($row < 0 || $col < 0 || $row >= $this->height || $col >= $this->width) {
            return null;
        }
        else {
            return $this->grid[$row][$col];
        }
    }

    public function createLeak($direction, $row, $col) {
        if($direction == "N") {
            $row -= 1;
            $type = Leak::SOUTH;
        }
        else if ($direction == "E") {
            $col += 1;
            $type = Leak::WEST;
        }
        else if ($direction == "S") {
            $row += 1;
            $type = Leak::NORTH;
        }
        else if ($direction == "W") {
            $col -= 1;
            $type = Leak::EAST;
        }

        if($row < 0 || $col < 0 || $row >= $this->height || $col >= $this->width) {
            return null;
        }
        else {
            $leak = new Leak($type, $this->currentPlayer);
            $leak->setRow($row);
            $leak->setCol($col);
            return $leak;
        }
    }

    public function rotatePipe()
    {
        if($this->winner != null) {
            return false;
        }

        $this->currentPlayer->getCurrentPipe()->rotate();
    }

    public function giveUp()
    {
        if($this->winner != null) {
            return false;
        }

        if ($this->currentPlayer == $this->player1) {
            $this->winner = $this->player2;
            $this->loser = $this->player1;
        } else {
            $this->winner = $this->player1;
            $this->loser = $this->player2;
        }
    }

    public function openValve() {
        if($this->winner != null) {
            return false;
        }

        //Get starting pipe
        if($this->currentPlayer == $this->player1) {
            $valve_row = $this->center_line - 3;
        }
        else {
            $valve_row = $this->center_line + 2;
        }
        $valve = $this->grid[$valve_row][0];

        //open valve
        $valve->rotate();

        //If there are leaks, you lose
        if($this->indicateLeaks()) {
            $this->giveUp();
        }
        //Else you win!
        else {
            if($this->currentPlayer == $this->player1) {
                $gauge_top_row = $this->center_line - 3;
                $gauge_bottom_row = $this->center_line - 2;
            }
            else {
                $gauge_top_row = $this->center_line;
                $gauge_bottom_row = $this->center_line + 1;
            }
            $gauge_top = $this->grid[$gauge_top_row][$this->width - 1];
            $gauge_bottom = $this->grid[$gauge_bottom_row][$this->width - 1];

            $gauge_top->rotate();
            $gauge_bottom->rotate();

            //other player loses
            $this->switchPlayers();
            $this->giveUp();
        }


    }

    public function getRandomPipe($player)
    {
        $pipe = clone $this->pipes[rand(0, count($this->pipes) - 1)];
        $pipe->setOwner($player);
        return $pipe;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getGrid()
    {
        return $this->grid;
    }

    public function getPlayer1()
    {
        return $this->player1;
    }

    public function getPlayer2()
    {
        return $this->player2;
    }

    private $pipes = null;          //list of all possible pipe configurations
    private $grid = null;           //2d array that holds Pipe objects

    private $size = 0;              //size, either 6, 8, or 10
    private $height = 0;            //size (height of grid)
    private $width = 0;             //size + 2 (width of grid)
    private $center_line = 0;           //used to place gauges and valves at the start

    private $player1 = null;        //Player 1 object
    private $player2 = null;        //Player 3 object
    private $winner = null;         //Player that has won
    private $loser = null;          //Player that has lost
    private $currentPlayer = null;  //The player whose turn it is
}