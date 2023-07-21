<?php

/**
 * @return array<string>
 */
function obtenerArchivos(string $directorio): array|false {
    // Comprobar si el directorio existe
    if (is_dir($directorio)){
        // Abrir el directorio
        if ($dh = opendir($directorio)) {
            $archivos = array();
            // Recorrer el directorio
            while (($archivo = readdir($dh)) !== false) {
                // No tomar en cuenta '.' y '..'
                if ($archivo != "." && $archivo != "..") {
                    if (substr($archivo, 0, 1) != "_") {
                        // Añadir el archivo a la lista
                        $archivos[] = $archivo;
                    }
                }
            }
            // Cerrar el directorio
            closedir($dh);
            // Devolver la lista de archivos
            return $archivos;
        }
        return false;
    } else {
        echo "El directorio no existe";
        return false;
    }
}
