<?php
require_once 'pdo.php';
require_once 'Controller.php';

$controller = new Controller($pdo);

if (!$controller->isAdminOrModerator()) {
    header('Location: index.php');
    exit();
}

$productTypes = $controller->getProductTypes();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Product Types</title>
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
<h1>Manage Product Types</h1>
<h2>Create New Product Type</h2>
<form action="product-types-handler.php" method="POST">
    <input type="hidden" name="action" value="create">
    Name: <input type="text" name="name" required><br>
    <input type="submit" value="Create Product Type">
</form>
<h2>Current Product Types</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($productTypes as $type) { ?>
        <tr>
            <td><?= $type['id'] ?></td>
            <td><?= $type['name'] ?></td>
            <td>
                <form action="product-types-handler.php" method="POST" style="display:inline-block;">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?= $type['id'] ?>">
                    Name: <input type="text" name="name" value="<?= $type['name'] ?>" required>
                    <input type="submit" value="Update">
                </form>
                <form action="product-types-handler.php" method="POST" style="display:inline-block;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $type['id'] ?>">
                    <input type="submit" value="Delete" onclick="return confirm('Are you sure,think about it again?')">
                </form>
            </td>
        </tr>
    <?php } ?>
</table>
If you want to logout,press button under
<form action="product-types-handler.php" method="POST">
    <input type="hidden" name="action" value="logout">
    <input type="submit" value="Logout">
</form>
</body>
</html>