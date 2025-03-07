<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Controllers\CommentController;
use App\Controllers\PostController;
use App\Controllers\UserController;

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;

$userController = new UserController();
$postController = new PostController();
$commentController = new CommentController();


if ($method == 'GET') {
    switch ($action) {
        case 'getUsers':
            $userController->index();
            break;
        case 'getPosts':
            $postController->index();
            break;
        case 'getComments':
            $commentController->index();
            break;
        case 'getCommentsCount':
            $commentController->commentsCount();
            break;
        case 'getUser':
            $userController->show();
            break;
        case 'getPost':
            $postController->show();
            break;
        case 'getUserPosts':
            $postController->userPosts();
            break;
        case 'getPostComments':
            $commentController->postWithComments();
            break;
        default:
            http_response_code(404);
            echo json_encode(['message' => 'Page not found.']);
    }
} else {
    if ($method == 'POST') {
        switch ($action) {
            case 'createUser':
                $userController->store();
                break;
            case 'createPost':
                $postController->store();
                break;
            case 'createComment':
                $commentController->store();
                break;
            default:
                http_response_code(404);
                echo json_encode(['message' => 'Page not found.']);
        }
    }
}
