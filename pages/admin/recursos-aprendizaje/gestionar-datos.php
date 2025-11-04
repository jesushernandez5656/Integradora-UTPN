<?php
// --- FUNCIONES AUXILIARES PARA LEER Y GUARDAR ---
function leerDatos() {
    // Lee el archivo JSON
    $datos_json = file_get_contents('datos.json');
    // Convierte el JSON en un array de PHP
    return json_decode($datos_json, true);
}

function guardarDatos($datos) {
    // Convierte el array de PHP de vuelta a JSON formateado
    $datos_json = json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    // Escribe el JSON de vuelta en el archivo
    file_put_contents('datos.json', $datos_json);
}

// --- MANEJO DE ACCIONES ---
if (isset($_POST['accion'])) {
    
    // 1. Lee los datos actuales
    $datos = leerDatos();

    // 2. Ejecuta la acción solicitada
    switch ($_POST['accion']) {
        
        case 'agregar_carrera':
            $nuevo_nombre = $_POST['nombre_carrera'];
            if (!empty($nuevo_nombre)) {
                // Obtiene el ID más alto y le suma 1
                $nuevo_id = empty($datos['categorias']) ? 1 : max(array_keys($datos['categorias'])) + 1;
                $datos['categorias'][$nuevo_id] = $nuevo_nombre;
            }
            break;

        case 'eliminar_carrera':
            $id_categoria = $_POST['id_categoria'];
            if (isset($datos['categorias'][$id_categoria])) {
                // Borra la carrera
                unset($datos['categorias'][$id_categoria]);
                // Borra los recursos de esa carrera
                foreach ($datos['recursos'] as $key => $recurso) {
                    if ($recurso['id_categoria'] == $id_categoria) {
                        unset($datos['recursos'][$key]);
                    }
                }
            }
            break;

        case 'agregar_recurso':
            $ids_recursos = empty($datos['recursos']) ? [0] : array_column($datos['recursos'], 'id_recurso');
            $nuevo_id_recurso = max($ids_recursos) + 1;

            $nuevo_recurso = [
                'id_recurso' => $nuevo_id_recurso,
                'id_categoria' => intval($_POST['id_categoria']),
                'titulo' => $_POST['titulo'],
                'descripcion' => $_POST['descripcion'],
                'tipo' => $_POST['tipo'],
                'enlace' => $_POST['enlace']
            ];
            $datos['recursos'][] = $nuevo_recurso;
            break;

        case 'editar_recurso':
            $id_recurso_editar = $_POST['id_recurso'];
            foreach ($datos['recursos'] as $key => $recurso) {
                if ($recurso['id_recurso'] == $id_recurso_editar) {
                    // Actualiza los datos del recurso en el array
                    $datos['recursos'][$key]['id_categoria'] = intval($_POST['id_categoria']);
                    $datos['recursos'][$key]['titulo'] = $_POST['titulo'];
                    $datos['recursos'][$key]['descripcion'] = $_POST['descripcion'];
                    $datos['recursos'][$key]['tipo'] = $_POST['tipo'];
                    $datos['recursos'][$key]['enlace'] = $_POST['enlace'];
                    break; 
                }
            }
            break;

        case 'eliminar_recurso':
            $id_recurso_eliminar = $_POST['id_recurso'];
            foreach ($datos['recursos'] as $key => $recurso) {
                if ($recurso['id_recurso'] == $id_recurso_eliminar) {
                    unset($datos['recursos'][$key]);
                    break;
                }
            }
            break;
    }
    
    // 3. Re-indexa el array de recursos (importante)
    $datos['recursos'] = array_values($datos['recursos']);
    
    // 4. Guarda los datos actualizados en el archivo
    guardarDatos($datos);
}

// 5. Redirige de vuelta al panel de admin
header('Location: admin_RA.php');
exit;
?>