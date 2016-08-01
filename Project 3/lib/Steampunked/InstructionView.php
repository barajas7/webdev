<?php
/**
 * Created by PhpStorm.
 * User: Pyromaniac Girl
 * Date: 2/16/2016
 * Time: 11:18 PM
 */

namespace Steampunked;


class InstructionView
{
    /** Constructor */
    public function __construct() {
    }

    public function present_header() {
        $html = <<<HTML
<header>
    <nav>
        <p><a href="index.php">Back to Game</a> | <a href="register.php">Register</a> | <a href="login.php">Login</a></p>
    </nav>
    <img src = "images/title.png" width="600" height="104" alt="Steampunked" />
</header>
HTML;
        return $html;
    }

    public function present() {
        $html = '<h1>Team Garret</h1>';

        $html .= <<<HTML
<h2>Team Members:</h2>
<p>Arturo Barajas</p>
<p>Kayla Sue Grotsky</p>
<p>Alexander R Morton</p>
<h2>How To Play:</h2>
<div id="instructions">
<p>The object of the game is to place pipe that will connect a steam source to a steam engine so it can power your
airship. Of course, you want to do this before your opponent does. The winner is the first one to connect steam to
their engine with no leaks and turn on the valve.</p>
</div>
HTML;

        return $html;

    }
}