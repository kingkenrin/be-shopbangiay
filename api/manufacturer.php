<?php
require "../bootstrap.php";
require "../handleCors.php";
use Src\Controller\ManufacturerController;

$requestMethod = $_SERVER["REQUEST_METHOD"];

$controller = new ManufacturerController($dbConnection, $requestMethod);
$controller->processRequest();
