<?php
/**
 * Created by PhpStorm.
 * User: kayla
 * Date: 4/3/2016
 * Time: 9:08 PM
 */

namespace Steampunked;


class PasswordValidateView
{
    /** Constructor */
    public function __construct(array $get)
    {
        $this->error = false;
        if(isset($get['v'])) {
            $this->validator = strip_tags($get['v']);
        }

        if(isset($get['e'])) {
            $this->error = true;
            $this->errorCode = 'e';
        } else if (isset($get['p'])) {
            $this->error = true;
            $this->errorCode = 'p';
        } else if (isset($get['l'])) {
            $this->error = true;
            $this->errorCode = 'l';
        }
    }

    public function present_header()
    {
        $html = <<<HTML
<header>
    <img src = "images/title.png" width="600" height="104" alt="Steampunked" />
</header>
HTML;
        return $html;
    }

    public function present()
    {
        $html = <<<HTML
<form action="post/password-validate.php" method="post">
<input type="hidden" name="validator" value=$this->validator>
	<fieldset>
		<legend>Change Password</legend>
		<p>
			<label for="email">Email</label><br>
			<input type="email" id="email" name="email" placeholder="Email">
		</p>
		<p>
			<label for="password">Password:</label><br>
			<input type="password" id="password" name="password" placeholder="password">
		</p>
		<p>
			<label for="password2">Password (again):</label><br>
			<input type="password" id="password2" name="password2" placeholder="password">
		</p>
		<p>
			<input type="submit" value="OK" id="ok" name="ok"> <input type="submit" value="Cancel" id="cancel" name="cancel">
		</p>
	</fieldset>
</form>

HTML;

        return $html;

    }

    public function error_message()
    {
        $html = <<<HTML
<p>
HTML;
        $html .= $this->error();
        $html .= <<<HTML
</p>
HTML;

        return $html;
    }

    public function error() {
        $html = "";
        if ($this->error) {
            if($this->errorCode == 'e') {
                $html .= <<<HTML
<p class="error">Email address is invalid.</p>
HTML;
            } else if ($this->errorCode == 'p') {
                $html .= <<<HTML
<p class="error">Passwords don't match.</p>
HTML;
            } else {
                $html .= <<<HTML
<p class="error">Password is too short.</p>
HTML;
            }
        }
        return $html;
    }


    private $validator;
    private $error;
    private $errorCode;
}