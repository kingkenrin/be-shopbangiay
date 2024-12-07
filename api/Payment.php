<?php
require "../bootstrap.php";
require "../handleCors.php";
use Src\Controller\PaymentController;

$requestMethod = $_SERVER["REQUEST_METHOD"];

$controller = new PaymentController($dbConnection, $requestMethod);
$controller->processRequest();
