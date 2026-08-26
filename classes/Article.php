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

    public function formatCreatedAt($date) {
        return date('F j, Y', strtotime($date));
    }
}
?>