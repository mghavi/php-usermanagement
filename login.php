<?php
require_once './admin/config.php';

if (isPost()) {
    $email = filter_input(INPUT_POST, "email");
    $password = filter_input(INPUT_POST, "password");
    $remember = (int) filter_input(INPUT_POST, "remember", FILTER_SANITIZE_NUMBER_INT);
    $user = new User();
    $user->setEmail($email);
    $user->setPassword($password);
    $user->setCookie($remember);
    if ($user->checklogin()) {
        if (isset($_SESSION['refer'])) {
            $url = explode("/", $_SESSION['refer']);
            $url = $url[count($url) - 1];
            unset($_SESSION['refer']);
            redirect($url);
        }
        redirect("index.php");
    } else {
        die("hi");
    }
}
?>
<html>
    <head>
        <title>Log In</title>
    </head>
    <body>
        <div class="content">
            <form method="POST">
                <label>Username / E-Mail</label><br/>
                <input name="email" type="text"/><br/>
                <label>Password</label><br/>
                <input name="password" type="password"/><br/>
                <label>
                    <input type="checkbox" name="remember" value="1"/> Remember Me
                </label>
                <button name="act_login">Log In</button>
            </form>
        </div>
    </body>
</html>