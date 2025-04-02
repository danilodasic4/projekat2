<?php
require_once 'pdo.php';
require_once 'Controller.php';
$controller = new Controller($pdo);
// $products = $controller->getProducts();
// $cartItems = $controller->getCartItems();
if(!$controller->isLogged()){
    header('Location: login.php');
}
$role=$controller->getRole();
?>
<h1>Welcome, <?= $role ?>!</h1>

<?php if ($role === 'administrator') { ?>
    <p><br>
    You have all possibilites,except ordering :D<br>
     First, everything about users  <br>=><a href="admin.php">Managing users</a> <=<br>
     Second, everything about products <br>=><a href="products.php">Products</a><=<br>
     Third, everything about product types <br>=><a href="product-types.php">Product Types</a><=<br>
     Fourth you can see our products, but you can't complete order <br>=><a href="store.php">STORE</a><=<br>
     Fifth, also you can see all logs if you are interesting in it<br> =><a href="logs.php">LOGS</a> <=<br> 
     Sixth, also you can see all orders(id of order,name of customer,total price and time of ordering )<br>
     <a href="orders.php">ORDERS</a> <br>
     Seventh,one of many your possibilites, see users with total spent of money<br>
     <a href="users_with_orders.php">USERS WITH ORDERS</a><br>
     Eighth,see product types with spending<br>
     <a href="product_types_with_spending.php">PRODUCT TYPES WITH SPENDING</a><br>
     Ninth,finally the last one is that you can see products with spending on them<br>
     <a href="products_with_spending.php">PRODUCTS WITH SPENDING</a>
    </p>
<?php } elseif ($role === 'moderator') { ?>
    <p><br>
    You have possibility to manage Products <br><a href="products.php">Products</a><br>
    You have possibility to manage Product Types <br><a href="product-types.php">Product Types</a><br>
    You can see our products, but you can't complete order <br>=><a href="store.php">STORE</a><=<br>
    Also you can see all orders(id of order,name of customer,total price and time of ordering )<br>
    <a href="orders.php">ORDERS</a><br>
    One of your possibilites is to see users with total spent of money<br>
    <a href="users_with_orders.php">USERS WITH ORDERS</a><br>
    See product types with spending if you want<br>
     <a href="product_types_with_spending.php">PRODUCT TYPES WITH SPENDING</a><br>
     The last possibility you can see is products with spending on them<br>
     <a href="products_with_spending.php">PRODUCTS WITH SPENDING</a>

    </p>
<?php } elseif ($role === 'user') { ?>
    <p>User content here.<br>
        Do you want to take a look what we have interesting for you in our store?<br>
        click here<br><a href="store.php">STORE</a>
    </p>
<?php } else { ?>
    <p><br>
        You have acces to our store, but to complete order you need to be our user...
        <br><a href="store.php">STORE</a>
    </p>
<?php } ?>

<form action='http-handler.php' method='post'>
    <input type='hidden' name='action' value='logout'>
    <input type='submit' value='Logout'>
</form>

<style>
    form {
        display: inline;
    }
</style>