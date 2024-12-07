<?php
require "../bootstrap.php";
require "../handleCors.php";
use Src\Controller\ConfirmCodeController;

$requestMethod = $_SERVER["REQUEST_METHOD"];

$controller = new ConfirmCodeController($dbConnection, $requestMethod);
$controller->processRequest();
