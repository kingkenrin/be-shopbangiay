<?php
require "../bootstrap.php";
require "../handleCors.php";
use Src\Controller\CategoryController;

$requestMethod = $_SERVER["REQUEST_METHOD"];

$controller = new CategoryController($dbConnection, $requestMethod);
$controller->processRequest();
