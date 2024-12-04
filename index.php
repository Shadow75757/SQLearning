<?php
require 'db.php';

$userLoggedIn = isset($_SESSION['user_id']) ? true : false;

if ($userLoggedIn) {
    $stmt = $pdo->prepare("SELECT profile_image FROM users WHERE id = :user_id");
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $profileImage = $user['profile_image'];
} else {
    $profileImage = null;
}

$inventory = $pdo->query("SELECT * FROM inventory")->fetchAll(PDO::FETCH_ASSOC);
?>


<script>
    <?php if (isset($_SESSION['user'])): ?>
        const username = "<?php echo $_SESSION['user']['username']; ?>";
        console.log("Logged in as: " + username);
    <?php else: ?>
        console.log("Not logged in");
    <?php endif; ?>
</script>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MYSQLearning | Home</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://kit.fontawesome.com/076868c758.js" crossorigin="anonymous"></script>
</head>
<?php require_once 'includes/header/header.php'; ?>

<body>

    <main>
        <div class="inventory">
            <h2>Inventory</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Updated by</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inventory as $item): ?>
                        <tr>
                            <td><?= $item['id'] ?></td>
                            <td><?= $item['item_name'] ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td><?= $item['inserted_by'] ?></td>
                            <td><?= $item['added_at'] ?></td>
                            <td>
                                <button class="edit-btn">Edit</button>
                                <div id="edit-modal" class="modal">
                                    <div class="modal-content">
                                        <span class="close-btn">&times;</span>
                                        <h2>Edit Item</h2>
                                        <br>
                                        <form id="edit-form">
                                            <input type="hidden" id="edit-id" name="id">
                                            <label for="edit-item-name">Item Name:</label>
                                            <input type="text" id="edit-item-name" name="item_name" placeholder="Enter item name" required>

                                            <label for="edit-quantity">Quantity:</label>
                                            <input type="number" id="edit-quantity" name="quantity" placeholder="Enter quantity" required>

                                            <button type="submit" id="update-button">
                                                Update Item
                                                <div style="top: 45px; left: 50%" class="tooltip">
                                                    <span class="sql-keyword">UPDATE</span> inventory
                                                    <span class="sql-keyword">SET</span>
                                                    item_name=<span class="user-value">'Item Name'</span>,
                                                    quantity=<span class="user-value">Quantity</span>
                                                    <span class="sql-keyword">WHERE</span> id=<span class="user-value">ID</span>
                                                </div>
                                            </button>

                                        </form>
                                    </div>
                                </div>
                                <button class="delete-btn" data-id="<?= $item['id'] ?>">
                                    Delete
                                    <div class="tooltip">
                                        <i class="fa-solid fa-trash" style="vertical-align: middle"></i> |
                                        <span style="vertical-align: middle" class="sql-keyword">DELETE</span>
                                        <span style="vertical-align: middle" class="sql-keyword">FROM</span> <a style="vertical-align: middle">inventory</a>
                                        <span style="vertical-align: middle" class="sql-keyword">WHERE</span> <a style="vertical-align: middle">id=</a><span style="vertical-align: middle" class="user-value"><?= $item['id'] ?></span>
                                    </div>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="add-item">
            <h2>Add Item</h2>
            <div class="form-container">
                <form class="add-item-form" id="add-item-form">
                    <label for="item-name">Item Name:</label>
                    <input type="text" id="item-name" placeholder="Enter item name">

                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" placeholder="Enter quantity">

                    <div id="add-button-container">
                        <button style="width: 100%" id="add-button" type="button">
                            Add Item
                        </button>
                        <div class="tooltip-additem">
                            <span class="sql-keyword">INSERT</span>
                            <span class="sql-keyword">INTO</span> inventory (<span class="default-value">&lt;itemName&gt;</span>,
                            <span class="default-value">&lt;quantity&gt;</span>)
                        </div>
                    </div>

                </form>
            </div>
        </div>
        <script src="crud.js"></script>
    </main>
    <?php require_once 'includes/footer/footer.php'; ?>
</body>

</html>