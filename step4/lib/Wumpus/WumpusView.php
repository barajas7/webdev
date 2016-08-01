<?php
/**
 * Created by PhpStorm.
 * User: Arturo Barajas
 * Date: 2/8/2016
 * Time: 12:32 AM
 */

namespace Wumpus;


class WumpusView
{
    /**
     * Constructor
     * @param Wumpus $wumpus The Wumpus object
     */
    public function __construct(Wumpus $wumpus) {
        $this->wumpus = $wumpus;
    }

    /** Generate the HTML for the number of arrows remaining */
    public function presentArrows() {
        $a = $this->wumpus->numArrows();
        return "<p>You have $a arrows remaining.</p>";
    }

    /** Generate the HTML for status */
    public function presentStatus() {
        $a = $this->wumpus->getCurrent()->getNum();
        $str = "<p>You are in room $a</p><br>";

        if ($this->wumpus->hearBirds()) {
            $str .= "<p>You hear birds!</p>";
        }

        if ($this->wumpus->feelDraft()) {
            $str .= "<p>You feel a draft!</p>";
        }

        if ($this->wumpus->smellWumpus()) {
            $str .= "<p>You smell a wumpus!</p>";
        }

        if ($this->wumpus->wasCarried()) {
            $str .= "<p>You were carried by the birds to room $a!</p>";
        }

        return $str;
    }

    /** Present the links for a room
     * @param $ndx An index 0 to 2 for the three rooms */
    public function presentRoom($ndx) {
        $room = $this->wumpus->getCurrent()->getNeighbors()[$ndx];
        $roomnum = $room->getNum();
        $roomndx = $room->getNdx();
        $roomurl = "game-post.php?m=$roomndx";
        $shooturl = "game-post.php?s=$roomndx";

        $html = <<<HTML
<div class="room">
  <figure><img src="cave2.jpg" width="180" height="135" alt=""/></figure>
  <p><a href="$roomurl">$roomnum</a></p>
<p><a href="$shooturl">Shoot Arrow</a></p>
</div>
HTML;

        return $html;
    }


    private $wumpus;    // The Wumpus object
}