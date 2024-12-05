<?php
session_start();

if (!isset($_SESSION['user']['id'])) {
    header('Location: ../login.php');
    exit;
}

require '../db.php';

$userId = $_SESSION['user']['id'];
$stmt = $pdo->prepare("SELECT username, profile_image FROM users WHERE id = :user_id");
$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $oldPassword = $_POST['old_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $verifyPassword = $_POST['verify_password'] ?? '';

    if ($username !== $user['username']) {
        $stmt = $pdo->prepare("UPDATE users SET username = :username WHERE id = :user_id");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();
    }

    if ($newPassword && $newPassword === $verifyPassword) {
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (password_verify($oldPassword, $userData['password'])) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :user_id");
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':user_id', $_SESSION['user_id']);
            $stmt->execute();
        } else {
            $error = "Old password is incorrect!";
        }
    }

    if (isset($_FILES['profile_image'])) {
        $image = $_FILES['profile_image'];
        $imagePath = 'images/' . basename($image['name']);
        move_uploaded_file($image['tmp_name'], $imagePath);

        $stmt = $pdo->prepare("UPDATE users SET profile_image = :profile_image WHERE id = :user_id");
        $stmt->bindParam(':profile_image', $imagePath);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();

        header("Location: profile/ profile.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQLearning | Profile</title>
    <link rel="stylesheet" href="profile.css">
<link rel="stylesheet" href="../styles.css">
</head>
<?php require_once '../includes/header/header.php'; ?>
<body>
    <div class="container">
        <div class="inner-container">
            <div class="left-side">
                <h2>Update Profile</h2>
                <form action="update_profile.php" method="post" enctype="multipart/form-data">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                    <label for="old-password">Old Password:</label>
                    <input type="password" id="old-password" name="old_password" required>
                    <label for="new-password">New Password:</label>
                    <input type="password" id="new-password" name="new_password" required>
                    <label for="confirm-password">Confirm New Password:</label>
                    <input type="password" id="confirm-password" name="confirm_password" required>
                    <button type="submit">Update Profile</button>
                </form>
            </div>
            <div class="right-side">
                <h2>Profile Image</h2>
                <img src="../images/<?= $user['profile_image'] ?: '../images/default.png' ?>" alt="Profile Image" id="profile-image-preview">
                <form action="update_profile.php" method="post" enctype="multipart/form-data">
                    <input type="file" name="profile_image" accept="image/*" onchange="previewImage(event)">
                    <button type="submit">Update Image</button>
                </form>
            </div>
        </div>
    </div>
    <script src="profile.js"></script>
</body>
<?php require_once '../includes/footer/footer.php'; ?>

</html>