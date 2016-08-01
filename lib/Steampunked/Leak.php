<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 2/17/16
 * Time: 5:58 PM
 */

namespace Steampunked;


class Leak extends Pipe
{
    const NORTH = 0;
    const EAST = 1;
    const SOUTH = 2;
    const WEST = 3;

    public function __construct($row)
    {
        parent::__construct($row);
    }

    public function rotate()
    {
        $this->orientation = ($this->orientation + 1) % 4;
    }

    public function getImageUrl()
    {
        switch($this->orientation) {
            case self::NORTH:
                return "images/leak-n.png";
            case self::EAST:
                return "images/leak-e.png";
            case self::SOUTH:
                return "images/leak-s.png";
            case self::WEST:
                return "images/leak-w.png";
            default:
                return null;
        }
    }

    public function getDirections()
    {
        switch($this->orientation) {
            case self::NORTH:
                return array("N" => true, "E" => false, "S" => false, "W" => false);
            case self::EAST:
                return array("N" => false, "E" => true, "S" => false, "W" => false);
            case self::SOUTH:
                return array("N" => false, "E" => false, "S" => true, "W" => false);
            case self::WEST:
                return array("N" => false, "E" => false, "S" => false, "W" => true);
            default:
                return null;
        }
    }
}