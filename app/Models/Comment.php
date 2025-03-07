<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Comment
{
    private  $pdo;
    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }
    public function getAllComments()
    {
        $stmt = $this->pdo->prepare("SELECT user_id, email, name, post_id, content FROM comments LEFT JOIN users ON comments.user_id = users.id");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getPostComments($id)
    {
        $stmt = $this->pdo->prepare("SELECT email, name, content FROM comments LEFT JOIN users ON comments.user_id = users.id WHERE post_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchAll();
    }
    public function getCommentById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM comments WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    public function deleteComment($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM comments WHERE id = ?");
        return $stmt->execute([$id]);
    }
    public function updateComment($id, $content, $user_id, $post_id)
    {
        $stmt = $this->pdo->prepare("UPDATE comments SET content = ?, user_id = ?, post_id = ? WHERE id = ?");
        return $stmt->execute([$content, $user_id, $post_id, $id]);
    }
    public function getCommentsCount()
    {
        $stmt = $this->pdo->prepare("SELECT user_id, email, name, COUNT(user_id) as 'сomments' FROM comments LEFT JOIN users ON users.id = comments.user_id GROUP BY user_id");
        $stmt->execute();
        return $stmt->fetchAll();
}
    public function createComment($content, $post_id, $user_id)
    {
        $stmt = $this->pdo->prepare("INSERT INTO comments (content, post_id, user_id) VALUES (?, ?, ?)");
        return $stmt->execute([$content, $post_id, $user_id]);
}

}