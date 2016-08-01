<?php
/**
 * Created by PhpStorm.
 * User: kayla
 * Date: 4/3/2016
 * Time: 9:08 PM
 */

namespace Steampunked;


class RegisterView
{
    /** Constructor */
    public function __construct($site, array $get) {
    }

    public function present_header() {
        $html = <<<HTML
<header>
    <nav>
        <p><a href="howToPlay.php">How To Play</a> | <a href="login.php">Login</a></p>
    </nav>
    <img src = "images/title.png" width="600" height="104" alt="Steampunked" />
</header>
HTML;
        return $html;
    }

    public function present() {

        $html = <<<HTML
<form method="post" action="post/register.php">
	<fieldset>
		<legend>New User</legend>
		<p>
			<label for="email">Email</label><br>
			<input type="email" id="email" name="email" placeholder="Email">
		</p>
		<p>
			<label for="name">Name</label><br>
			<input type="text" id="name" name="name" placeholder="Name">
		</p>
		<p>
			<input type="submit" value="OK"> <input type="submit" name="cancel" value="Cancel">
		</p>

	</fieldset>
</form>
		<p><a href="./">Steampunked Home</a></p>

HTML;

        return $html;

    }

}