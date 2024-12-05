<header>
    <link rel="stylesheet" href="/xampp/SQLearning/includes/header/header_style.css">
    <h1>
        <strong><a class="headerH1" href="/xampp/SQLearning">SQLearning Hub</a></strong>
    </h1>
    <nav>
        <?php
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $current_page = basename($_SERVER['PHP_SELF']);
        ?>
        <?php if (isset($_SESSION['user'])): ?>
            <?php
            $profile_image = isset($_SESSION['user']['profile_image']) ? $_SESSION['user']['profile_image'] : 'default.png';
            $valid_images = ['/xampp/SQLearning/images/default.png', ''];
            if (in_array($profile_image, $valid_images)) {
                $profile_image = '/xampp/SQLearning/images/default.png';
            }
            ?>
            <div class="profile-menu">
                <img src="/xampp/SQLearning/images/<?= $profile_image ?>" alt="Profile" class="profile-image">
                <div class="dropdown">
                    <a href="profile/profile.php"><strong><i class="ri-user-line"></i></strong> | Profile</a>
                    <a href="logout.php"><strong><i class="ri-logout-box-line"></i></strong> | Logout</a>
                </div>
            </div>
        <?php elseif ($current_page !== 'login.php' && $current_page !== 'register.php'): ?>
            <a href="/xampp/SQLearning/includes/authentication/login/login.php" class="login-btn">Login</a>
        <?php endif; ?>
    </nav>
    <script src="/xampp/SQLearning/includes/header/profile_timeout.js"></script>
</header>