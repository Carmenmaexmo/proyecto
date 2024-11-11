<?php
require_once('../Repositorios/conexion.php'); // Asegúrate de que DB esté definida en este archivo
require_once('../Repositorios/repoUsuario.php');

class ApiUser {
    private $repoUsuario;

    // Constructor
    public function __construct($repoUsuario) {
        $this->repoUsuario = $repoUsuario;
    }

    // Manejar la solicitud
    public function handleRequest() {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $this->createUsuario();
                break;
            case 'GET':
                if (isset($_GET['id'])) {
                    $this->getUsuarioById($_GET['id']);
                } else {
                    $this->getAllUsuarios();
                }
                break;
            case 'PUT':
                $this->updateUsuario();
                break;
            case 'DELETE':
                $this->deleteUsuario();
                break;
            default:
                http_response_code(405); // Método no permitido
                echo json_encode(['error' => 'Método no permitido']);
                break;
        }
    }

    private function createUsuario() {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
            return;
        }

        $usuarioData = [
            'nombre' => $data['nombre'],
            'ubicacion' => $data['ubicacion'],
            'telefono' => $data['telefono'],
            'contraseña' => $data['contraseña'],
            'foto' => $data['foto'] ?? null,
            'monedero' => $data['monedero'],
            'carrito' => $data['carrito'],
            'rol' => $data['rol'] ?? 'cliente'
        ];

        $alergenos = $data['alergenos'];

        $usuarioId = $this->repoUsuario->createUsuario($usuarioData, $alergenos);

        if ($usuarioId) {
            echo json_encode(['success' => true, 'usuarioId' => $usuarioId]);
        } else {
            echo json_encode(['success' => false, 'error' => 'No se pudo crear el usuario']);
        }
    }

    private function getUsuarioById($usuarioId) {
        $usuario = $this->repoUsuario->getUsuarioById($usuarioId);
        if ($usuario) {
            echo json_encode(['success' => true, 'usuario' => $usuario]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Usuario no encontrado.']);
        }
    }

    private function getAllUsuarios() {
        $usuarios = $this->repoUsuario->getAllUsuarios();
        echo json_encode(['success' => true, 'usuarios' => $usuarios]);
    }

    private function updateUsuario() {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
            return;
        }

        if (!isset($data['idUsuario'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'ID de usuario no proporcionado']);
            return;
        }

        $usuarioData = [
            'nombre' => $data['nombre'],
            'ubicacion' => $data['ubicacion'],
            'telefono' => $data['telefono'],
            'contraseña' => $data['contraseña'],
            'foto' => $data['foto'] ?? null,
            'monedero' => $data['monedero'],
            'carrito' => $data['carrito'],
            'rol' => $data['rol'] ?? 'cliente'
        ];

        $alergenos = $data['alergenos'];

        $usuarioId = $this->repoUsuario->updateUsuario($data['idUsuario'], $usuarioData, $alergenos);

        if ($usuarioId) {
            echo json_encode(['success' => true, 'usuarioId' => $usuarioId]);
        } else {
            echo json_encode(['success' => false, 'error' => 'No se pudo actualizar el usuario']);
        }
    }

    private function deleteUsuario() {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['idUsuario'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'ID de usuario no proporcionado']);
            return;
        }

        $result = $this->repoUsuario->deleteUsuario($data['idUsuario']);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'No se pudo eliminar el usuario']);
        }
    }
}

// Instanciamos las clases y ejecutamos
$db = new DB(); // Usamos DB aquí
$repoUsuario = new RepoUsuario($db);
$apiUser = new ApiUser($repoUsuario);

// Ejecutamos el manejador de la solicitud
$apiUser->handleRequest();
