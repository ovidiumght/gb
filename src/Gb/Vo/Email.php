<?php

namespace Gb\Vo;


class Email
{
    protected $email;

    public function __construct($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('The email is invalid');
        }

        $this->email = $email;
    }

    public function __toString()
    {
        return $this->email;
    }
}