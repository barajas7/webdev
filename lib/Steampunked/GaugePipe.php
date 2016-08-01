<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 2/17/16
 * Time: 6:20 PM
 */

namespace Steampunked;


class GaugePipe extends Pipe
{
    const ZERO = 0;
    const ONE_NINETY = 1;
    const TOP_ZERO = 2;
    const TOP_ONE_NINETY = 3;


    public function __construct($row)
    {
        parent::__construct($row);
    }

    public function rotate()
    {
        if($this->orientation == self::ZERO) {
            $this->orientation = self::ONE_NINETY;
        }
        else if($this->orientation == self::TOP_ZERO) {
            $this->orientation = self::TOP_ONE_NINETY;
        }
    }

    public function getImageUrl()
    {
        switch($this->orientation) {
            case self::ZERO:
                return "images/gauge-0.png";
            case self::ONE_NINETY:
                return "images/gauge-190.png";
            case self::TOP_ZERO:
                return "images/gauge-top-0.png";
            case self::TOP_ONE_NINETY:
                return "images/gauge-top-190.png";
            default:
                return null;
        }
    }

    public function getDirections()
    {
        switch($this->orientation) {
            case self::ZERO:
                return array("N" => false, "E" => false, "S" => false, "W" => true);
            case self::TOP_ZERO:
                return array("N" => false, "E" => false, "S" => false, "W" => false);
            case self::ONE_NINETY:
                return array("N" => false, "E" => false, "S" => false, "W" => false);
            case self::TOP_ONE_NINETY:
                return array("N" => false, "E" => false, "S" => false, "W" => false);
            default:
                return null;
        }
    }
}