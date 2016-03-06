<?php

namespace Gb\Repository;


use Gb\Comment\Comment;

interface CommentRepository
{
    public function saveComment(Comment $comment);

    public function getComments();
}