<?php 
include 'partials/admin/header.php';
include 'partials/admin/navbar.php';
$article = new Article();
$userId = $_SESSION['user_id'];
$userArticles = $article->articlesByUser($userId);
?>
    <main class="container my-5">
        <h2 class="mb-4">Welcome <?php echo $_SESSION['username']; ?> to Admin Dashboard</h2>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <form class="d-flex align-items-center" method="POST" action="<?php echo baseUrl('create-dummy-articles.php'); ?>">
                <label for="articleCount" class="form-label me-2">Number of Dummy Articles:</label>
                <input style="width: 100px;" type="number" class="form-control" id="articleCount" name="articleCount" value="10" min="1" max="1000">
                <button id="articleCount" class="btn btn-primary ms-2" type="submit"> Create Dummy Articles</button>
            </form>
             <form action="<?php echo baseUrl('reorder-articles.php'); ?>" method="POST">
            <button name="reorder_articles" class="btn btn-warning" type="submit"> Reorder Article ID's</button>
            </form>
            <button id="deleteSelected" class="btn btn-danger">Delete Selected Articles</button>
        </div>
        <!-- Articles Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Published Date</th>
                        <th>Excerpt</th>
                        <th>Edit</th>
                        <th>Delete</th>
                        <th>Ajax delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($userArticles)): ?>
                    <!-- Example Article Row -->
                     <?php foreach($userArticles as $articleItem): ?>
                    <tr>
                        <td><input type="checkbox" class="articleCheckbox" value="<?php echo $articleItem->id; ?>"></td>
                        <td><?php echo $articleItem->id ?></td>
                        <td><?php echo $articleItem->title ?></td>
                        <td><?php echo $_SESSION['username']; ?></td>
                        <td><?php echo formatDate($articleItem->created_at); ?></td>
                        <td>
                            <?php echo $article->getExcerpt($articleItem->content); ?>
                        </td>
                        <td>
                            <a href="edit-article.php?id=<?php echo $articleItem->id; ?>" class="btn btn-sm btn-primary me-1">Edit</a>
                        </td>
                        <td>
                            <form method="POST" action="<?php echo baseUrl('delete-article.php'); ?>" onsubmit="return confirmDelete(<?php echo $articleItem->id; ?>);">
                            <input type="hidden" name="article_id" value="<?php echo $articleItem->id; ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                       <td>
                            <button data-id="<?php echo $articleItem->id; ?>" type="submit" class="btn btn-sm btn-danger delete-single" style="width: auto; white-space: nowrap;">Ajax delete</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
    <script>
        document.getElementById('selectAll').onclick = function() {
            let checkboxes = document.querySelectorAll('.articleCheckbox');
            for (let checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        }

        document.getElementById('deleteSelected').onclick = function() {
            let selectedIds = [];
            let checkboxes = document.querySelectorAll('.articleCheckbox:checked');
            checkboxes.forEach((checkbox) => {
                selectedIds.push(checkbox.value);
            });
            console.log(selectedIds);
            
            if(selectedIds.length === 0) {
                alert('Please select at least one article to delete.');
                return;
            }
            if(confirm('Are you sure you want to delete the selected articles?')) {
                sendDeleteRequest(selectedIds);
            }
        }
        document.querySelectorAll('.delete-single').forEach((button) =>{
            button.onclick = function() {
                let articleId = this.getAttribute('data-id');
                if(confirm('Are you sure you want to delete the selected articles?')) {
                    sendDeleteRequest([articleId]);
                }
            }
        })
        function sendDeleteRequest(articleIds) {
            let xhr = new XMLHttpRequest();
            xhr.open('POST', '<?php echo baseUrl('delete-ajax.php'); ?>', true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.onreadystatechange = function() {
                if(xhr.readyState === 4 && xhr.status === 200) {
                    let response = JSON.parse(xhr.responseText);
                    if(response.success){
                        alert('we did it');
                        location.reload();
                    } else {
                        alert('failed: ' + response.message);
                    }
                }
            }
            xhr.send(JSON.stringify({ article_ids: articleIds }));
        }

    </script>
<?php
include 'partials/admin/footer.php';
?>