<?php

namespace Gb\Comment;


use Gb\Vo\CommentContent;
use Gb\Vo\Email;
use Gb\Vo\Name;

class Comment
{
    /** @var Name  */
    private $name;

    /** @var Email  */
    private $email;

    /** @var CommentContent  */
    private $content;

    /** @var int  */
    private $timeAdded;

    public function __construct(Name $name, Email $email, CommentContent $commentContent)
    {
        $this->name = $name;
        $this->email = $email;
        $this->content = $commentContent;
        $this->timeAdded = time();
    }

    public function toArray()
    {
        return [
            'name' => (string)$this->name,
            'email' => (string)$this->email,
            'content' => (string)$this->content,
            'time' => $this->timeAdded
        ];
    }
}