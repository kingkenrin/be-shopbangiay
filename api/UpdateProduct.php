<?php
require "../bootstrap.php";
require "../handleCors.php";
use Src\Controller\UpdateProductController;

$requestMethod = $_SERVER["REQUEST_METHOD"];

$controller = new UpdateProductController($dbConnection, $requestMethod);
$controller->processRequest();
