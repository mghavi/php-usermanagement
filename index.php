<?php
require './admin/config.php';
$user = new User();
if(!$user->isLogin()){
    $_SESSION['refer']=filter_input(INPUT_SERVER,'PHP_SELF');
    redirect("login.php");
}
