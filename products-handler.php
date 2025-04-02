<?php
require_once 'pdo.php';
require_once 'Controller.php';

$controller = new Controller($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $name = $_POST['name'] ?? '';
        $type_id = $_POST['type_id'] ?? '';
        $price = $_POST['price'] ?? '';

        if ($controller->createProduct($name, $type_id, $price)) {
            header('Location: products.php');
            exit();
        } else {
            echo 'Failed to create product. <a href="products.php">Back to Products</a>';
            exit();
        }
    } elseif ($action === 'update') {
        $id = $_POST['id'] ?? '';
        $name = $_POST['name'] ?? '';
        $type_id = $_POST['type_id'] ?? '';
        $price = $_POST['price'] ?? '';

        if ($controller->updateProduct($id, $name, $type_id, $price)) {
            header('Location: products.php');
            exit();
        } else {
            echo 'Failed to update product. <a href="products.php">Back to Products</a>';
            exit();
        }
    } elseif ($action === 'delete') {
        $id = $_POST['id'] ?? '';

        if ($controller->deleteProduct($id)) {
            header('Location: products.php');
            exit();
        } else {
            echo 'Failed to delete product. <a href="products.php">Back to Products</a>';
            exit();
        }
    }elseif ($action === 'logout') {
        $controller->logout();
        header('Location: login.php');
        exit();
    }
}
?>