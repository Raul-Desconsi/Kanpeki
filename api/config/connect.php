<?php
$host = 'localhost';
$db   = 'kanpeki';
$user = 'root'; 
$pass = ''; 

try {
    $conexao = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die(json_encode(["status" => 500, "mensagem" => "Falha na conexão: " . $e->getMessage()]));
}