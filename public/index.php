<?php

require_once '../vendor/autoload.php';
use RedBeanPHP\R as R;
session_start();

R::setup(
    'mysql:host=localhost;dbname=binsta',
    'bit_academy',
    'bit_academy'
);

$params = explode('/', $_GET['params']);
$userId = $_SESSION['userid'] ?? null;
$error = $_GET['error'] ?? null;

// Defineer controller en controleer of deze bestaat
$controllerName = $params[0] == "" ? "feed" : $params[0];
$controllerName = ucfirst($controllerName) . "Controller";

if (class_exists($controllerName)) {
    $controller = new $controllerName();
} else {
    error(404, '"' . $controllerName . '" is geen bestaande controller!');
    exit();
}

// Haal de method op en geef hem index indien die leeg is
if (isset($params[1]) && $params[1] == 'index') {
    header('location: ./../');
}

$method = isset($params[1]) && !empty($params[1]) ? $params[1] : 'index';

$method = strtolower($method);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $method .= 'Post';
}

// Haal de ID op als die gegeven is
$id = isset($params[2]) && !empty($params[2]) ? $params[2] : null;

// Controleer of de gegeven method bestaat zo ja wordt deze verwerkt
if (method_exists($controller, $method)) {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $controller->$method($id);
    } else {
        $controller->$method($id, $userId, $error);
    }
} else {
    error(404, '"' . $method . '" is geen bestaande method!');
    exit();
}
