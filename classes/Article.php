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
            if($article->user_id == $_SESSION['user_id']) {
                return $article;
            } else {
                redirect('admin.php');
            }
        } else {
            redirect('admin.php');
        }
    }

    public function deleteWithImage($id) {
        $article = $this->getArticleById($id);
        if($article) {
            if($article->user_id == $_SESSION['user_id']) {
                if(!empty($article->image) && file_exists($article->image)) {
                if(!unlink($article->image)) {
                    return false;
                }
            }
            $query = "DELETE FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } else {
            redirect('admin.php');
        }
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

    public function create($title, $created_at, $content, $author_id, $imagePath) {
        $query = "INSERT INTO " . $this->table . " (title, created_at, content, user_id, image) VALUES(:title, :created_at, :content, :author_id, :image)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':created_at', $created_at);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':author_id', $author_id);
        $stmt->bindParam(':image', $imagePath);
        return $stmt->execute();
    }

    public function update($articleId, $title, $created_at, $content, $author_id, $imagePath = null) {
        $query = "UPDATE " . $this->table . " SET title = :title, created_at = :created_at, content = :content, user_id = :author_id";
        if($imagePath) {
            $query .= ", image = :imagePath";
        }
        $query .= " WHERE id = :articleId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':created_at', $created_at);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':author_id', $author_id, PDO::PARAM_INT);
        $stmt->bindParam(':articleId', $articleId, PDO::PARAM_INT);
        if($imagePath) {
            $stmt->bindParam(':imagePath', $imagePath, PDO::PARAM_STR);
        }
        return $stmt->execute();
    }

    public function uploadImage($file) {
    $targetDir = 'uploads/';
    if(!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }
    if(isset($file) && $file['error'] === UPLOAD_ERR_OK) {
        $originalName  = $file['name'];
        $imageFileType = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if(in_array($imageFileType, $allowedTypes)) {
            $uniqueName = uniqid('img_', true) . '.' . $imageFileType;
            $targetFile = $targetDir . $uniqueName;
            if(move_uploaded_file($file['tmp_name'], $targetFile)) {
                return $targetFile;
            } else {
                return "there was an error uploading the file.";
            }
        } else {
            return "Invalid file type. Only JPG, JPEG, PNG, GIF, and WEBP are allowed.";
        }

    }
    return '';
    }
}   
?>