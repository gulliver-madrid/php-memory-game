<?php
    function arrayToString(array $array) {
        $str = "[";
        foreach ($array as $item) {
            if (is_array($item)){
                $item = arrayToString($item);
            }
            $str .= $item . ", ";
        }
        // Eliminar la ultima coma y espacio, y anadir el corchete de cierre
        $str = rtrim($str, ', ') . "]";
        return $str;
    }

    function clearSession(){
        // Inicia la sesión
        session_start();
        // Limpia todas las variables de sesión
        session_unset();
        // Destruye la sesión
        session_destroy();
    }
