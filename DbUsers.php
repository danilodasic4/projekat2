<?php

class DbUsers {
    private $pdo;

    function __construct($pdo) {
        $this->pdo = $pdo;
    }

    function getDbUser($username, $password) {
        $sql = 'SELECT * FROM users WHERE username=:username AND password=:password';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            'username' => $username,
            'password' => sha1($password)
        ]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    function createUser($username, $password, $role) {
        $sql = 'INSERT INTO users (username, password, role) VALUES (:username, :password, :role)';
        $statement = $this->pdo->prepare($sql);
        return $statement->execute([
            'username' => $username,
            'password' => sha1($password),
            'role' => $role,
        ]);
    }

    function getDbUsers() {
        $sql = 'SELECT * FROM users';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function updateUser($id, $username, $password, $role) {
        $sql = 'UPDATE users SET username=:username, password=:password, role=:role WHERE id=:id';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'username' => $username,
            'password' => sha1($password),
            'role' => $role
        ]);
    }

    function deleteUser($id) {
        $sql = 'DELETE FROM users WHERE id=:id';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
    public function getUsersWithOrders() {
        $sql = '
            SELECT u.id, u.username, SUM(o.total_price) as total_spent
            FROM users u
            JOIN orders o ON u.id = o.user_id
            GROUP BY u.id
            HAVING total_spent > 0
        ';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}