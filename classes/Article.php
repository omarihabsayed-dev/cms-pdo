<?php
class Article {
    private $conn;
    private $table = "articles";
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getExcerpt($content, $length = 100) {
        if(strlen($content) > $length) {
            return trim(substr($content, 0, $length)) . '...';
        }
        return $content;
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getArticleById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $article = $stmt->fetch(PDO::FETCH_OBJ);
        if($article) {
            return $article;
        } else {
            return false;
        }
    }

    public function articlesByUser($userId) {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :userId ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getArticleWithOwnerById($id) {
        $query = "SELECT articles.id, articles.title, articles.content, articles.created_at, articles.image, users.username AS author, users.email AS author_email FROM " . $this->table . " JOIN users ON articles.user_id = users.id WHERE articles.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $article = $stmt->fetch(PDO::FETCH_OBJ);
        if($article) {
            return $article;
        } else {
            return false;
        }
    }

    public function create($title, $created_at, $content, $author_id, $filePath) {
        $query = "INSERT INTO " . $this->table . " (title, created_at, content, user_id, image) VALUES(:title, :created_at, :content, :author_id, :image)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':created_at', $created_at);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':author_id', $author_id);
        $stmt->bindParam(':image', $filePath);
        return $stmt->execute();
    }
}   
?>