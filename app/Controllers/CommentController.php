<?php

namespace App\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

class CommentController
{
    private Comment $commentModel;

    public function __construct()
    {
        $this->commentModel = new Comment();
    }

    public function index()
    {
        http_response_code(200);
        echo json_encode($this->commentModel->getAllComments());
        exit;
    }

    public function show()
    {
        $id = $_GET['id'] ?? null;
        if (!empty($id)) {
            $comment = $this->commentModel->getCommentById($id);
            if (!empty($comment)) {
                http_response_code(200);
                echo json_encode($comment);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Comment not found"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(['message' => "Bad request. Parameter id not set"]);
        }
        exit;

    }

    public function destroy()
    {
        $id = $_GET['id'] ?? null;
        if (empty($id)) {
            http_response_code(400);
            echo json_encode(['message' => 'Bad request. Parameter id not set']);
        } else {
            if ($this->commentModel->getCommentById($id)) {
                $this->commentModel->deleteComment($id);
                http_response_code(200);
                echo json_encode(['message' => 'Comment deleted successfully']);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Comment not found']);
            }
        }
        exit;
    }

    public function update()
    {
        $id = $_GET['id'] ?? null;
        if (!empty($id)) {
            if ($this->commentModel->getCommentById($id)) {
                $data = json_decode(file_get_contents("php://input"));
                if (!empty($data->content) && !empty($data->user_id) && !empty($data->post_id)) {
                    $this->commentModel->updateComment($id, $data->content, $data->user_id, $data->post_id);
                    http_response_code(200);
                    echo json_encode(['message' => 'Comment updated successfully']);
                } else {
                    http_response_code(400);
                    echo json_encode(['message' => "Bad request. Missing fields"]);
                }
            } else {
                http_response_code(404);
                echo json_encode(['message' => "Comment not found"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(['message' => "Bad request. Missing id parameter"]);
        }
        exit;
    }

    public function postWithComments()
    {
        $id = $_GET['id'];
        if (!empty($id)) {
            http_response_code(200);
            echo json_encode($this->commentModel->getPostComments($id));
            exit;
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Bad request. Missing id parameter']);
        }
        exit;

    }

    public function commentsCount()
    {
        http_response_code(200);
        echo json_encode($this->commentModel->getCommentsCount());
        exit;
    }

    public function store()
    {
        if (empty($_POST['content']) || empty($_POST['post_id']) || empty($_POST['user_id'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Bad request. Missing fields']);
        } else {
            $post = new Post();
            if ($post->getPostById($_POST['post_id'])) {
                $user = new User();
                if ($user->getUserById($_POST['user_id'])) {
                    if ($this->commentModel->createComment($_POST['content'], $_POST['post_id'], $_POST['user_id'])) {
                        http_response_code(201);
                        echo json_encode(['message' => 'Comment created']);
                    } else {
                        http_response_code(500);
                        echo json_encode(['message' => 'An error occurred while creating comment']);
                    }
                } else {
                    http_response_code(404);
                    echo json_encode(['message' => "User not found"]);
                }

            } else {
                http_response_code(404);
                echo json_encode(['message' => "Post not found"]);
            }

        }
        exit;
    }
}