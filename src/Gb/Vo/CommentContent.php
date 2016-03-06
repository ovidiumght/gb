<?php

namespace Gb\Vo;


class CommentContent
{

    private $message;
    public function __construct($message)
    {
        if(strlen($message) < 5) {
            throw new \Exception('The message length cannot be less than 5 characters');
        }

        $this->message = $message;
    }

    public function __toString()
    {
        return $this->message;
    }
}