<?php
require_once 'init.php';
checkUserLoggedIn();
if(isPostRequest()) {
    $count = getPostData('article_id');
    $article = new Article();
    if($article->deleteWithImage($count)) {
        redirect('admin.php');
    } else {
        $error = "Failed to delete the article. Please try again.";
    }
}
?>