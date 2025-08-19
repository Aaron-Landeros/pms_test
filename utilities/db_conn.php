<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
$dsn = sprintf('mysql:host=%s;dbname=%s;', $_ENV['DB_HOST'], $_ENV['DB_NAME']);
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];
?>
