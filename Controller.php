<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'DbUsers.php';
require_once 'DbProducts.php';

class Controller {
    private DbUsers $usersDb;
    private DbProducts $productsDb;

    function __construct($pdo) {
        $this->usersDb = new DbUsers($pdo);
        $this->productsDb = new DbProducts($pdo);
    }

    function loginUser($userData) {
        if (isset($userData['username']) && isset($userData['password']) 
            && $user = $this->usersDb->getDbUser($userData['username'], $userData['password'])) {
            $_SESSION['logged'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            header('Location: index.php');
            exit();
        } else {
            header('Location: login.php?error=1');
            exit();
        }
    }

    function isLogged() {
        return isset($_SESSION['logged']);
    }

    function getRole(){
        return $_SESSION['role'] ?? 'guest'; //Noviji ternarni
    }

    function isAdmin(){
        return $this->isLogged() && $_SESSION['role'] == 'administrator'; 
    }

    function isAdminOrModerator(){
        return $this->isLogged() && in_array($_SESSION['role'], ['administrator', 'moderator']); 
    }

    function logout(){
        session_destroy();
        header('Location: login.php');
        exit();
    }

    function getUsers(){
        if($this->isAdmin()){
            return $this->usersDb->getDbUsers();
        }
        return [];
    }

    function updateUser($id, $username, $password, $role){
        if($this->isAdmin()){
            return $this->usersDb->updateUser($id, $username, $password, $role);
        }
        return false;
    }

    function deleteUser($id) {
        if ($this->isAdmin()) {
            return $this->usersDb->deleteUser($id);
        }
        return false;
    }

    function createUser($username, $password, $role) {
        if ($this->isAdmin()) {
            return $this->usersDb->createUser($username, $password, $role);
        }
        return false;
    }

    // Product Types
    function getProductTypes() {
        return $this->productsDb->getProductTypes();
    }

    function createProductType($name) {
        if ($this->isAdminOrModerator()) {
            $result = $this->productsDb->createProductType($name);
            if ($result) {
                $role = $_SESSION['role'];
                $this->productsDb->addLog("$role created Product Type '$name'");
            }
            return $result;
        }
    }

    function updateProductType($id, $name) {
        if ($this->isAdminOrModerator()) {
            $result = $this->productsDb->updateProductType($id, $name);
            if ($result) {
                $role = $_SESSION['role'];
                $this->productsDb->addLog("$role updated Product Type with id $id to name '$name'");
            }
            return $result;
        }
    }

    function deleteProductType($id) {
        if ($this->isAdminOrModerator()) {
            $productType = $this->productsDb->getProductTypeById($id);
            $result = $this->productsDb->deleteProductType($id);
            if ($result) {
                $role = $_SESSION['role'];
                $this->productsDb->addLog("$role deleted Product Type '{$productType['name']}' with id $id");
            }
            return $result;
        }
    }

    // Products
    function getProducts() {
        return $this->productsDb->getProducts();
    }

    function createProduct($name, $type_id, $price) {
        if ($this->isAdminOrModerator()) {
            $result = $this->productsDb->createProduct($name, $type_id, $price);
            if ($result) {
                $role = $_SESSION['role'];
                $this->productsDb->addLog("$role created Product '$name' with type_id $type_id and price $price");
            }
            return $result;
        }
    }

    function updateProduct($id, $name, $type_id, $price) {
        if ($this->isAdminOrModerator()) {
            $result = $this->productsDb->updateProduct($id, $name, $type_id, $price);
            if ($result) {
                $role = $_SESSION['role'];
                $this->productsDb->addLog("$role updated Product with id $id to name '$name', type_id $type_id, and price $price");
            }
            return $result;
        }
    }

    function deleteProduct($id) {
        if ($this->isAdminOrModerator()) {
            $product = $this->productsDb->getProductById($id);
            $result = $this->productsDb->deleteProduct($id);
            if ($result) {
                $role = $_SESSION['role'];
                $this->productsDb->addLog("$role deleted Product '{$product['name']}' with id $id");
            }
            return $result;
        }
    }

    // Orders
    function addProductIntoCart($data) {
        $productId = $data['id'];
        if (!isset($_COOKIE['order'])) {
            $order = [];
        } else {
            $order = unserialize($_COOKIE['order']);
        }

        if (!isset($order[$productId])) {
            $order[$productId] = 1;
        } else {
            $order[$productId]++;
        }
        setcookie('order', serialize($order), time() + 86400);
        header('Location: store.php');
    }

    function removeProductFromCart($data) {
        $productId = $data['id'];
        if (!isset($_COOKIE['order'])) {
            $order = [];
        } else {
            $order = unserialize($_COOKIE['order']);
        }

        if (isset($order[$productId])) {
            $order[$productId]--;
            if ($order[$productId] == 0) {
                unset($order[$productId]);
            }
        }
        setcookie('order', serialize($order), time() + 86400);
        header('Location: store.php');
    }

    function getCartItems() {
        $items = isset($_COOKIE['order']) ? unserialize($_COOKIE['order']) : [];
        $products = $this->getProducts();
        $itemsData = [];
        foreach ($items as $productId => $quantity) {
            foreach ($products as $product) {
                if ($product['id'] == $productId) {
                    $itemsData[$productId] = [
                        'name' => $product['name'],
                        'quantity' => $quantity,
                    ];
                    break;
                }
            }
        }
        return $itemsData;
    }

    function completeOrder() {
        $this->productsDb->addOrder(
            unserialize($_COOKIE['order']),
            $_SESSION['logged']
        );
        setcookie('order', serialize([]));
        header('Location: store.php');
    }

    function getLogs() {
        if ($this->isAdmin()) {
            return $this->productsDb->getLogs();
        }
        return [];
    }
    function getOrders() {
        if ($this->isAdminOrModerator()) {
            return $this->productsDb->getOrders();
        }
        return [];
    }
    function getUsersWithOrders() {
        if ($this->isAdminOrModerator()) {
            return $this->usersDb->getUsersWithOrders();
        }
        return [];
    }
    public function getProductTypesWithSpending() {
        return $this->productsDb->getProductTypesWithSpending();
    }
    public function getProductsWithSpending() {
        return $this->productsDb->getProductsWithSpending();
    }
}