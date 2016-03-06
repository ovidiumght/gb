<?php

namespace Gb\Comment;


use Gb\Vo\CommentContent;
use Gb\Vo\Email;
use Gb\Vo\Name;

class Comment
{

    private $name;

    private $email;

    private $content;

    public function __construct(Name $name, Email $email, CommentContent $commentContent)
    {
        $this->name = $name;
        $this->email = $email;
        $this->content = $commentContent;
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'content' => $this->content
        ];
    }
}