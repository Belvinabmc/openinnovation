<?php
header('Content-Type: text/html; charset=UTF-8');
$host = "sql307.infinityfree.com"; // Hôte MySQL
$dbname = "if0_38256733_openinnovation"; // Nom de la base de données
$username = "if0_38256733"; // Nom d'utilisateur MySQL
$password = "bassouaka"; // Mot de passe MySQL

try {
    // Connexion à MySQL avec PDO et activation de UTF-8
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ]);

    // Forcer l'encodage UTF-8
    $conn->exec("SET NAMES utf8mb4");
    $conn->exec("SET CHARACTER SET utf8mb4");

} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>