<?php
require_once 'pdo.php';
require_once 'Controller.php';

$controller = new Controller($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? '';

        if ($controller->createUser($username, $password, $role)) {
            header('Location: admin.php');
            exit(); // Dodato je exit() da se prekine dalji tok skripte
        } else {
            echo 'Failed to create user. <a href="admin.php">Back to Admin</a>';
            exit();
        }
    } elseif ($action === 'edit') {
        $id = $_POST['id'] ?? '';
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? '';

        if ($controller->updateUser($id, $username, $password, $role)) {
            header('Location: admin.php');
            exit();
        } else {
            echo 'Failed to update user. <a href="admin.php">Back to Admin</a>';
            exit();
        }
    } elseif ($action === 'delete') {
        $id = $_POST['id'] ?? '';

        if ($controller->deleteUser($id)) {
            header('Location: admin.php');
            exit();
        } else {
            echo 'Failed to delete user. <a href="admin.php">Back to Admin</a>';
            exit();
        }
    } elseif ($action === 'logout') {
        $controller->logout();
        header('Location: login.php');
        exit();
    }
}
?>