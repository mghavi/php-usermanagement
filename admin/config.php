<?php

session_start();

spl_autoload_register(function($classname) {
    include __DIR__ . "/class/$classname.php";
});

function isPost() {
    return filter_input(INPUT_SERVER, "REQUEST_METHOD") == "POST";
}
function redirect($url){
    header("Location: $url");
    exit();
}

