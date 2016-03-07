<?php

include('vendor/autoload.php');

$request = $_SERVER['REQUEST_METHOD'];
//header('Content-type: application/json');

$commentRepository = new Gb\Repository\FlatCommentRepository();

if($request == 'GET') {

    // Get comments
    $comments = $commentRepository->getComments();
    echo json_encode($comments);

} else if($request == 'PUT') {

    // Parse the request
    $requestBody = file_get_contents('php://input');
    $requestContent = json_decode($requestBody,true);

    // Bad formatted request
    if(is_null($requestContent)) {
        http_response_code(400);
        echo json_encode(['message' => 'Bad request']);
        die;
    }

    // Build the Vo's entity and save them, in case of any error show a message
    try{

        $name = new Gb\Vo\Name($requestContent['firstName'],$requestContent['lastName']);
        $email = new Gb\Vo\Email($requestContent['email']);
        $messageContent = new Gb\Vo\CommentContent($requestContent['content']);

        $comment = new Gb\Comment\Comment($name, $email, $messageContent);

        $commentRepository->saveComment($comment);

    } catch (\Exception $e) {
        http_response_code(400);
        echo json_encode(['message' => $e->getMessage()]);
        die;
    }

    echo json_encode(['success' => true]);
    die;

} else {

    http_response_code(400);
    echo json_encode(['message' => 'Unsupported method']);
    die;
}