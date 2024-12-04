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
    <title>SQLearning | Login Form</title>
    <link rel="stylesheet" href="login_style.css">
</head>
<?php require_once('../../../includes/header/header.php'); ?>
<style>
    .tooltip {
        position: absolute;
        background-color: #222;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-family: monospace;
        font-size: 14px;
        white-space: nowrap;
        z-index: 1000;
        top: 50%;
        /* Adjust as needed */
        left: 50%;
        transform: translate(-50%, -120%);
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    button:hover .tooltip {
        opacity: 1;
        visibility: visible;
    }
</style>

<body>
    <div class="login-form">
        <h2>Login</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form action="login.php" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button id="login-button" type="submit">Login<span class="tooltip"></span></button>
            <a class="register" href="../register/register.php">Don't have an account?<br><a class="footer__link" style="text-align: center; font-size: 1rem" href="../register/register.php">Register now!</a></a>
        </form>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const usernameInput = document.querySelector('input[name="username"]');
                const passwordInput = document.querySelector('input[name="password"]');
                const loginButton = document.getElementById('login-button');
                const tooltip = loginButton.querySelector('.tooltip');

                loginButton.addEventListener('mouseover', () => {
                    const username = usernameInput.value || '<username>';
                    const password = passwordInput.value ? '********' : '<password>';

                    tooltip.textContent = `SELECT id, password FROM users WHERE username = '${username}' AND password = '${password}'`;
                    tooltip.style.visibility = 'visible';
                    tooltip.style.opacity = '1';
                });

                loginButton.addEventListener('mouseout', () => {
                    tooltip.style.visibility = 'hidden';
                    tooltip.style.opacity = '0';
                });
            });

            function fakeEncryptPassword(password) {
                return password ? btoa(password) : '<encrypted_password>';
            }

            loginButton.addEventListener('mouseover', () => {
                const username = usernameInput.value || '<username>';
                const encryptedPassword = fakeEncryptPassword(passwordInput.value);

                tooltip.textContent = `SELECT id, password FROM users WHERE username = '${username}' AND password = '${encryptedPassword}'`;
                tooltip.style.visibility = 'visible';
                tooltip.style.opacity = '1';
            });
        </script>
    </div>
</body>

<?php require_once('../../../includes/footer/footer.php'); ?>

</html>