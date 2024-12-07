<?php
require "../bootstrap.php";
require "../handleCors.php";
use Src\Controller\CartController;

$requestMethod = $_SERVER["REQUEST_METHOD"];

$controller = new CartController($dbConnection, $requestMethod);
$controller->processRequest();
