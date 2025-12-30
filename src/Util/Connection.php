<?php
    $host = '127.0.0.1';
    $port = 33100;
    $dbname = 'oretan-ia';
    $user = 'root';
    $pass = 'root';

    try {
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

    } catch (PDOException $e) {
        die("Error al conectar a la base de datos");

    }