<?php

    require_once "app.php";
    require_once "defaults.php";
    require_once "helpers.php";
    require_once "views/mainView.php";

    use JuegoMemoria\App\App;
    use JuegoMemoria\Juego\Juego;

    $debug = false;

    // Iniciar la sesion
    if (!isset($_SESSION)) {
        session_start();
    }

    // Verificar si se debe reiniciar el juego
    if (isset($_GET['restart']) || !isset($_SESSION['app']) || $_SESSION['app'] == null){
        assert(isset($_GET['jugadores']));
        $num_jugadores = $_GET['jugadores'];
        $num_tarjetas = isset($_SESSION['num_tarjetas'])
            ? $_SESSION['num_tarjetas']
            : NUMBER_OF_CARDS;
        $app = startGame($num_jugadores, $num_tarjetas);
        $_SESSION['app'] = $app;
    } else {
        $app = $_SESSION['app'];
    }

    assert($app instanceof App, 'App must be an instance of App');
    $juego = $app->juego;


    // Obtener la carta seleccionada
    if (isset($_GET['carta'])) {
        $juego->registrarCarta($_GET['carta']);
    }

    if (isset($_GET['ocultar'])){
        $juego->pasarTurno();
    }

    if ($debug){  // @phpstan-ignore-line
        debug_output($juego);
    }

    function startGame(int $num_jugadores, int $num_tarjetas=NUMBER_OF_CARDS): App {
        assert($num_jugadores == 1 || $num_jugadores == 2);
        $app = new App($num_jugadores, $num_tarjetas);
        return $app;
    }

    function debug_output(Juego $juego): void {
        echo "<p>Cartas barajadas: ".arrayToString($juego->cartas).'</p>';
        echo "<p>intento_actual: ". arrayToString($juego->intento_actual)."</p>";
        echo "<p>encontradas: ". arrayToString($juego->encontradas)."</p>";
    }


    $tema = $_SESSION['tema'];
    mainView($app, $tema);
