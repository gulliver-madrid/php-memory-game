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
