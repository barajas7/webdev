<?php
/**
 * Created by PhpStorm.
 * User: kayla
 * Date: 3/26/2016
 * Time: 11:56 PM
 */

namespace Steampunked;


class Validators extends Table
{
    /**
     * Constructor
     * @param $site The Site object
     */
    public function __construct(Site $site) {
        parent::__construct($site, "validator");
    }

    /**
     * Create a new validator and add it to the table.
     * @param $userid User this validator is for.
     * @return The new validator.
     */
    public function newValidator($userid) {
        $validator = $this->createValidator();

        // Write to the table
        $sql = <<<SQL
INSERT INTO $this->tableName(userid, validator, date)
values(?, ?, ?)
SQL;
        $statement = $this->pdo()->prepare($sql);
        $statement->execute(array($userid, $validator, date("Y-m-d H:i:s")));

        return $validator;
    }

    /**
     * @brief Generate a random validator string of characters
     * @param $len Length to generate, default is 32
     * @returns Validator string
     */
    private function createValidator($len = 32) {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $l = strlen($chars) - 1;
        $str = '';
        for ($i = 0; $i < $len; ++$i) {
            $str .= $chars[rand(0, $l)];
        }
        return $str;
    }

    /**
     * Determine if a validator is valid. If it is,
     * get the user ID for that validator. Then destroy any
     * validator records for that user ID. Return the
     * user ID.
     * @param $validator Validator to look up
     * @return User ID or null if not found.
     */
    public function getOnce($validator) {

        // Write to the table
        $sql = <<<SQL
SELECT userid 
FROM $this->tableName
WHERE validator = '$validator'
SQL;
        $statement = $this->pdo()->prepare($sql);
        $statement->execute();
        if($statement->rowCount() === 0) {
            return null;
        }

        $row = $statement->fetch(\PDO::FETCH_ASSOC);
        $userid = $row['userid'];

        $sql = <<<SQL
DELETE FROM $this->tableName
WHERE userid = $userid
SQL;

        $statement = $this->pdo()->prepare($sql);
        $statement->execute();

        return $userid;
    }
}