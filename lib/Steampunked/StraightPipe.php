<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 2/17/16
 * Time: 5:10 PM
 */

namespace Steampunked;


class StraightPipe extends Pipe
{
    const VERTICAL = 0;
    const HORIZONTAL = 1;

    public function __construct($row)
    {
        parent::__construct($row);
    }

    public function rotate()
    {
        $this->orientation = ($this->orientation + 1) % 2;
    }

    public function getImageUrl()
    {
        switch($this->orientation) {
            case self::VERTICAL:
                return "images/straight-v.png";
            case self::HORIZONTAL:
                return "images/straight-h.png";
            default:
                return null;
        }
    }

    public function getDirections()
    {
        switch($this->orientation) {
            case self::VERTICAL:
                return array("N" => true, "E" => false, "S" => true, "W" => false);
            case self::HORIZONTAL:
                return array("N" => false, "E" => true, "S" => false, "W" => true);
            default:
                return null;
        }
    }
}