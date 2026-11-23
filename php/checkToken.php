<?php
function getToken() {
    if(!isset($_SESSION['token'])){
        $_SESSION['token'] = array(md5(rand()), md5(rand()), md5(rand()), md5(rand()), md5(rand()) );
        $_SESSION['idtoken'] = 0;
    }
    $token = md5(rand());
    $_SESSION['token'][$_SESSION['idtoken']] = $token;
    $_SESSION['idtoken'] = ($_SESSION['idtoken']+1)%5;
    return $token;
}

function isTokenNotValid($token) {
    if(!isset($_SESSION['token'])){
        return true;
    }
    return !in_array($token, $_SESSION['token']);
}
?>