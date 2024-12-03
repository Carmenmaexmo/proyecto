<?php
require_once '../Repositorios/Conexion.php';
require_once '../Repositorios/RepoKebab.php';
require_once '../Clases/Kebab.php';
require_once '../Clases/Ingredientes.php';

class ApiKebab {
    private $repoKebab;

    public function __construct($db) {
        $this->repoKebab = new RepoKebab($db);
    }

    public function handleRequest() {
        // Obtención del método HTTP
        $method = $_SERVER['REQUEST_METHOD'];

        // Obtención del endpoint
        $url = $_SERVER['REQUEST_URI'];
        $urlParts = explode('/', trim($url, '/'));
        $idKebab = isset($urlParts[5]) ? (int)$urlParts[5] : null; 

           // Parsear los parámetros de la query string
            $params = [];
            if (strpos($url, '?') !== false) {
                $queryString = parse_url($url, PHP_URL_QUERY); // Obtiene la parte después del "?"
                parse_str($queryString, $params); // Convierte la query string en un array
            }

        // Switch para manejar las peticiones HTTP
        switch ($method) {
            case 'POST':
                // Crear un kebab
                if (!$idKebab) {
                    $data = json_decode(file_get_contents("php://input"), true);
                    echo $this->createKebab($data);
                }
                // Asociar un ingrediente a un kebab
                else if ($idKebab) {
                    $idIngrediente = $urlParts[4] ?? null;
                    if ($idIngrediente) {
                        echo $this->addIngredienteToKebab($idKebab, $idIngrediente);
                    }
                }
                break;

            case 'GET':
                // Si el parámetro 'tipo' es igual a 'kebab_casa'
                if (isset($params['tipo']) && $params['tipo'] === 'kebab_casa') {
                    echo $this->getKebabsDeLaCasa(); // Obtener los kebabs de la casa
                }
                // Si no hay tipo especificado, o tipo no es 'kebab_casa'
                else if (!isset($urlParts[5])) {
                    echo $this->getKebabs(); // Obtener todos los kebabs
                }
                // Obtener un kebab específico por ID
                else {
                    echo $this->getKebab($idKebab);
                }
                break;

            case 'PUT':
                // Actualizar un kebab
                $data = json_decode(file_get_contents("php://input"), true); 
                if ($idKebab && $data) {
                    echo $this->updateKebab($idKebab, $data); 
                } else {
                    http_response_code(400);
                    echo json_encode(["status" => "error", "message" => "Datos no proporcionados correctamente"]);
                }
                break;

            case 'DELETE':
                // Eliminar un kebab
                if ($idKebab) {
                    echo $this->deleteKebab($idKebab);
                } else {
                    http_response_code(400);
                    echo json_encode(["status" => "error", "message" => "ID de kebab no proporcionado"]);
                }
                break;

            default:
                $this->sendResponse(405, ["status" => "error", "message" => "Método no permitido"]);
                break;
        }
    }

    // Crear un kebab
    private function createKebab($data) {
        if (!isset($data['nombre']) || !isset($data['precio']) || !isset($data['descripcion'])) {
            return $this->sendResponse(400, ["status" => "error", "message" => "Faltan datos obligatorios (nombre, precio, descripción)"]);
        }

        $ingredientesIds = isset($data['ingredientes']) ? $data['ingredientes'] : [];
        $kebabData = [
            'nombre' => "Kebab de la casa: " . $data['nombre'],
            'foto' => $data['foto'] ?? null,
            'precio' => $data['precio'],
            'descripcion' => $data['descripcion']
        ];

        $idKebab = $this->repoKebab->createKebab($kebabData, $ingredientesIds);

        if ($idKebab) {
            return $this->sendResponse(201, ["status" => "success", "message" => "Kebab creado correctamente", "id" => $idKebab]);
        }

        return $this->sendResponse(500, ["status" => "error", "message" => "Error al crear kebab"]);
    }

    // Obtener todos los kebabs
    private function getKebabs() {
        $kebabs = $this->repoKebab->getAllKebabs();
        return $this->sendResponse(200, $kebabs);
    }

    // Obtener un kebab específico por ID
    private function getKebab($idKebab) {
        $kebab = $this->repoKebab->getKebabById($idKebab);

        if ($kebab) {
            return $this->sendResponse(200, $kebab);
        }

        return $this->sendResponse(404, ["status" => "error", "message" => "Kebab no encontrado"]);
    }

    // En la clase ApiKebab
    public function getKebabsDeLaCasa() {
        $kebabsDeLaCasa = $this->repoKebab->getKebabsByNombre('Kebab de la casa:%');
        return $this->sendResponse(200, $kebabsDeLaCasa);
    }

    // Actualizar un kebab
    private function updateKebab($idKebab, $data) {
        // Verificar si los datos obligatorios están presentes
        if (!isset($data['nombre']) || !isset($data['precio']) || !isset($data['descripcion'])) {
            return $this->sendResponse(400, ["status" => "error", "message" => "Faltan datos obligatorios (nombre, precio, descripción)"]);
        }
    
        // Obtener los ingredientes
        $ingredientesIds = isset($data['ingredientes']) ? $data['ingredientes'] : [];
    
        // Datos del kebab que se van a actualizar
        $kebabData = [
            'nombre' => $data['nombre'],
            'foto' => $data['foto'] ?? null, // Si no hay foto, asignamos null
            'precio' => $data['precio'],
            'descripcion' => $data['descripcion']
        ];        
    
        // Llamada al repositorio para actualizar el kebab
        $updated = $this->repoKebab->updateKebab($idKebab, $kebabData, $ingredientesIds);
    
        // Respuesta en base al resultado de la actualización
        if ($updated) {
            return $this->sendResponse(200, ["status" => "success", "message" => "Kebab actualizado correctamente"]);
        }
    
        return $this->sendResponse(500, ["status" => "error", "message" => "Error al actualizar kebab"]);
    }

    // Eliminar un kebab
    private function deleteKebab($idKebab) {
        $deleted = $this->repoKebab->deleteKebab($idKebab);

        if ($deleted) {
            return $this->sendResponse(200, ["status" => "success", "message" => "Kebab eliminado correctamente"]);
        }

        return $this->sendResponse(500, ["status" => "error", "message" => "Error al eliminar kebab"]);
    }
  
    // Asociar un ingrediente a un kebab
    private function addIngredienteToKebab($idKebab, $ingredienteId) {
        // Usar $idIngrediente en lugar de $ingredienteId
        $added = $this->repoKebab->addIngredienteToKebab($idKebab, $ingredienteId);

        if ($added) {
            return $this->sendResponse(200, ["status" => "success", "message" => "Ingrediente asociado correctamente al kebab"]);
        }

        return $this->sendResponse(500, ["status" => "error", "message" => "Error al asociar ingrediente al kebab"]);
    }


    // Función para enviar respuestas JSON con códigos de estado adecuados
    private function sendResponse($statusCode, $response) {
        http_response_code($statusCode);
        echo json_encode($response);
        exit();
    }
}

// Instanciamos las clases y ejecutamos
$db = new DB();
$apiKebab = new ApiKebab($db);

// Ejecutamos el manejador de la solicitud
$apiKebab->handleRequest();