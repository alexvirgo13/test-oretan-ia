<?php
    session_name('oretan-ia');
    session_start();

    include_once 'Connection.php';
    global $pdo;

    if(isset($_POST["login-email"])){
        $email = $_POST["login-email"];
        $password = $_POST["login-pswd"];

        // Validar usuario
        $stmt = $pdo->prepare('SELECT * FROM Usuario WHERE correo = :mail AND contrasea = :pswd');
        $stmt->execute([
            ":mail" => $email,
            ":pswd" => $password
        ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            setcookie("current_user_id", $user["id"], time() + (86400), "/");

            header("Location: ../");
            exit;
        } else {
            echo "<p style='color:red;'>Usuario o contraseña incorrectos.</p>";
        }
    }
