<?php
require_once 'pdo.php';
require_once 'Controller.php';

$controller = new Controller($pdo);

if (!$controller->isAdminOrModerator()) {
    header('Location: index.php');
    exit();
}

$products = $controller->getProducts();
$productTypes = $controller->getProductTypes();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Products</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<h1>Manage Products</h1>
<h2>Create New Product</h2>
<form action="products-handler.php" method="POST">
    <input type="hidden" name="action" value="create">
    Name: <input type="text" name="name" required><br>
    Type: 
    <select name="type_id" required>
        <?php foreach ($productTypes as $type) { ?>
            <option value="<?= $type['id'] ?>"><?= $type['name'] ?></option>
        <?php } ?>
    </select><br>
    Price: <input type="number" step="0.01" name="price" required><br>
    <input type="submit" value="Create Product">
</form>
<h2>Current Products</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Type</th>
        <th>Price</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($products as $product) { ?>
        <tr>
            <td><?= $product['id'] ?></td>
            <td><?= $product['name'] ?></td>
            <td><?= $product['type_name'] ?></td>
            <td><?= $product['price'] ?></td>
            <td>
                <form action="products-handler.php" method="POST" style="display:inline-block;">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?= $product['id'] ?>">
                    Name: <input type="text" name="name" value="<?= $product['name'] ?>" required><br>
                    Type: 
                    <select name="type_id" required>
                        <?php foreach ($productTypes as $type) { ?>
                            <option value="<?= $type['id'] ?>" <?= $type['id'] == $product['type_id'] ? 'selected' : '' ?>><?= $type['name'] ?></option>
                        <?php } ?>
                    </select><br>
                    Price: <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required><br>
                    <input type="submit" value="Update">
                </form>
                <form action="products-handler.php" method="POST" style="display:inline-block;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $product['id'] ?>">
                    <input type="submit" value="Delete" onclick="return confirm('Are you sure,think twice?')">
                </form>
            </td>
        </tr>
    <?php } ?>
</table>
If you want to logout,press button under
<form action="products-handler.php" method="POST">
    <input type="hidden" name="action" value="logout">
    <input type="submit" value="Logout">
</form>
</body>
</html>