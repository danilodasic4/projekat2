<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'pdo.php';  
require_once 'DbProducts.php';
require_once 'Controller.php';

$controller = new Controller($pdo); 

if (!$controller->isAdmin()) {
    echo "Access denied!";
    exit();
}

$logs = $controller->getLogs();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Logs</title>
</head>
<body>
<h1>Administrator Logs</h1>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Log</th>
        <th>Created At</th>
    </tr>
    <?php foreach ($logs as $log) { ?>
        <tr>
            <td><?= $log['id'] ?></td>
            <td><?= $log['log'] ?></td>
            <td><?= $log['created_at'] ?></td>
        </tr>
    <?php } ?>
</table>
</body>
</html>