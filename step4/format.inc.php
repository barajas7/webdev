<?php
/**
 * Created by PhpStorm.
 * User: Arturo Barajas
 * Date: 2/5/2016
 * Time: 4:06 AM
 */

function present_header($title) {
    $html = "<header>";
    $html .= "<nav><p><a href=\"welcome.php\">New Game</a> ";
    $html .= "<a href=\"game.php\">Game</a> ";
    $html .= "<a href=\"instructions.php\">Instructions</a></p></nav>";
    $html .= "<h1>$title</h1>";
    $html .= "</header>";

    return $html;
}