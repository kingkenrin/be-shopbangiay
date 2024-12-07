<?php
require "../bootstrap.php";
require "../handleCors.php";
use Src\Controller\ShopController;

$requestMethod = $_SERVER["REQUEST_METHOD"];

$controller = new ShopController($dbConnection, $requestMethod);
$controller->processRequest();
