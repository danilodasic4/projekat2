<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'pdo.php';
require_once 'Controller.php';

$controller = new Controller($pdo);

if (!$controller->isAdminOrModerator()) {
    echo "Access denied!";
    exit();
}

$orders = $controller->getOrders();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Orders</title>
</head>
<body>
<h1>Orders</h1>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Customer Name</th>
        <th>Total Price</th>
        <th>Created At</th>
    </tr>
    <?php foreach ($orders as $order){ ?>
        <tr>
            <td><?= htmlspecialchars($order['id']) ?></td>
            <td><?= htmlspecialchars($order['customer_name']) ?></td>
            <td><?= htmlspecialchars($order['total_price']) ?></td>
            <td><?= htmlspecialchars($order['created_at']) ?></td>
        </tr>
    <?php } ?>
</table>
</body>
</html>