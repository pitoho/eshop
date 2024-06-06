<?php
session_start();

function login() {
    global $_SESSION;

    $username = $_POST['login'];
    $password = $_POST['password'];

    $user = Eshop::getUserByLogin($username);

    if ($user) {
        if (hash('sha256',$password) == $user->hash) {

            $_SESSION['user_id'] = $user->id;
            $_SESSION['username'] = $user->login;
            echo LOGIN_OK;
            header('Refresh: 3, url=admin'); 
            exit;
        } else {
            echo PASSWORD_ERROR , ' ' , hash('sha256', trim(strip_tags($password))), " ", $user->hash ;
            header('Refresh: 3, url=login'); 
            exit;
        }
    } else {
        echo FIND_USER_ERROR;
        header('Refresh: 3, url=login'); 
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    login(); 
}
?>

<h1>Вход в админку</h1>
<p>Вернуться в <a href='/catalog'>каталог</a></p>
<form action="login" method="post">
    <div>
        <label>Логин:</label>
        <input type="text" name="login" size="25">
    </div>
    <div>
        <label>Пароль:</label>
        <input type="password" name="password" size="25">
    </div>
    <div>
        <input type="submit" value="Войти">
    </div>
</form>

<?php
if (isset($_SESSION['error_message'])) {
    echo "<p class='error'>" . $_SESSION['error_message'] . "</p>";
    unset($_SESSION['error_message']); 
}
?>