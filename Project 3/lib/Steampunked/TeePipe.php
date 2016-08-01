<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 2/17/16
 * Time: 6:11 PM
 */

namespace Steampunked;


class TeePipe extends Pipe
{
    const NORTH_EAST_SOUTH = 0;
    const EAST_SOUTH_WEST = 1;
    const SOUTH_WEST_NORTH = 2;
    const WEST_NORTH_EAST = 3;

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
            case self::NORTH_EAST_SOUTH:
                return "images/tee-nes.png";
            case self::EAST_SOUTH_WEST:
                return "images/tee-esw.png";
            case self::SOUTH_WEST_NORTH:
                return "images/tee-swn.png";
            case self::WEST_NORTH_EAST:
                return "images/tee-wne.png";
            default:
                return null;
        }
    }

    public function getDirections()
    {
        switch($this->orientation) {
            case self::NORTH_EAST_SOUTH:
                return array("N" => true, "E" => true, "S" => true, "W" => false);
            case self::EAST_SOUTH_WEST:
                return array("N" => false, "E" => true, "S" => true, "W" => true);
            case self::SOUTH_WEST_NORTH:
                return array("N" => true, "E" => false, "S" => true, "W" => true);
            case self::WEST_NORTH_EAST:
                return array("N" => true, "E" => true, "S" => false, "W" => true);
            default:
                return null;
        }
    }
}