<?php
/**
 * Created by PhpStorm.
 * User: kayla
 * Date: 4/3/2016
 * Time: 9:08 PM
 */

namespace Steampunked;


class RegisterController
{
    public function __construct(Site $site, array $post) {
        $root = $site->getRoot();
        $this->redirect = $root;

        $users = new Users($site);

        if(isset($post['cancel'])) {
            return;
        }

        $id = 0;

        //
        // Get all of the stuff from the form
        //
        $email = strip_tags($post['email']);
        $name = strip_tags($post['name']);

        $row = array('id' => $id,
            'email' => $email,
            'name' => $name,
            'password' => null,
        );

        $newUser = new User($row);

        $mailer = new Email();
        $users->add($newUser, $mailer);
    }

    /**
     * @return mixed
     */
    public function getRedirect() {
        return $this->redirect;
    }


    private $redirect;	///< Page we will redirect the user to.
}