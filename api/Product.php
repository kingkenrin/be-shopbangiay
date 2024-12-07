<?php
require "../bootstrap.php";
require "../handleCors.php";
use Src\Controller\ProductController;

$requestMethod = $_SERVER["REQUEST_METHOD"];

$controller = new ProductController($dbConnection, $requestMethod);
$controller->processRequest();
