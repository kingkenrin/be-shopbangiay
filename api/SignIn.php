<?php
require "../bootstrap.php";
require "../handleCors.php";
use Src\Controller\SignInController;

$requestMethod = $_SERVER["REQUEST_METHOD"];

$controller = new SignInController($dbConnection, $requestMethod);
$controller->processRequest();
