<?php
    require_once "helpers.php";
    require_once 'views/indexView.php';

    session_start();
    if (isset($_POST['restart'])){
        $_SESSION['app'] = null;
    }
    if (isset($_POST['tema'])){
        $_SESSION['tema'] = $_POST['tema'];
    }
    if (!isset($_SESSION['tema'])){
        $_SESSION['tema'] = 'oscuro';
    }

    if (isset($_POST['num_tarjetas'])) {
        // Obtener el numero de tarjetas ingresado por el usuario
        $num_tarjetas_solicitado = filter_input(
            INPUT_POST,
            'num_tarjetas',
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 2, 'max_range' => 8]]
        );

        if ($num_tarjetas_solicitado !== false) {
            // El numero de tarjetas es valido (esta entre 2 y 8)
            $_SESSION['num_tarjetas'] = $num_tarjetas_solicitado;
        } else {
            // El numero de tarjetas no es valido
            echo "El número de tarjetas debe estar entre 2 y 8. Por favor, vuelve a intentarlo.";
        }
    }
    if (!isset($_SESSION['num_tarjetas'])){
        $_SESSION['num_tarjetas'] = 4;
    }
    $num_tarjetas = $_SESSION['num_tarjetas'];

    indexView($num_tarjetas);
?>
