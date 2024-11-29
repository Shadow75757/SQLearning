<header>
    <link rel="stylesheet" href="requirments/header_style.css">
    <h1><strong><a class="headerH1" href="http://172.20.10.2/xampp/SQLearning/">MySQL</a></strong><a class="headerH1" href="http://172.20.10.2/xampp/SQLearning/">earing Hub</a></h1>
    <nav>
        <?php if (isset($_SESSION['user'])): ?>
            <div class="profile-menu">
                <img src="uploads/<?= $_SESSION['user']['profile_image'] ?>" alt="Profile" class="profile-image">
                <div class="dropdown">
                    <a href="profile.php">Profile</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        <?php else: ?>
            <a href="login.php" class="login-btn">Login</a>
        <?php endif; ?>
    </nav>
</header>