<?php
$host = 'localhost:3306'; 
$db = 'projeto1';    
$user = 'root';
$pass = ''; 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erro: ' . $e->getMessage();
}
?>