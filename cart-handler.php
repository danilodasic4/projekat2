<?php
require_once 'pdo.php';
require_once 'Controller.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$controller = new Controller($pdo);

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $data = ['id' => $_POST['id']];

    if($action === 'add') {
            $controller->addProductIntoCart($data);
            exit();
    }elseif ($action === 'remove'){
        $controller->removeProductFromCart($data);
        exit();
    }elseif($action === 'complete'){
        $controller->completeOrder();       
        exit();
    }elseif ($action === 'logout') {
        $controller->logout();
        header('Location: login.php');
        exit();
    }else{
        header('Location: store.php');
            exit();
    }

}
?>