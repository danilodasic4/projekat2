<?php
session_start();
require_once 'pdo.php';
require_once 'Controller.php';

$controller = new Controller($pdo);

if (!$controller->isAdminOrModerator()) {
    echo "Access denied!";
    exit();
}

$productTypesWithSpending = $controller->getProductTypesWithSpending();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Types with Spending</title>
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
<h1>Product Types with Spending</h1>
<table border="1">
    <tr>
        <th>Type Name</th>
        <th>Total Spent</th>
    </tr>
    <?php foreach ($productTypesWithSpending as $type){ ?>
        <tr>
            <td><?= $type['type_name'] ?></td>
            <td><?= $type['total_spent'] ?></td>
        </tr>
    <?php } ?>
</table>
</body>
</html>