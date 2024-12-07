<?php
require "../bootstrap.php";
require "../handleCors.php";
use Src\Controller\UserController;

$requestMethod = $_SERVER["REQUEST_METHOD"];

$controller = new UserController($dbConnection, $requestMethod);
$controller->processRequest();
