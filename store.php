<?php
require_once 'pdo.php';
require_once 'Controller.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$controller = new Controller($pdo);
$products = $controller->getProducts();
$cartItems = $controller->getCartItems();
$isUser = $controller->getRole() === 'user';
$isLogged = $controller->isLogged();
$role=$controller->getRole();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Store</title>
    <style>
        table {
            width: 80%;
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
<h1>Products</h1>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Type</th>
        <th>Price</th>
        <?php if ($isUser) { ?><th>Actions</th><?php } ?>
    </tr>
    <?php if (!empty($products)) { ?>
        <?php foreach ($products as $product) { ?>
            <tr>
                <td><?= $product['id'] ?></td>
                <td><?= $product['name'] ?></td>
                <td><?= $product['type_name'] ?></td>
                <td><?= $product['price'] ?></td>
                <?php if ($isUser) { ?>
                    <td>
                        <form action="cart-handler.php" method="POST" style="display:inline-block;">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="id" value="<?= $product['id'] ?>">
                            <input type="submit" value="Add to Cart">
                        </form>
                    </td>
                <?php } ?>
            </tr>
        <?php } ?>
    <?php } ?>
</table>

<?php if ($isUser) { ?>
    <h2>Cart</h2>
    <ul>
        <?php foreach ($cartItems as $itemId => $item) { ?>
            <li>
                <?= $item['name'] ?> (<?= $item['quantity'] ?>)
                <form action="cart-handler.php" method="POST" style="display:inline-block;">
                    <input type="hidden" name="action" value="remove">
                    <input type="hidden" name="id" value="<?= $itemId ?>">
                    <input type="submit" value="-">
                </form>
                <form action="cart-handler.php" method="POST" style="display:inline-block;">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="id" value="<?= $itemId ?>">
                    <input type="submit" value="+">
                </form>
            </li>
        <?php } ?>
    </ul>

    <?php if (!empty($cartItems)) { ?>
        <form action="cart-handler.php" method="POST">
            <input type="hidden" name="action" value="complete">
            <input type="submit" value="Complete Order">
        </form>
    <?php } ?>
<?php } ?>
<?php if ($isLogged){ ?>
    <!-- Ulogovani korisnici -->
    <p>If you want to logout, press the button below:</p>
    <form action="products-handler.php" method="POST">
        <input type="hidden" name="action" value="logout">
        <input type="submit" value="Logout">
    </form>
    
    <?php if ($role === 'moderator'){ ?>
        <!-- Samo korisnici mogu da kompletiraju narudžbine -->
        <p>ONLY USERS can complete orders.</p>
    <?php }elseif ($role === 'administrator'){ ?>
        <!-- Administratori moderatori i posjetioci nemaju dodatnu opciju za kompletiranje narudžbina -->
        <p>ONLY USERS can complete orders.</p>
    <?php } ?>
<?php } else{ ?>
    <!-- Gosti -->
    <p>If you want to place order you need to log in before that, <a href="login.php">CLICK HERE</a></p>
<?php } ?>
</body>
</html>