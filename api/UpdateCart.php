<?php
require "../bootstrap.php";
require "../handleCors.php";
use Src\Controller\UpdateCartController;

$requestMethod = $_SERVER["REQUEST_METHOD"];

$controller = new UpdateCartController($dbConnection, $requestMethod);
$controller->processRequest();
