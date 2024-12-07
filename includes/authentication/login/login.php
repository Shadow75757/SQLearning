<?php
require '../../../db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = ['id' => $user['id'], 'username' => $username];
                header("Location: /xampp/SQLearning/");
                exit;
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Please enter your username and password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQLearning | Login</title>
    <link rel="stylesheet" href="login_style.css">
</head>
<?php require_once('../../../includes/header/header.php'); ?>

<body>
    <div class="login-form">
        <h2>Login</h2>
        <?php if (!empty($error)): ?>
            <div class="message-box error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="login.php" method="post" autocomplete="off">
            <input type="text" name="username" placeholder="Username">
            <input type="password" name="password" placeholder="Password">
            <button id="login-button" type="submit">Login<span class="tooltip"></span></button>
            <a class="register" href="../register/register.php">Don't have an account?<br><a class="footer__link" style="text-align: center; font-size: 1rem" href="../register/register.php">Register now!</a></a>
        </form>
    </div>
</body>
<script src="tooltip.js"></script>
<?php require_once('../../../includes/footer/footer.php'); ?>
<style>
    .footer {
        position: absolute !important;
    }
</style>

</html>