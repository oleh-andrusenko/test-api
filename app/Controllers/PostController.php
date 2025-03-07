<?php

namespace App\Controllers;


use App\Models\Post;
use App\Models\User;

class PostController
{
    private Post $postModel;

    public function __construct()
    {
        $this->postModel = new Post();
    }

    public function index()
    {
        http_response_code(200);
        echo json_encode($this->postModel->getAllPosts());
        exit;
    }

    public function store()
    {
        if (empty($_POST['title']) || empty($_POST['content']) || empty($_POST['user_id'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Bad request. Missing fields']);
        } else {
            $user = new User();
            if ($user->getUserById($_POST['user_id'])) {
                if ($this->postModel->createPost($_POST['title'], $_POST['content'], $_POST['user_id'])) {
                    http_response_code(201);
                    echo json_encode(['message' => 'Post successfully created']);
                } else {
                    http_response_code(500);
                    echo json_encode(['message' => 'Error occurred while creating the post']);
                }
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'User not found']);
            }

        }
        exit;
    }

    public function update()
    {
        $id = $_GET['id'] ?? null;
        if (!empty($id)) {
            if ($this->postModel->getPostById($id)) {
                $data = json_decode(file_get_contents('php://input'));
                if (!empty($data->title) && !empty($data->content) && !empty($data->user_id)) {
                    $user = new User();
                    if ($user->getUserById($data->user_id)) {
                        $this->postModel->updatePost($id, $data->title, $data->content, $data->user_id);
                        http_response_code(200);
                        echo json_encode(['message' => 'Post successfully updated']);
                    } else {
                        http_response_code(404);
                        echo json_encode(['message' => 'User not found']);
                    }

                } else {
                    http_response_code(400);
                    echo json_encode(['message' => 'Bad request. Missing fields']);
                }
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Post not found']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Bad request. Parameter id not set']);
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
            if ($this->postModel->getPostById($id)) {
                $this->postModel->deletePost($id);
                http_response_code(200);
                echo json_encode(['message' => 'Post deleted successfully']);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Post not found']);
            }
        }
        exit;
    }

    public function userPosts()
    {
        $id = $_GET['id'] ?? null;
        if (!empty($id)) {
            $user = new User();
            if ($user->getUserById($id)) {
                $userPosts = $this->postModel->getUserPosts($id);
                http_response_code(200);
                echo json_encode($userPosts);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'User not found']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Bad request. Parameter id not set']);
        }
        exit;
    }

    public function show()
    {
        $id = $_GET['id'] ?? null;
        if (!empty($id)) {
            $post = $this->postModel->getPostById($id);
            if ($post) {
                http_response_code(200);
                echo json_encode($post);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Post not found']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Bad request. Parameter id not set']);
        }
    }
}