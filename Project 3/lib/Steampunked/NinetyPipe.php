<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 2/17/16
 * Time: 5:50 PM
 */

namespace Steampunked;


class NinetyPipe extends Pipe
{
    const NORTH_EAST = 0;
    const EAST_SOUTH = 1;
    const SOUTH_WEST = 2;
    const WEST_NORTH = 3;

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
            case self::NORTH_EAST:
                return "images/ninety-ne.png";
            case self::EAST_SOUTH:
                return "images/ninety-es.png";
            case self::SOUTH_WEST:
                return "images/ninety-sw.png";
            case self::WEST_NORTH:
                return "images/ninety-wn.png";
            default:
                return null;
        }
    }

    public function getDirections()
    {
        switch($this->orientation) {
            case self::NORTH_EAST:
                return array("N" => true, "E" => true, "S" => false, "W" => false);
            case self::EAST_SOUTH:
                return array("N" => false, "E" => true, "S" => true, "W" => false);
            case self::SOUTH_WEST:
                return array("N" => false, "E" => false, "S" => true, "W" => true);
            case self::WEST_NORTH:
                return array("N" => true, "E" => false, "S" => false, "W" => true);
            default:
                return null;
        }
    }
}