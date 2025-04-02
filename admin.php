<?php
require_once 'pdo.php';
require_once 'Controller.php';

$controller=new Controller($pdo);

if(!$controller->isAdmin()){
    header('Location:index.php');
    exit();
}
$users = $controller->getUsers();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Manage Users</title>
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

<h1>Manage Users</h1>

<h2>Create New User</h2>
<form action="admin-handler.php" method="POST">
    <input type="hidden" name="action" value="create">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    Role:
    <select name="role" required>
        <option value="administrator">Administrator</option>
        <option value="moderator">Moderator</option>
        <option value="user">User</option>
        <option value="guest">Guest</option>
    </select><br>
    <input type="submit" value="Create User">
</form>

<h2>Current Users</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Role</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($users as $user) { ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= $user['username'] ?></td>
            <td><?= $user['role'] ?></td>
            <td>
                <form action="admin-handler.php" method="POST" style="display:inline;">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                    <input type="text" name="username" value="<?= $user['username'] ?>" required>
                    <input type="password" name="password" placeholder="New password">
                    <select name="role" required>
                        <option value="administrator" <?= $user['role'] === 'administrator' ? 'selected' : '' ?>>Administrator</option>
                        <option value="moderator" <?= $user['role'] === 'moderator' ? 'selected' : '' ?>>Moderator</option>
                        <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                        <option value="guest" <?= $user['role'] === 'guest' ? 'selected' : '' ?>>Guest</option>
                    </select>
                    <input type="submit" value="Save Editing">
                </form>
                <form action="admin-handler.php" method="POST" style="display:inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                    <input type="submit" value="Delete">
                </form>
            </td>
        </tr>
    <?php } ?>
</table>
<br>
If you want to logout,press button under<br><br>
<form action="admin-handler.php" method="POST">
    <input type="hidden" name="action" value="logout">
    <input type="submit" value="Logout">
</form>

</body>
</html>