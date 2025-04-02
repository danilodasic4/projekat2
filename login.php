<?php

require_once 'pdo.php';
require_once 'Controller.php';
$controller = new Controller($pdo);

if ($controller->isLogged()) {
    header('Location: index.php');
}
$error = isset($_GET['error']) ? 'Invalid username or password.Try Again' : '';

?>

<form action='http-handler.php' method='post'>
    <input type='hidden' name='action' value='login'>
    Username: <input type='text' name='username'> <br/>
    Password: <input type='password' name='password'> <br/>
    <input type='submit' value='Login'> <br/>
</form>
<?= $error ?><br><br>

You want proceed like a guest?<br>
<a href="store.php">STORE</a>