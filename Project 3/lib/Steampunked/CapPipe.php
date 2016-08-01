<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 2/17/16
 * Time: 5:25 PM
 */

namespace Steampunked;


class CapPipe extends Pipe
{

    const NORTH = 0;
    const EAST = 1;
    const SOUTH = 2;
    const WEST = 3;

    public function __construct(array $row)
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
                return "images/cap-n.png";
            case self::EAST:
                return "images/cap-e.png";
            case self::SOUTH:
                return "images/cap-s.png";
            case self::WEST:
                return "images/cap-w.png";
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