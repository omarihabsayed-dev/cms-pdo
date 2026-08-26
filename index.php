<?php 
require_once 'partials/header.php';
include basePath('partials/navbar.php');
include basePath('partials/hero.php');
$posts = new Article();
$articles = $posts->getAll();
?>
<main class="container my-5">
    <?php if(!empty($articles)): ?>
        <?php foreach($articles as $article): ?>
        <!-- Blog Post 1 -->
        <div class="row mb-4">
            <div class="col-md-4">
                <?php if(!empty($article->image)): ?>
                    <img
                    src="<?php echo htmlspecialchars($article->image); ?>"
                    class="img-fluid"
                    alt="Blog Post Image"
                    >
                <?php else: ?>
                    <img
                    src="https://placehold.co/350x200"
                    class="img-fluid"
                    alt="Blog Post Image"
                    >
               </div>
                <?php endif; ?>
            </div>
            <div class="col-md-8">
                <h2><?php echo htmlspecialchars($article->title); ?></h2>
                <p>
                    <?php echo htmlspecialchars($posts->getExcerpt($article->content)); ?>
                </p>
                <a href="article.php?id=<?php echo $article->id; ?>" class="btn btn-primary">Read More</a>
            </div>
        </div>
    <?php endforeach; ?>
    <?php else: ?>
        <p>No articles found.</p>
    <?php endif; ?>
</main>
<?php 
include 'partials/footer.php';
?>
