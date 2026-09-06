<?php
require __DIR__ . '/init.php';
if(isPostRequest()) {
    $article = new Article();
    $articleCount = getPostData('articleCount');
    if($article->generateDummyData($articleCount)) {
        redirect('admin.php');
    } else {
        $error = "Failed to create dummy articles. Please try again.";
    }
}
?>