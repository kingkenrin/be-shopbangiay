<?php
require "../bootstrap.php";
require "../handleCors.php";
use Src\Controller\InvoiceController;

$requestMethod = $_SERVER["REQUEST_METHOD"];

$controller = new InvoiceController($dbConnection, $requestMethod);
$controller->processRequest();
