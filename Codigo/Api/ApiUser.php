<?php
// Incluir las clases necesarias
require_once('../Repositorios/conexion.php'); 
require_once('../Repositorios/repoUsuario.php'); 

class ApiUser {
    private $repoUsuario;

    // Constructor
    public function __construct($repoUsuario) {
        $this->repoUsuario = $repoUsuario;
    }

    // Método para manejar la solicitud
    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->createUsuario();
        } else {
            http_response_code(405); // Método no permitido
            echo json_encode(['error' => 'Método no permitido']);
        }
    }

    // Método para crear un usuario
    private function createUsuario() {
        // Decodificar el cuerpo JSON de la solicitud
        $data = json_decode(file_get_contents("php://input"), true);

        // Verificar si la decodificación fue exitosa
        if (!$data) {
            http_response_code(400);
            echo json_encode(['error' => 'Error al decodificar JSON: ' . json_last_error_msg()]);
            return;
        }

        // Comprobar si los datos esenciales están presentes
        $missingFields = [];
        if (!isset($data['nombre'])) $missingFields[] = 'nombre';
        if (!isset($data['ubicacion'])) $missingFields[] = 'ubicacion';
        if (!isset($data['telefono'])) $missingFields[] = 'telefono';
        if (!isset($data['contraseña'])) $missingFields[] = 'contraseña';
        if (!isset($data['monedero'])) $missingFields[] = 'monedero';
        if (!isset($data['carrito'])) $missingFields[] = 'carrito';
        if (!isset($data['rol'])) $missingFields[] = 'rol';
        if (!isset($data['alergenos'])) $missingFields[] = 'alergenos';

        // Si hay campos faltantes, registrar y retornar error
        if (count($missingFields) > 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos incompletos', 'faltantes' => $missingFields]);
            return;
        }

        // Preparar los datos del usuario
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

        // Obtener la lista de alérgenos
        $alergenos = $data['alergenos'];

        try {
            // Crear el usuario usando el repositorio
            $usuarioId = $this->repoUsuario->createUsuario($usuarioData, $alergenos);
            
            if ($usuarioId === null) {
                http_response_code(500);  // Error interno del servidor
                echo json_encode(['success' => false, 'error' => 'No se pudo crear el usuario.']);
                return;
            }

            // Si la creación fue exitosa
            http_response_code(201);  // Creado con éxito
            echo json_encode(['success' => true, 'idUsuario' => $usuarioId]);

        } catch (Exception $e) {
            http_response_code(500);  // Error interno del servidor
            echo json_encode(['error' => 'Error al crear el usuario: ' . $e->getMessage()]);
        }
    }
}

// Crear una instancia de la conexión
$db = new DB();

// Crear una instancia del repositorio con la conexión
$repoUsuario = new RepoUsuario($db);

// Crear una instancia de la API y manejar la solicitud
$apiUser = new ApiUser($repoUsuario);
$apiUser->handleRequest();
?>
