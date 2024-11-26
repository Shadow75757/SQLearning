<?php
require 'db.php';
$inventory = $pdo->query("SELECT * FROM inventory")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MYSQLearning</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <h1><strong>MySQL</strong>earing Hub</h1>
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

    <main>
        <div class="inventory">
            <h2>Inventory</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inventory as $item): ?>
                        <tr>
                            <td><?= $item['id'] ?></td>
                            <td><?= $item['item_name'] ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>
                                <button class="edit-btn">
                                    Edit
                                    <div class="tooltip">
                                        <span class="sql-keyword">UPDATE</span> inventory
                                        <span class="sql-keyword">SET</span>
                                        item_name=<span class="user-value">'<?= $item['item_name'] ?>'</span>,
                                        quantity=<span class="user-value"><?= $item['quantity'] ?></span>
                                        <span class="sql-keyword">WHERE</span> id=<span class="user-value"><?= $item['id'] ?></span>
                                    </div>
                                </button>
                                <!-- Edit Modal -->
                                <div id="edit-modal" class="modal">
                                    <div class="modal-content">
                                        <span class="close-btn">&times;</span>
                                        <h2>Edit Item</h2>
                                        <form id="edit-form">
                                            <input type="hidden" id="edit-id" name="id">
                                            <label for="edit-item-name">Item Name:</label>
                                            <input type="text" id="edit-item-name" name="item_name" placeholder="Enter item name" required>

                                            <label for="edit-quantity">Quantity:</label>
                                            <input type="number" id="edit-quantity" name="quantity" placeholder="Enter quantity" required>

                                            <button type="submit" id="update-button">Update Item</button>
                                        </form>
                                    </div>
                                </div>

                                <button class="delete-btn" data-id="<?= $item['id'] ?>">
                                    Delete
                                    <div class="tooltip">
                                        <span class="sql-keyword">DELETE</span>
                                        <span class="sql-keyword">FROM</span> inventory
                                        <span class="sql-keyword">WHERE</span> id=<span class="user-value"><?= $item['id'] ?></span>
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
                        <button style="width:100%" id="add-button" type="button">Add Item</button>
                        <div id="add-tooltip" class="tooltip">
                            <span class="sql-keyword">INSERT</span>
                            <span class="sql-keyword">INTO</span> inventory (<span class="default-value">&lt;itemName&gt;</span>,
                            <span class="default-value">&lt;quantity&gt;</span>)
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <script src="script.js"></script>
    </main>
</body>

</html>