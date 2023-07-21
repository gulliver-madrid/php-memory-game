<?php
    /**
     * @param array<mixed> $array
     */
    function arrayToString(array $array): string {
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

    function clearSession(): void {
        // Limpia todas las variables de sesión
        session_unset();
        // Destruye la sesión
        session_destroy();
        // Inicia la sesión
        session_start();
    }

    function consoleLog(string $text): void {
        echo "<script>console.log('" . $text . "')</script>";
    }
