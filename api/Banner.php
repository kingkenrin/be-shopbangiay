<?php
require "../bootstrap.php";
require "../handleCors.php";
use Src\Controller\BannerController;

$requestMethod = $_SERVER["REQUEST_METHOD"];

$controller = new BannerController($dbConnection, $requestMethod);
$controller->processRequest();
