<?php
/**
 * Created by PhpStorm.
 * User: kayla
 * Date: 4/3/2016
 * Time: 10:40 PM
 */

namespace Steampunked;


class Email
{
    public function mail($to, $subject, $message, $headers) {
        mail($to, $subject, $message, $headers);
    }
}