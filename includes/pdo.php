<?php

// Connexion à la base de données
function connectDB() {
    $host = 'localhost'; 
    $dbname = 'login'; 
    $username = 'root'; 
    $password = 'root'; 

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        // Gérer les erreurs de connexion à la base de données ici
        echo "Erreur de connexion : " . $e->getMessage();
        return null;
    }
}
?>
