<?php
// dbconnection.php

class Database {
    private $host = 'localhost';
    private $db_name = 'gestion_scrutins';
    private $username = 'root';
    private $password = 'admin'; // Mettez votre mot de passe de connexion à MySQL
    private $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            // Configure PDO pour lancer des exceptions en cas d'erreur
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Erreur de connexion à la base de données: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>
