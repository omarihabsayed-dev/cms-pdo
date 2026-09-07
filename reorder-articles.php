<?php
require __DIR__ . '/init.php';
if(isPostRequest()) {
    if(isset($_POST['reorder_articles'])) {
        $article = new Article();
        try {
            $article->reorderArticles();
            redirect('admin.php');
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        redirect('admin.php');
    }
}
?>