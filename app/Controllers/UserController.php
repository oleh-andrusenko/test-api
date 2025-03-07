<?php

namespace App\Controllers;

use App\Models\User;


class UserController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index()
    {
        http_response_code(200);
        echo json_encode($this->userModel->getAllUsers());
        exit;
    }

    public function show()
    {
        $id = $_GET['id'] ?? null;
        if (!empty($id)) {
            $user = $this->userModel->getUserById($id);
            if ($user) {
                http_response_code(200);
                echo json_encode($user);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'User not found']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Bad request. Missing id parameter']);
        }
        exit;
    }

    public function destroy()
    {
        $id = $_GET['id'] ?? null;
        if (empty($id)) {
            http_response_code(400);
            echo json_encode(['message' => 'Bad request. Missing id parameter']);
        } else {
            if ($this->userModel->getUserById($id)) {
                $this->userModel->deleteUser($id);
                http_response_code(200);
                echo json_encode(['message' => 'User deleted successfully']);
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
            if ($this->userModel->getUserById($id)) {
                $data = json_decode(file_get_contents('php://input'));
                if (!empty($data->name) && !empty($data->email) && !empty($data->password)) {
                    $this->userModel->updateUser($id, $data->name, $data->email, $data->password);
                    http_response_code(200);
                    echo json_encode(['message' => 'User updated successfully']);
                } else {
                    http_response_code(400);
                    echo json_encode(['message' => 'Bad request. Missing fields']);
                }
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'User not found']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Bad request. Missing id parameter']);
        }

    }

    public function store()
    {
        if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Bad request. Missing fields']);
        } else {
            if (!$this->userModel->getUserByEmail($_POST['email'])) {
                if ($this->userModel->createUser($_POST['name'], $_POST['email'], $_POST['password'])) {
                    http_response_code(201);
                    echo json_encode(['message' => 'User successfully created']);
                } else {
                    http_response_code(500);
                    echo json_encode(['message' => 'An error occurred when trying to create user']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'User with this email already exists']);
            }
        }
        exit;
    }
}