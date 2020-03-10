<?php
define('ROOT', dirname(__FILE__) . DIRECTORY_SEPARATOR);
require ROOT . 'app/Config/config.php';
require ROOT . 'core/Application.php';

if(!isset($_GET['url']) or $_GET['url'] == 'index.php') {
    header('Location: /movies');
}

header('Content-Type: application/json');
$application = new Application();
$response = $application->dispatch();

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

