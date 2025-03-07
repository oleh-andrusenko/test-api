<?php

namespace App\Models;


use App\Core\Database;


class Post
{
    private  $pdo;
    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }
    public function getAllPosts(){
        $stmt = $this->pdo->query('SELECT id, title, content, user_id FROM posts');
        return $stmt->fetchAll();
    }
    public function getPostById($id)
    {
        $stmt = $this->pdo->prepare('SELECT id, title, content, user_id FROM posts WHERE id=?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    public function createPost($title, $content, $user_id)
    {
        $stmt = $this->pdo->prepare('INSERT INTO posts (title, content, user_id) VALUES (?, ?, ?)');
        return $stmt->execute([$title, $content, $user_id]);
    }
    public function updatePost($id, $title, $content, $user_id)
    {
        $stmt=$this->pdo->prepare('UPDATE posts SET title=?, content=?,user_id=?, updated_at=NOW() WHERE id=?');
        print_r($stmt->execute([$title, $content, $user_id, $id]));
    }
    public function deletePost($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM posts WHERE id=?');
        return $stmt->execute([$id]);
    }
    public function getUserPosts($user_id)
    {
        $stmt = $this->pdo->prepare('SELECT id, title, content FROM posts WHERE user_id=?');
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }
}