<?php
require_once 'pdo.php';
require_once 'Controller.php';

$controller = new Controller($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $name = $_POST['name'] ?? ''; // Lakse od ternarnog mnogo,makar meni
        //Uvedeno od PHP 7 to znam
        if ($controller->createProductType($name)) {
            header('Location: product-types.php');
            exit();
        } else {
            echo 'Failed to create product type. <a href="product-types.php">Back to Product Types</a>';
            exit();
        }
    } elseif ($action === 'update') {
        $id = $_POST['id'] ?? '';
        $name = $_POST['name'] ?? '';

        if ($controller->updateProductType($id, $name)) {
            header('Location: product-types.php');
            exit();
        } else {
            echo 'Failed to update product type. <a href="product-types.php">Back to Product Types</a>';
            exit();
        }
    } elseif ($action === 'delete') {
        $id = $_POST['id'] ?? '';

        if ($controller->deleteProductType($id)) {
            header('Location: product-types.php');
            exit();
        } else {
            echo 'Failed to delete product type. <a href="product-types.php">Back to Product Types</a>';
            exit();
        }
    }elseif($action === 'logout'){
        $controller->logout();
        header('Location:login.php');
        exit();
    }
}
?>