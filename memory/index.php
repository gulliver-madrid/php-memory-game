<?php
    require_once "defaults.php";
    require_once "fileManager.php";
    require_once "helpers.php";
    require_once 'views/indexView.php';

    session_start();

    $archivos = obtenerArchivos('images');
    if ($archivos == false) {
        echo "No se encontró ningún archivo en el directorio images<br>";
        die();
    }
    $num_max_imagenes = count($archivos);

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
            ['options' => ['min_range' => 2, 'max_range' => $num_max_imagenes]]
        );
        $_SESSION['num_tarjetas'] = ($num_tarjetas_solicitado == false)
            ? NUMBER_OF_CARDS
            : $num_tarjetas_solicitado;
    }
    if (!isset($_SESSION['num_tarjetas'])){
        $_SESSION['num_tarjetas'] = NUMBER_OF_CARDS;
    }
    $num_tarjetas = $_SESSION['num_tarjetas'];
    $tema = $_SESSION['tema'];
    assert(is_string($tema));
    assert(in_array($tema, ['claro', 'oscuro']));
    assert(is_int($num_tarjetas));

    indexView($num_tarjetas, $tema, $num_max_imagenes);
?>
