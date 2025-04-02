<?php
session_start();
require_once 'pdo.php';
require_once 'Controller.php';

$controller = new Controller($pdo);

if (!$controller->isAdminOrModerator()) {
    echo "Access denied!";
    exit();
}

$productsWithSpending = $controller->getProductsWithSpending();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products with Spending</title>
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
<h1>Products with Spending</h1>
<table border="1">
    <tr>
        <th>Product Name</th>
        <th>Total Spent</th>
    </tr>
    <?php
    if (empty($productsWithSpending)) {
        echo '<tr><td colspan="2">No data available.</td></tr>';
    } else {
        foreach ($productsWithSpending as $product) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($product['product_name']) . '</td>';
            echo '<td>' . htmlspecialchars($product['total_spent']) . '</td>';
            echo '</tr>';
        }
    }
    ?>
</table>
</body>
</html>