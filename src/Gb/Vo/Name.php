<?php

namespace Gb\Vo;


class Name
{

    private $firstName;

    private $lastName;

    public function __construct($firstName, $lastName)
    {
        if(strlen($firstName) < 3 || strlen($lastName) < 3) {
            throw new \Exception('The name cannot be less than 3 characters long');
        }

        $this->firstName = $firstName;

        $this->lastName = $lastName;
    }

    public function __toString()
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}