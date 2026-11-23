<?php
session_start();

require_once 'checkToken.php';
if (!isset($_POST["token"]) || isTokenNotValid($_POST["token"])) {
    header("Location: ../index.php");
    exit();
}

session_destroy();
header('Location: ../index.php');
exit();
?>