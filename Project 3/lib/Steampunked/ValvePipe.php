<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 2/17/16
 * Time: 6:28 PM
 */

namespace Steampunked;


class ValvePipe extends Pipe
{
    const CLOSED = 0;
    const OPEN = 1;

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
            case self::CLOSED:
                return "images/valve-closed.png";
            case self::OPEN:
                return "images/valve-open.png";
            default:
                return null;
        }
    }

    public function getDirections()
    {
        return array("N" => false, "E" => true, "S" => false, "W" => false);
    }
}