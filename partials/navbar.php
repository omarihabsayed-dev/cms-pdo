    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo baseUrl('index.php'); ?>">CMS PDO System</a>
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
                        <a class="nav-link active" aria-current="page" href="<?php echo baseUrl('index.php'); ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo baseUrl('admin.php'); ?>">Admin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo baseUrl('about.php'); ?>">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo baseUrl('contact.php'); ?>">Contact</a>
                    </li>
                    <?php if(!isLoggedIn()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo baseUrl('login.php'); ?>">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo baseUrl('register.php'); ?>">Register</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>