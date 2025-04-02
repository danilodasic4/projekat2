<?php

require_once 'Controller.php';
require_once 'pdo.php';

$controller = new Controller($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'login') {
        $controller->loginUser($_POST);
    } elseif ($_POST['action'] === 'logout') {
        $controller->logout();
    }
}
