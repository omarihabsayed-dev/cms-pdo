<?php 
include 'partials/admin/header.php';
include 'partials/admin/navbar.php';
if(isPostRequest()) {
    $filePath = '';
    $targetDir = 'uploads/';
    if(!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }
    if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $originalName  = $_FILES['image']['name'];
        $imageFileType = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if(in_array($imageFileType, $allowedTypes)) {
            $uniqueName = uniqid('img_', true) . '.' . $imageFileType;
            $targetFile = $targetDir . $uniqueName;
            if(move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $filePath = $targetFile;
            } else {
                echo "<div class='alert alert-danger'>Error uploading the image.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Invalid file type. Only JPG, JPEG, PNG, GIF, and WEBP are allowed.</div>";
        }

    }
    $title = getPostData('title');
    $created_at = getPostData('date');
    $content = getPostData('content');
    $author_id = $_SESSION['user_id'];
    $article = new Article();
    if($article->create($title, $created_at, $content, $author_id, $filePath)) {
        redirect('admin.php');
        exit;
    }

}
?>
    <main class="container my-5">
        <h2>Create New Article</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Article Title *</label>
                <input name="title" type="text" class="form-control" id="title" placeholder="Enter article title" required>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Published Date *</label>
                <input name="date" type="date" class="form-control" id="date" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Content *</label>
                <textarea name="content" class="form-control" id="content" rows="10" placeholder="Enter article content" required></textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Featured Image URL</label>
                <input name="image" type="file" class="form-control" id="image" placeholder="Enter image URL">
            </div>
            <button type="submit" class="btn btn-success">Publish Article</button>
            <a href="admin.php" class="btn btn-secondary ms-2">Cancel</a>
        </form>
    </main>
<?php
include 'partials/admin/footer.php';
?>