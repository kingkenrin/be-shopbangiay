<?php
require "../bootstrap.php";
require "../handleCors.php";
use Src\Controller\ForgetPasswordController;

$requestMethod = $_SERVER["REQUEST_METHOD"];

$controller = new ForgetPasswordController($dbConnection, $requestMethod);
$controller->processRequest();
