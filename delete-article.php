<?php
require_once 'init.php';
checkUserLoggedIn();
if(isPostRequest()) {
    $articleId = getPostData('article_id');
    $article = new Article();
    if($article->deleteWithImage($articleId)) {
        redirect('admin.php');
    } else {
        setFlashMessage('error', 'Failed to delete the article.');
    }
}
?>