<?php
require_once '../../../db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $error = "Username already taken.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $pdo->prepare("INSERT INTO users (username, password, profile_image, created_at) 
                                   VALUES (:username, :password, 'default.png', NOW())");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);

            if ($stmt->execute()) {
                header("Location: /xampp/SQLearning/");
                exit;
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register_style.css">
    <title>SQLearning | Register</title>
</head>

<?php require_once('../../../includes/header/header.php'); ?>

<body>
    <div class="login-form">
        <h2>Register</h2>
        <?php if ($error): ?>
            <div class="message-box error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST" action="register.php" autocomplete="off">
            <div class="textbox">
                <input type="text" id="username" name="username" placeholder="Enter username" required title="Enter a unique username (e.g., john_doe)">
            </div>
            <div class="textbox">
                <input type="password" id="password" name="password" placeholder="Enter password" required title="Password should be at least 8 characters, with one uppercase letter and one number or symbol.">
            </div>
            <div class="textbox">
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password" required title="Re-enter your password to confirm it matches.">
            </div>

            <button class="btn" type="submit">Register<span class="tooltip"></span></button>

            <a class="register" href="../login/login.php">Already have an account?<br><a class="footer__link" style="text-align: center; font-size: 1rem" href="../login/login.php">Login now!</a></a>

        </form>
    </div>
    <script src="tooltip.js"></script>
</body>

<?php require_once('../../../includes/footer/footer.php'); ?>

</html>