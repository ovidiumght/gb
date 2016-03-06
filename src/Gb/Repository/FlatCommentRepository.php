<?php

namespace Gb\Repository;


use Gb\Comment\Comment;

// All this 3rd party code is separated behind an interface
class FlatCommentRepository implements CommentRepository
{
    protected $flatFileRepo;

    public function __construct()
    {
        $config = new \JamesMoss\Flywheel\Config('/var/www/gb/data/');
        $this->flatFileRepo = new \JamesMoss\Flywheel\Repository('comments', $config);
    }

    public function saveComment(Comment $comment)
    {
        $comment = new \JamesMoss\Flywheel\Document($comment->toArray());
        $this->flatFileRepo->store($comment);
    }

    public function getComments()
    {
        $response = [];

        $comments = $this->flatFileRepo->query()->execute();

        foreach($comments as $comment) {
            var_dump($comment->email);
            $formatted['name'] = $comment->name;
            $formatted['email'] = $comment->email;
            $formatted['content'] = $comment->content;

            $response[] = $formatted;
        }

        return $response;
    }

}