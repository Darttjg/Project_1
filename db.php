<?php

function get_db() {
    $host = 'localhost';
    $dbname = 'parkingdb';
    
    // Attempt connection with credentials
    $user = 'parking'; // If you created a user put them here
    $pass = 'parkingpass'; //put the password to that user here
    
    try {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        return $pdo;
    } catch (PDOException $e) {
        // Fallback to root MySQL user if primary credentials fail
        try {
            $user = 'root';
            $pass = 'Ir1dized!'; // Adjust root password if configured on your local machine
            $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            return $pdo;
        } catch (PDOException $ex) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Database connection failed: ' . $ex->getMessage()]);
            exit;
        }
    }
}