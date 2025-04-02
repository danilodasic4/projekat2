<?php

class DbProducts {
    private $pdo;

    function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Product Types
    function getProductTypes() {
        $sql = 'SELECT * FROM product_types';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function createProductType($name) {
        $sql = 'INSERT INTO product_types (name) VALUES (:name)';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['name' => $name]);
    }

    function updateProductType($id, $name) {
        $sql = 'UPDATE product_types SET name=:name WHERE id=:id';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id, 'name' => $name]);
    }

    function deleteProductType($id) {
        $sql = 'DELETE FROM product_types WHERE id=:id';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // Products
    function getProducts() {
        $sql = 'SELECT p. *, pt.name AS type_name FROM Products p JOIN product_types pt ON p.type_id = pt.id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function createProduct($name, $type_id, $price) {
        $sql = 'INSERT INTO Products (name, type_id, price) VALUES (:name, :type_id, :price)';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['name' => $name, 'type_id' => $type_id, 'price' => $price]);
    }

    function updateProduct($id, $name, $type_id, $price) {
        $sql = 'UPDATE Products SET name=:name, type_id=:type_id, price=:price WHERE id=:id';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id, 'name' => $name, 'type_id' => $type_id, 'price' => $price]);
    }

    function deleteProduct($id) {
        $sql = 'DELETE FROM Products WHERE id=:id';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
    public function getOrders() {
        $sql = '
            SELECT o.id, u.username as customer_name, o.created_at, o.total_price
            FROM orders o
            JOIN users u ON o.user_id = u.id
        ';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addOrder($order, $userId) {
        $this->pdo->beginTransaction();
        try {
            $totalPrice = 0; 
            $stmt = $this->pdo->prepare('INSERT INTO orders (user_id, created_at, total_price) VALUES (?, NOW(), ?)');
            $stmt->execute([$userId, $totalPrice]);
            $orderId = $this->pdo->lastInsertId();
            
            $stmt = $this->pdo->prepare('INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)');
            foreach ($order as $productId => $quantity) {
                $stmt->execute([$orderId, $productId, $quantity]);
                
                // Izracunavanje ukupne cijene
                $product = $this->getProductById($productId);
                $totalPrice += $product['price'] * $quantity;
            }
            

            $stmt = $this->pdo->prepare('UPDATE orders SET total_price = ? WHERE id = ?');
            $stmt->execute([$totalPrice, $orderId]);
            
            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
    
    public function getProductById($id) {
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    function addLog($log) {
        $sql = 'INSERT INTO logs (log) VALUES (:log)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['log' => $log]);
    }
    function getLogs() {
        $sql = 'SELECT * FROM logs ORDER BY created_at DESC';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getProductTypesWithSpending() {
        $sql = '
            SELECT pt.name AS type_name, SUM(p.price * oi.quantity) AS total_spent
            FROM product_types pt
            JOIN products p ON pt.id = p.type_id
            JOIN order_items oi ON p.id = oi.product_id
            JOIN orders o ON oi.order_id = o.id
            GROUP BY pt.name
        ';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getProductsWithSpending() {
        $sql = '
            SELECT p.name AS product_name, SUM(p.price * oi.quantity) AS total_spent
            FROM products p
            JOIN order_items oi ON p.id = oi.product_id
            JOIN orders o ON oi.order_id = o.id
            GROUP BY p.name
        ';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
    
