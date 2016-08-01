<?php

namespace Steampunked;


class User
{
    const SESSION_NAME = 'user';

    /**
     * Constructor
     * @param $row Row from the user table in the database
     */
    public function __construct(array $row) {
        $this->id = $row['id'];
        $this->email = $row['email'];
        $this->name = $row['name'];
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


    private $id;		///< The internal ID for the user
    private $email;		///< Email address
    private $name; 		///< Name as last, first
}