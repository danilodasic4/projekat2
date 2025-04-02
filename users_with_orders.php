<?php
session_start();
require_once 'pdo.php';
require_once 'Controller.php';

$controller = new Controller($pdo);

if (!$controller->isAdminOrModerator()) {
    echo "Access denied!";
    exit();
}

$usersWithOrders = $controller->getUsersWithOrders();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Users with Orders</title>
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
<h1>Users with Orders</h1>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Total Spent</th>
    </tr>
    <?php foreach ($usersWithOrders as $user){ ?>
        <tr>
            <td><?php echo htmlspecialchars($user['id']); ?></td>
            <td><?php echo htmlspecialchars($user['username']); ?></td>
            <td><?php echo htmlspecialchars($user['total_spent']); ?></td>
        </tr>
    <?php } ?>
</table>
</body>
</html>