<?php
/**
 * Created by PhpStorm.
 * User: kayla
 * Date: 4/3/2016
 * Time: 9:08 PM
 */

namespace Steampunked;


class PasswordValidateController
{
    public function __construct(Site $site, array $post) {
        $root = $site->getRoot();

        if(isset($post['cancel'])){
            return;
        }

        //
        // 1. Ensure the validator is correct! Use it to get the user ID.
        // 2. Destroy the validator record so it can't be used again!
        //
        $validators = new Validators($site);
        $validator = $post['validator'];
        $userid = $validators->getOnce($post['validator']);

        if($userid === null) {
            $this->redirect = "$root/index.php?a";
            return;
        }

        $users = new Users($site);
        $editUser = $users->get($userid);
        if($editUser === null) {
            // User does not exist!
            $this->redirect = "$root/index.php?b";
            return;
        }
        $email = trim(strip_tags($post['email']));
        if($email !== $editUser->getEmail()) {
            // Email entered is invalid
            $v = $validators->newValidator($userid);
            $this->redirect = "$root/password-validate.php?v=$v&e";
            return;
        }

        $password1 = trim(strip_tags($post['password']));
        $password2 = trim(strip_tags($post['password2']));
        if($password1 !== $password2) {
            // Passwords do not match
            $v = $validators->newValidator($userid);
            $this->redirect = "$root/password-validate.php?v=$v&p";
            return;
        }

        if(strlen($password1) < 8) {
            // Password too short
            $v = $validators->newValidator($userid);
            $this->redirect = "$root/password-validate.php?v=$v&l";
            return;
        }

        $this->redirect = "$root/index.php?c";
        $users->setPassword($userid, $password1);
        return;
    }

    /**
     * @return string
     */
    public function getRedirect()
    {
        return $this->redirect;
    }

    private $redirect;///< Page we will redirect the user to.
}