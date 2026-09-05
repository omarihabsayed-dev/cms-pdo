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
            <div class="card mb-4 shadow-sm">
                <div class="row g-0 align-items-center">
                    <div class="col-md-4">
                        <?php if(!empty($article->image)): ?>
                            <img
                            src="<?php echo htmlspecialchars($article->image); ?>"
                            class="img-fluid rounded-start w-100"
                            alt="Blog Post Image"
                            style="height: 220px; object-fit: cover;"
                            >
                        <?php else: ?>
                            <img
                            src="https://placehold.co/350x200"
                            class="img-fluid rounded-start w-100"
                            alt="Blog Post Image"
                            style="height: 220px; object-fit: cover;"
                            >
                        <?php endif; ?>
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h3 class="card-title h4 mb-2"><?php echo htmlspecialchars($article->title); ?></h3>
                            <p class="card-text text-muted mb-3">
                                <?php echo htmlspecialchars($posts->getExcerpt($article->content)); ?>
                            </p>
                            <a href="article.php?id=<?php echo $article->id; ?>" class="btn btn-primary btn-sm">Read More</a>
                        </div>
                    </div>
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
