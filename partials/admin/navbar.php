    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo baseUrl('index.php'); ?>">CMS PDO System - Admin</a>
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav"
                aria-controls="navbarNav"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
            <div
                class="collapse navbar-collapse"
                id="navbarNav"
            >
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="<?php echo baseUrl('admin.php'); ?>">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo baseUrl('create-article.php'); ?>">Create Article</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo baseUrl('index.php'); ?>">View Site</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo baseUrl('profile.php'); ?>">Profile</a>
                    </li>
                    <form method="POST" action="<?php echo baseUrl('logout.php'); ?>">
                        <button type="submit" class="nav-link">Logout</button>
                    </form>
                </ul>
            </div>
        </div>
    </nav>