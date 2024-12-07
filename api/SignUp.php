<?php
require "../bootstrap.php";
require "../handleCors.php";
use Src\Controller\SignUpController;

$requestMethod = $_SERVER["REQUEST_METHOD"];

$controller = new SignUpController($dbConnection, $requestMethod);
$controller->processRequest();
