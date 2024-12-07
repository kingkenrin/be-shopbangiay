<?php
require "../bootstrap.php";
require "../handleCors.php";
use Src\Controller\FeedbackController;

$requestMethod = $_SERVER["REQUEST_METHOD"];

$controller = new FeedbackController($dbConnection, $requestMethod);
$controller->processRequest();
