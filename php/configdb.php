<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Incluye el autoload de Composer

use Dotenv\Dotenv;

// Carga el archivo .env desde la raíz del proyecto
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Asigna las variables de entorno a variables PHP
$SERVER = $_ENV['DB_SERVER'];
$USERNAME = $_ENV['DB_USERNAME'];
$PASSWORD = $_ENV['DB_PASSWORD'];
$DATABASE = $_ENV['DB_DATABASE'];

// Conecta a la base de datos
$conn = new mysqli($SERVER, $USERNAME, $PASSWORD, $DATABASE);

// Verifica la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
