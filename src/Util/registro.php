<?php
    include_once 'Connection.php';
    global $pdo;

    if(isset($_POST["signup-fullname"])){
        $nombre = $_POST["signup-fullname"];
        $email = $_POST["signup-email"];
        $password = $_POST["signup-pswd"];

        $stmt = $pdo->prepare('INSERT INTO Usuario(correo, contrasea, nombre, apellido, creditos, fecha_registro)
                                            VALUES (:mail, :pswd, :name, :srnm, :crdt, :date)');
        $resultado = $stmt->execute([
            ":mail" => $email,
            ":pswd" => $password,
            ":name" => trim(substr($nombre, strpos($nombre, ",") + 1)),
            ":srnm" => trim(substr($nombre, 0, strpos($nombre, ","))),
            ":crdt" => 0,
            ":date" => date("Y-m-d H:i:s")
        ]);

        // Validar usuario
        $stmt = $pdo->prepare('SELECT id FROM Usuario WHERE correo = :mail AND contrasea = :pswd');
        $stmt->execute([
            ":mail" => $email,
            ":pswd" => $password
        ]);
        $userId = $stmt->fetch(PDO::FETCH_ASSOC);

        if($resultado) {
            setcookie("current_user_id", $userId, time() + (86400), "/");

            header("Location: ../");
            exit;
        } else {
            //Ahora mismo no se como pasar la informacion del error de vuelta a /registro
            //$_POST['error'] = 'No se pudo guardar el usuario';

            header("Location: ../registro");
            exit;
        }
    }


