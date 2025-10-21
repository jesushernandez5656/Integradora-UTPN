<?php
// --- FUNCIONES AUXILIARES PARA LEER Y GUARDAR ---
function leerDatos() {
    $datos_json = file_get_contents('datos.json');
    return json_decode($datos_json, true);
}

function guardarDatos($datos) {
    // JSON_PRETTY_PRINT hace que el archivo .json sea legible
    $datos_json = json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents('datos.json', $datos_json);
}

// --- MANEJO DE ACCIONES ---
if (isset($_POST['accion'])) {
    
    $datos = leerDatos();

    switch ($_POST['accion']) {
        
        // --- ACCIONES DE CARRERAS ---
        case 'agregar_carrera':
            $nuevo_nombre = $_POST['nombre_carrera'];
            if (!empty($nuevo_nombre)) {
                $nuevo_id = empty($datos['categorias']) ? 1 : max(array_keys($datos['categorias'])) + 1;
                $datos['categorias'][$nuevo_id] = $nuevo_nombre;
            }
            break;

        case 'eliminar_carrera':
            $id_categoria = $_POST['id_categoria'];
            if (isset($datos['categorias'][$id_categoria])) {
                unset($datos['categorias'][$id_categoria]);
                foreach ($datos['recursos'] as $key => $recurso) {
                    if ($recurso['id_categoria'] == $id_categoria) {
                        unset($datos['recursos'][$key]);
                    }
                }
            }
            break;

        // --- ACCIONES DE RECURSOS ---
        case 'agregar_recurso':
            $ids_recursos = array_column($datos['recursos'], 'id_recurso');
            $nuevo_id_recurso = empty($ids_recursos) ? 1 : max($ids_recursos) + 1;

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

        // --- NUEVO: LÓGICA DE EDICIÓN ---
        case 'editar_recurso':
            $id_recurso_editar = $_POST['id_recurso'];
            foreach ($datos['recursos'] as $key => $recurso) {
                if ($recurso['id_recurso'] == $id_recurso_editar) {
                    // Actualizamos todos los datos del recurso
                    $datos['recursos'][$key]['id_categoria'] = intval($_POST['id_categoria']);
                    $datos['recursos'][$key]['titulo'] = $_POST['titulo'];
                    $datos['recursos'][$key]['descripcion'] = $_POST['descripcion'];
                    $datos['recursos'][$key]['tipo'] = $_POST['tipo'];
                    $datos['recursos'][$key]['enlace'] = $_POST['enlace'];
                    break; // Terminamos el bucle una vez encontrado y editado
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
    
    // Re-indexar el array de recursos para que el JSON quede limpio
    $datos['recursos'] = array_values($datos['recursos']);
    
    // Guardar todos los cambios en el archivo JSON
    guardarDatos($datos);
}

// Redireccionar siempre de vuelta al panel de admin
header('Location: admin_RA.php');
exit;
?>