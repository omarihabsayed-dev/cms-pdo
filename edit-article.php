<?php 
include 'partials/admin/header.php';
include 'partials/admin/navbar.php';
$articleId = $_GET['id'] ? (int)$_GET['id'] : NULL;
$article = new Article();
if(isPostRequest()) {
    $imagePath = $article->uploadImage($_FILES['image']);
    $title = getPostData('title');
    $created_at = getPostData('date');
    $content = getPostData('content');
    $author_id = $_SESSION['user_id'];
    if(strpos($imagePath, 'error') === false) {
        if($article->update($articleId, $title, $created_at, $content, $author_id, $imagePath)) {
            redirect('admin.php');
            exit;
        }
    }
}
$articleData = $article->getArticleById($articleId);
?>
    <main class="container my-5">
        <h2>Update Article</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Article Title *</label>
                <input name="title" type="text" value="<?php echo $articleData->title; ?>" class="form-control" id="title" placeholder="Enter article title" required>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Published Date *</label>
                <input name="date" type="date" value="<?php echo date('Y-m-d', strtotime($articleData->created_at)); ?>" class="form-control" id="date" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Content *</label>
                <textarea name="content" class="form-control" id="content" rows="10" placeholder="Enter article content" required><?php echo $articleData->content; ?></textarea>
            </div>
            <?php if (!empty($articleData->image)): ?>
            <div class="mb-3">
                <label for="image" class="form-label">Current Featured Image</label><br>
                <img src="<?php echo $articleData->image; ?>" alt="Featured Image" class="img-fluid mb-2" style="max-width: 150px;">
            </div>
            <?php endif; ?>
            <div class="mb-3">
                <label for="image" class="form-label">Featured Image URL</label>
                <input name="image" type="file" class="form-control" id="image" placeholder="Enter image URL">
            </div>
            <button type="submit" class="btn btn-success">Update Article</button>
            <a href="admin.php" class="btn btn-secondary ms-2">Cancel</a>
        </form>
    </main>
<?php
include 'partials/admin/footer.php';
?>