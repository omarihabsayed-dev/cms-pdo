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
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :userId";
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

    public function generateDummyData($count = 10) {
        $query = "INSERT INTO " . $this->table . " (title, content, user_id, created_at) VALUES (:title, :content, :user_id, :created_at)";
        $stmt = $this->conn->prepare($query);
        $dummyTitles = [
            "The Future of Technology",
            "Exploring the Depths of the Ocean",
            "The Art of Mindfulness",
            "A Journey Through Time",
            "The Secrets of the Universe",
            "The Power of Positive Thinking",
            "The Wonders of Space Exploration",
            "The Beauty of Nature",
            "The Evolution of Music",
            "The Impact of Social Media"
        ];
        $dummyContents = [
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
            "Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.",
            "Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.",
            "Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
            "Curabitur pretium tincidunt lacus. Nulla gravida orci a odio. Nullam varius, turpis et commodo pharetra.",
            "Donec sodales sagittis magna. Sed consequat, leo eget bibendum sodales, augue velit cursus nunc.",
            "Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.",
            "Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper.",
            "Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra.",
            "Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi."
        ];
        $userId = $_SESSION['user_id'];
        $createdAt = date('Y-m-d');
        for($i = 0; $i < $count; $i++) {
            $title = $dummyTitles[array_rand($dummyTitles)];
            $stmt->bindParam(':title', $title);
            $content = $dummyContents[array_rand($dummyContents)];
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':created_at', $createdAt);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
        }
        return true;
    }

    public function reorderArticles() {
        try {

            $this->conn->beginTransaction();
            $query = "SELECT id FROM " . $this->table . " ORDER BY id ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $articles = $stmt->fetchAll(PDO::FETCH_OBJ);
            $tempId = 1000000;

            foreach($articles as $article) {
                $updateQuery = "  UPDATE " . $this->table . " SET id = :tempId WHERE id = :currentId";
                $updateStmt = $this->conn->prepare($updateQuery);
                $updateStmt->bindParam(':tempId', $tempId, PDO::PARAM_INT);
                $updateStmt->bindParam(':currentId', $article->id, PDO::PARAM_INT);
                $updateStmt->execute();
                $tempId++;
            }
            $newId = 1;
            $tempId = 1000000;
            foreach($articles as $article) {
                $updateQuery = "UPDATE " . $this->table . " SET id = :newId WHERE id = :tempId";
                $updateStmt = $this->conn->prepare($updateQuery);
                $updateStmt->bindParam(':newId', $newId, PDO::PARAM_INT);
                $updateStmt->bindParam(':tempId', $tempId, PDO::PARAM_INT);
                $updateStmt->execute();
                $newId++;
                $tempId++;
            }
            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            throw $e;
        }
    }

    public function deleteMultiple($ids) {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $query = "DELETE FROM " . $this->table . " WHERE id IN ($placeholders)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($ids);
    }
}   
?>