<?php
/**
 * Created by PhpStorm.
 * User: Arturo Barajas
 * Date: 2/8/2016
 * Time: 1:20 AM
 */

namespace Wumpus;


class WumpusController
{
    // Page getter
    public function getPage() {
        return $this->page;
    }

    // Reset bool value getter
    public function isReset() {
        return $this->reset;
    }

    // Cheat mode bool value getter
    public function isCheat() {
        return $this->cheat;
    }

    /**
     * Constructor
     * @param Wumpus $wumpus The Wumpus object
     * @param $request The $_REQUEST array
     */
    public function __construct(Wumpus $wumpus, $request) {
        $this->wumpus = $wumpus;

        if(isset($request['m'])) {
            $this->move($request['m']);
        } else if(isset($request['s'])) {
            $this->shoot($request['s']);
        } else if(isset($request['n'])) {
            // New game!
            $this->reset = true;
        } else if(isset($request['c'])) {
            // Enter cheat mode!
            $this->cheat = true;
        }
    }

    /** Move request
     * @param $ndx Index for room to move to */
    /** Move request
     * @param $ndx Index for room to move to */
    private function move($ndx) {
        // Simple error check
        if(!is_numeric($ndx) || $ndx < 1 || $ndx > Wumpus::NUM_ROOMS) {
            return;
        }

        switch($this->wumpus->move($ndx)) {
            case Wumpus::HAPPY:
                break;

            case Wumpus::EATEN:
            case Wumpus::FELL:
                $this->reset = true;
                $this->page = 'lose.php';
                break;
        }
    }

    /** Shoot request
     * @ndx Index for room to shoot to */
    private function shoot($ndx) {
        if(!is_numeric($ndx) || $ndx < 1 || $ndx > Wumpus::NUM_ROOMS) {
            return;
        }

        if ($this->wumpus->shoot($ndx)) {
            $this->reset = true;
            $this->page = 'win.php';
        }

        else {
            if ($this->wumpus->numArrows() == 0) {
                $this->reset = true;
                $this->page = 'lose.php';
            }
        }
    }

    private $wumpus;                // The Wumpus object we are controlling
    private $page = 'game.php';     // The next page we will go to
    private $reset = false;         // True if we need to reset the game
    private $cheat = false;         // True if we enter cheat mode
}