<?php
/**
 * API REST para Gestión de Consejos de Ciberseguridad
 * USANDO ARCHIVOS JSON en lugar de Base de Datos
 * UTPN - Universidad Tecnológica Paso del Norte
 */

// Configuración de headers
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Rutas de archivos JSON
define('DATA_DIR', __DIR__ . '/../../data/');
define('CONSEJOS_FILE', DATA_DIR . 'consejos.json');
define('CATEGORIAS_FILE', DATA_DIR . 'categorias.json');
define('LOGS_FILE', DATA_DIR . 'logs.json');

// Crear directorio si no existe
if (!file_exists(DATA_DIR)) {
    mkdir(DATA_DIR, 0755, true);
}

// Clase principal de la API
class ConsejosAPIJSON {
    
    private function responder($data, $codigo = 200) {
        http_response_code($codigo);
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
    
    private function leerJSON($archivo) {
        if (!file_exists($archivo)) {
            return [];
        }
        $contenido = file_get_contents($archivo);
        $datos = json_decode($contenido, true);
        return $datos ?: [];
    }
    
    private function escribirJSON($archivo, $datos) {
        $json = json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        if (file_put_contents($archivo, $json, LOCK_EX) === false) {
            return false;
        }
        return true;
    }
    
    private function verificarAdmin() {
        // Descomenta para producción
        // if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'super_admin') {
        //     $this->responder(['success' => false, 'error' => 'Acceso denegado'], 403);
        // }
        return true;
    }
    
    public function listarConsejos($categoria = null) {
        try {
            $consejos = $this->leerJSON(CONSEJOS_FILE);
            $categorias = $this->leerJSON(CATEGORIAS_FILE);
            
            $catMap = [];
            foreach ($categorias as $cat) {
                $catMap[$cat['id']] = $cat;
            }
            
            $resultado = [];
            foreach ($consejos as $consejo) {
                if ($consejo['activo'] == 1) {
                    $catId = $consejo['categoria_id'];
                    if (isset($catMap[$catId])) {
                        $consejo['categoria_nombre'] = $catMap[$catId]['nombre'];
                        $consejo['categoria_icono'] = $catMap[$catId]['icono'];
                        $consejo['categoria'] = $catMap[$catId]['slug'];
                    }
                    
                    if ($categoria && $categoria !== 'todos') {
                        if (isset($consejo['categoria']) && $consejo['categoria'] === $categoria) {
                            $resultado[] = $consejo;
                        }
                    } else {
                        $resultado[] = $consejo;
                    }
                }
            }
            
            usort($resultado, function($a, $b) {
                $prioridades = ['high' => 3, 'medium' => 2, 'low' => 1];
                $priA = $prioridades[$a['prioridad']] ?? 0;
                $priB = $prioridades[$b['prioridad']] ?? 0;
                if ($priA === $priB) {
                    return strtotime($b['fecha']) - strtotime($a['fecha']);
                }
                return $priB - $priA;
            });
            
            $this->responder([
                'success' => true,
                'data' => $resultado,
                'total' => count($resultado)
            ]);
        } catch (Exception $e) {
            $this->responder([
                'success' => false,
                'error' => 'Error al obtener consejos: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function obtenerConsejo($id) {
        try {
            $consejos = $this->leerJSON(CONSEJOS_FILE);
            $categorias = $this->leerJSON(CATEGORIAS_FILE);
            
            foreach ($consejos as $consejo) {
                if ($consejo['id'] == $id && $consejo['activo'] == 1) {
                    foreach ($categorias as $cat) {
                        if ($cat['id'] == $consejo['categoria_id']) {
                            $consejo['categoria_nombre'] = $cat['nombre'];
                            $consejo['categoria_slug'] = $cat['slug'];
                            $consejo['categoria_icono'] = $cat['icono'];
                            break;
                        }
                    }
                    $this->responder(['success' => true, 'data' => $consejo]);
                }
            }
            $this->responder(['success' => false, 'error' => 'Consejo no encontrado'], 404);
        } catch (Exception $e) {
            $this->responder(['success' => false, 'error' => 'Error al obtener consejo: ' . $e->getMessage()], 500);
        }
    }
    
    public function listarCategorias() {
        try {
            $categorias = $this->leerJSON(CATEGORIAS_FILE);
            $consejos = $this->leerJSON(CONSEJOS_FILE);
            
            $resultado = [];
            foreach ($categorias as $cat) {
                if ($cat['activo'] == 1) {
                    $total = 0;
                    foreach ($consejos as $consejo) {
                        if ($consejo['categoria_id'] == $cat['id'] && $consejo['activo'] == 1) {
                            $total++;
                        }
                    }
                    $cat['total_consejos'] = $total;
                    $resultado[] = $cat;
                }
            }
            
            usort($resultado, function($a, $b) {
                return $a['orden'] - $b['orden'];
            });
            
            $this->responder(['success' => true, 'data' => $resultado, 'total' => count($resultado)]);
        } catch (Exception $e) {
            $this->responder(['success' => false, 'error' => 'Error al obtener categorías: ' . $e->getMessage()], 500);
        }
    }
    
    public function obtenerEstadisticas() {
        try {
            $consejos = $this->leerJSON(CONSEJOS_FILE);
            $categorias = $this->leerJSON(CATEGORIAS_FILE);
            
            $totalConsejos = 0;
            $altaPrioridad = 0;
            $ultimaActualizacion = null;
            
            foreach ($consejos as $consejo) {
                if ($consejo['activo'] == 1) {
                    $totalConsejos++;
                    if ($consejo['prioridad'] === 'high') {
                        $altaPrioridad++;
                    }
                    if (!$ultimaActualizacion || strtotime($consejo['fecha']) > strtotime($ultimaActualizacion)) {
                        $ultimaActualizacion = $consejo['fecha'];
                    }
                }
            }
            
            $totalCategorias = 0;
            foreach ($categorias as $cat) {
                if ($cat['activo'] == 1) {
                    $totalCategorias++;
                }
            }
            
            $this->responder([
                'success' => true,
                'data' => [
                    'total_consejos' => $totalConsejos,
                    'total_categorias' => $totalCategorias,
                    'alta_prioridad' => $altaPrioridad,
                    'ultima_actualizacion' => $ultimaActualizacion
                ]
            ]);
        } catch (Exception $e) {
            $this->responder(['success' => false, 'error' => 'Error al obtener estadísticas: ' . $e->getMessage()], 500);
        }
    }
    
    public function crearConsejo($datos) {
        $this->verificarAdmin();
        try {
            $campos_requeridos = ['titulo', 'categoria_id', 'descripcion_corta', 'contenido_completo'];
            foreach ($campos_requeridos as $campo) {
                if (empty($datos[$campo])) {
                    $this->responder(['success' => false, 'error' => "El campo {$campo} es requerido"], 400);
                }
            }
            
            $consejos = $this->leerJSON(CONSEJOS_FILE);
            $nuevoId = 1;
            foreach ($consejos as $consejo) {
                if ($consejo['id'] >= $nuevoId) {
                    $nuevoId = $consejo['id'] + 1;
                }
            }
            
            $nuevoConsejo = [
                'id' => $nuevoId,
                'titulo' => $datos['titulo'],
                'categoria_id' => (int)$datos['categoria_id'],
                'prioridad' => $datos['prioridad'] ?? 'medium',
                'icono' => $datos['icono'] ?? '📌',
                'descripcion' => $datos['descripcion_corta'],
                'contenido_completo' => $datos['contenido_completo'],
                'activo' => 1,
                'fecha' => date('Y-m-d H:i:s'),
                'creado_por' => $_SESSION['user_id'] ?? null
            ];
            
            $consejos[] = $nuevoConsejo;
            
            if ($this->escribirJSON(CONSEJOS_FILE, $consejos)) {
                $this->registrarLog($nuevoId, 'crear', null, $nuevoConsejo);
                $this->responder(['success' => true, 'message' => 'Consejo creado exitosamente', 'id' => $nuevoId], 201);
            } else {
                $this->responder(['success' => false, 'error' => 'Error al guardar el consejo'], 500);
            }
        } catch (Exception $e) {
            $this->responder(['success' => false, 'error' => 'Error al crear consejo: ' . $e->getMessage()], 500);
        }
    }
    
    public function actualizarConsejo($id, $datos) {
        $this->verificarAdmin();
        try {
            $consejos = $this->leerJSON(CONSEJOS_FILE);
            $encontrado = false;
            $datosAnteriores = null;
            
            foreach ($consejos as $index => $consejo) {
                if ($consejo['id'] == $id) {
                    $encontrado = true;
                    $datosAnteriores = $consejo;
                    $consejos[$index]['titulo'] = $datos['titulo'];
                    $consejos[$index]['categoria_id'] = (int)$datos['categoria_id'];
                    $consejos[$index]['prioridad'] = $datos['prioridad'];
                    $consejos[$index]['icono'] = $datos['icono'] ?? '📌';
                    $consejos[$index]['descripcion'] = $datos['descripcion_corta'];
                    $consejos[$index]['contenido_completo'] = $datos['contenido_completo'];
                    $consejos[$index]['fecha'] = date('Y-m-d H:i:s');
                    break;
                }
            }
            
            if (!$encontrado) {
                $this->responder(['success' => false, 'error' => 'Consejo no encontrado'], 404);
            }
            
            if ($this->escribirJSON(CONSEJOS_FILE, $consejos)) {
                $this->registrarLog($id, 'editar', $datosAnteriores, $consejos[$index]);
                $this->responder(['success' => true, 'message' => 'Consejo actualizado exitosamente']);
            } else {
                $this->responder(['success' => false, 'error' => 'Error al actualizar el consejo'], 500);
            }
        } catch (Exception $e) {
            $this->responder(['success' => false, 'error' => 'Error al actualizar consejo: ' . $e->getMessage()], 500);
        }
    }
    
    public function eliminarConsejo($id) {
        $this->verificarAdmin();
        try {
            $consejos = $this->leerJSON(CONSEJOS_FILE);
            $encontrado = false;
            
            foreach ($consejos as $index => $consejo) {
                if ($consejo['id'] == $id) {
                    $encontrado = true;
                    $consejos[$index]['activo'] = 0;
                    break;
                }
            }
            
            if (!$encontrado) {
                $this->responder(['success' => false, 'error' => 'Consejo no encontrado'], 404);
            }
            
            if ($this->escribirJSON(CONSEJOS_FILE, $consejos)) {
                $this->registrarLog($id, 'eliminar', null, null);
                $this->responder(['success' => true, 'message' => 'Consejo eliminado exitosamente']);
            } else {
                $this->responder(['success' => false, 'error' => 'Error al eliminar el consejo'], 500);
            }
        } catch (Exception $e) {
            $this->responder(['success' => false, 'error' => 'Error al eliminar consejo: ' . $e->getMessage()], 500);
        }
    }
    
    public function crearCategoria($datos) {
        $this->verificarAdmin();
        try {
            $categorias = $this->leerJSON(CATEGORIAS_FILE);
            $nuevoId = 1;
            foreach ($categorias as $cat) {
                if ($cat['id'] >= $nuevoId) {
                    $nuevoId = $cat['id'] + 1;
                }
            }
            
            $slug = $this->generarSlug($datos['nombre']);
            $nuevaCategoria = [
                'id' => $nuevoId,
                'nombre' => $datos['nombre'],
                'icono' => $datos['icono'],
                'descripcion' => $datos['descripcion'] ?? '',
                'slug' => $slug,
                'orden' => count($categorias) + 1,
                'activo' => 1
            ];
            
            $categorias[] = $nuevaCategoria;
            
            if ($this->escribirJSON(CATEGORIAS_FILE, $categorias)) {
                $this->responder(['success' => true, 'message' => 'Categoría creada exitosamente', 'id' => $nuevoId], 201);
            } else {
                $this->responder(['success' => false, 'error' => 'Error al guardar la categoría'], 500);
            }
        } catch (Exception $e) {
            $this->responder(['success' => false, 'error' => 'Error al crear categoría: ' . $e->getMessage()], 500);
        }
    }
    
    public function actualizarCategoria($id, $datos) {
        $this->verificarAdmin();
        try {
            $categorias = $this->leerJSON(CATEGORIAS_FILE);
            $encontrado = false;
            
            foreach ($categorias as $index => $cat) {
                if ($cat['id'] == $id) {
                    $encontrado = true;
                    $slug = $this->generarSlug($datos['nombre']);
                    $categorias[$index]['nombre'] = $datos['nombre'];
                    $categorias[$index]['icono'] = $datos['icono'];
                    $categorias[$index]['descripcion'] = $datos['descripcion'] ?? '';
                    $categorias[$index]['slug'] = $slug;
                    break;
                }
            }
            
            if (!$encontrado) {
                $this->responder(['success' => false, 'error' => 'Categoría no encontrada'], 404);
            }
            
            if ($this->escribirJSON(CATEGORIAS_FILE, $categorias)) {
                $this->responder(['success' => true, 'message' => 'Categoría actualizada exitosamente']);
            } else {
                $this->responder(['success' => false, 'error' => 'Error al actualizar la categoría'], 500);
            }
        } catch (Exception $e) {
            $this->responder(['success' => false, 'error' => 'Error al actualizar categoría: ' . $e->getMessage()], 500);
        }
    }
    
    public function eliminarCategoria($id) {
        $this->verificarAdmin();
        try {
            $consejos = $this->leerJSON(CONSEJOS_FILE);
            foreach ($consejos as $consejo) {
                if ($consejo['categoria_id'] == $id && $consejo['activo'] == 1) {
                    $this->responder(['success' => false, 'error' => 'No se puede eliminar una categoría con consejos asociados'], 400);
                }
            }
            
            $categorias = $this->leerJSON(CATEGORIAS_FILE);
            $encontrado = false;
            
            foreach ($categorias as $index => $cat) {
                if ($cat['id'] == $id) {
                    $encontrado = true;
                    $categorias[$index]['activo'] = 0;
                    break;
                }
            }
            
            if (!$encontrado) {
                $this->responder(['success' => false, 'error' => 'Categoría no encontrada'], 404);
            }
            
            if ($this->escribirJSON(CATEGORIAS_FILE, $categorias)) {
                $this->responder(['success' => true, 'message' => 'Categoría eliminada exitosamente']);
            } else {
                $this->responder(['success' => false, 'error' => 'Error al eliminar la categoría'], 500);
            }
        } catch (Exception $e) {
            $this->responder(['success' => false, 'error' => 'Error al eliminar categoría: ' . $e->getMessage()], 500);
        }
    }
    
    private function registrarLog($consejo_id, $accion, $datosAnteriores, $datosNuevos) {
        try {
            $logs = $this->leerJSON(LOGS_FILE);
            $nuevoLog = [
                'id' => count($logs) + 1,
                'consejo_id' => $consejo_id,
                'usuario_id' => $_SESSION['user_id'] ?? null,
                'accion' => $accion,
                'datos_anteriores' => $datosAnteriores,
                'datos_nuevos' => $datosNuevos,
                'fecha' => date('Y-m-d H:i:s'),
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ];
            $logs[] = $nuevoLog;
            $this->escribirJSON(LOGS_FILE, $logs);
        } catch (Exception $e) {
            error_log("Error al registrar log: " . $e->getMessage());
        }
    }
    
    private function generarSlug($texto) {
        $slug = strtolower(trim($texto));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }
}

function inicializarDatos() {
    if (!file_exists(CATEGORIAS_FILE)) {
        $categorias = [
            ['id' => 1, 'nombre' => 'Contraseñas', 'icono' => '🔐', 'descripcion' => 'Gestión segura de contraseñas', 'slug' => 'contrasenas', 'orden' => 1, 'activo' => 1],
            ['id' => 2, 'nombre' => 'Phishing', 'icono' => '🎣', 'descripcion' => 'Prevención de estafas', 'slug' => 'phishing', 'orden' => 2, 'activo' => 1],
            ['id' => 3, 'nombre' => 'Redes Sociales', 'icono' => '📱', 'descripcion' => 'Privacidad en redes sociales', 'slug' => 'redes-sociales', 'orden' => 3, 'activo' => 1],
            ['id' => 4, 'nombre' => 'Redes WiFi', 'icono' => '📶', 'descripcion' => 'Seguridad en conexiones WiFi', 'slug' => 'wifi', 'orden' => 4, 'activo' => 1],
            ['id' => 5, 'nombre' => 'Dispositivos', 'icono' => '💻', 'descripcion' => 'Protección de dispositivos', 'slug' => 'dispositivos', 'orden' => 5, 'activo' => 1]
        ];
        file_put_contents(CATEGORIAS_FILE, json_encode($categorias, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }
    
    if (!file_exists(CONSEJOS_FILE)) {
        $consejos = [
            ['id' => 1, 'titulo' => 'Contraseñas Fuertes', 'categoria_id' => 1, 'prioridad' => 'high', 'icono' => '🔐', 'descripcion' => 'Utiliza combinaciones de letras mayúsculas, minúsculas, números y símbolos especiales.', 'contenido_completo' => '<h3>¿Por qué son importantes?</h3><p>Una contraseña fuerte es tu primera línea de defensa.</p>', 'activo' => 1, 'fecha' => date('Y-m-d H:i:s'), 'creado_por' => null],
            ['id' => 2, 'titulo' => 'No Reutilices Contraseñas', 'categoria_id' => 1, 'prioridad' => 'high', 'icono' => '🔄', 'descripcion' => 'Cada cuenta debe tener una contraseña única.', 'contenido_completo' => '<h3>Riesgos</h3><p>Si comprometen una, comprometen todas.</p>', 'activo' => 1, 'fecha' => date('Y-m-d H:i:s'), 'creado_por' => null],
            ['id' => 3, 'titulo' => 'Verifica el Remitente', 'categoria_id' => 2, 'prioridad' => 'high', 'icono' => '🎣', 'descripcion' => 'Antes de hacer clic, verifica el correo del remitente.', 'contenido_completo' => '<h3>Señales de phishing</h3><p>Direcciones sospechosas, errores ortográficos...</p>', 'activo' => 1, 'fecha' => date('Y-m-d H:i:s'), 'creado_por' => null]
        ];
        file_put_contents(CONSEJOS_FILE, json_encode($consejos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }
    
    if (!file_exists(LOGS_FILE)) {
        file_put_contents(LOGS_FILE, json_encode([], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }
}

try {
    inicializarDatos();
    $api = new ConsejosAPIJSON();
    $metodo = $_SERVER['REQUEST_METHOD'];
    $accion = $_GET['action'] ?? '';
    $input = file_get_contents('php://input');
    $datos = json_decode($input, true);
    
    switch ($accion) {
        case 'listar':
            if ($metodo === 'GET') {
                $categoria = $_GET['categoria'] ?? null;
                $api->listarConsejos($categoria);
            }
            break;
        case 'obtener':
            if ($metodo === 'GET' && isset($_GET['id'])) {
                $api->obtenerConsejo($_GET['id']);
            }
            break;
        case 'listar_categorias':
            if ($metodo === 'GET') {
                $api->listarCategorias();
            }
            break;
        case 'estadisticas':
            if ($metodo === 'GET') {
                $api->obtenerEstadisticas();
            }
            break;
        case 'crear':
            if ($metodo === 'POST') {
                $api->crearConsejo($datos);
            }
            break;
        case 'actualizar':
            if ($metodo === 'PUT' && isset($_GET['id'])) {
                $api->actualizarConsejo($_GET['id'], $datos);
            }
            break;
        case 'eliminar':
            if ($metodo === 'DELETE' && isset($_GET['id'])) {
                $api->eliminarConsejo($_GET['id']);
            }
            break;
        case 'crear_categoria':
            if ($metodo === 'POST') {
                $api->crearCategoria($datos);
            }
            break;
        case 'actualizar_categoria':
            if ($metodo === 'PUT' && isset($_GET['id'])) {
                $api->actualizarCategoria($_GET['id'], $datos);
            }
            break;
        case 'eliminar_categoria':
            if ($metodo === 'DELETE' && isset($_GET['id'])) {
                $api->eliminarCategoria($_GET['id']);
            }
            break;
        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Acción no válida'], JSON_UNESCAPED_UNICODE);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
?>