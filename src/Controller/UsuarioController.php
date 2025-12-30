<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UsuarioController extends AbstractController
{
    #[Route('/new', name: 'new', methods: ['POST'])]
    public function index(): RedirectResponse
    {
        include_once 'App/Util/Connection.php';
        global $pdo;

        $error = ["", "", "", "", ""];

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

            if($resultado) {

                // Validar usuario
                $stmt = $pdo->prepare('SELECT id FROM Usuario WHERE correo = :mail AND contrasea = :pswd');
                $stmt->execute([
                    ":mail" => $email,
                    ":pswd" => $password
                ]);
                $userId = $stmt->fetch(PDO::FETCH_ASSOC);

                setcookie("current_user_id", $userId, time() + (86400), "/");

                return $this->redirectToRoute('home', [], Response::HTTP_OK);
            } else {
                $error[2] = $stmt->errorInfo();

                return $this->redirectToRoute('registro', ['error' => $error], Response::HTTP_CONFLICT);
            }
        } else {
            $error[0] = 'No information sent';
            return $this->redirectToRoute('registro', ['error' => $error], Response::HTTP_EXPECTATION_FAILED);
        }
    }

    #[Route('/verify-login', name: 'verify-login', methods: ['POST'])]
    public function verifyLogin(): RedirectResponse
    {



        return $this->redirectToRoute('home');
    }
}