<?php
/**
 * Created by PhpStorm.
 * User: kayla
 * Date: 4/3/2016
 * Time: 9:07 PM
 */

namespace Steampunked;


class LoginView
{
    /** Constructor */
    public function __construct($session, $get) {
        if(isset($_GET['e'])){
            $this->error = "Your login has failed.";
        }
    }

    public function present_header() {
        $html = <<<HTML
<header>
    <nav>
        <p><a href="howToPlay.php">How To Play</a> | <a href="register.php">Register</a></p>
    </nav>
    <img src = "images/title.png" width="600" height="104" alt="Steampunked" />
</header>
HTML;
        return $html;
    }

    public function present() {

        $html = <<<HTML
<form method="post" action="post/login.php">
	<fieldset>
		<legend>Login</legend>
		<p>
			<label for="email">Email</label><br>
			<input type="email" id="email" name="email" placeholder="Email">
		</p>
		<p>
			<label for="password">Password</label><br>
			<input type="password" id="password" name="password" placeholder="Password">
		</p>
		<p>
			<input type="submit" value="Log in">
		</p>

	</fieldset>
</form>
		<p><a href="./">Steampunked Home</a></p>

HTML;

        return $html;
    }

    public function error_message(){
        $html = <<<HTML
<p>
HTML;
        $html .= $this->error;
        $html .= <<<HTML
</p>
HTML;

        return $html;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param string $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }


    private $error = "";

}