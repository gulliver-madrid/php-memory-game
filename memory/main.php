<?php

    require_once "fileManager.php";
    require_once "game.php";
    require_once "helpers.php";

    $debug = false;

    // Iniciar la sesion
    if (!isset($_SESSION)) {
        session_start();
    }

    // Verificar si se debe reiniciar el juego
    if (isset($_GET['restart']) || !isset($_SESSION['juego'])){
        clearSession();
        $image_files = obtenerArchivos("images");
        if ($image_files == false){
            $image_files = []; // TODO: generar error
        }
        $cartas = array_merge($image_files, $image_files);
        assert(isset($_GET['jugadores']));
        $num_jugadores = $_GET['jugadores'];
        assert($num_jugadores == 1 || $num_jugadores == 2);
        $juego = new Juego($cartas, $num_jugadores);
        $_SESSION['juego'] = $juego;
    } else {
        $juego = $_SESSION['juego'];
    }

    // Obtener la carta seleccionada
    if (isset($_GET['carta'])) {
        $juego->registrarCarta($_GET['carta']);
    }

    if (isset($_GET['ocultar'])){
        $juego->intento_actual = array();
        $juego->pasarTurno();
    }

    if ($debug){
        debug_output($juego);
    }

    function debug_output($juego) {
        echo "<p>Cartas barajadas: ".arrayToString($juego->cartas).'</p>';
        echo "<p>intento_actual: ". arrayToString($juego->intento_actual)."</p>";
        echo "<p>encontradas: ". arrayToString($juego->encontradas)."</p>";
    }

    // Crea una tarjeta en el tablero
    function createSquare($juego, $i){
        $cartas = $juego->cartas;
        $valor_carta = $cartas[$i];
        $src = "images/" . $valor_carta;
        if ($juego->esCartaDescubierta($i)): ?>
            <div class="carta descubierta">
                <img src="<?= $src ?>" width="80" height="80">
            </div>
        <?php elseif ($juego->esCartaYaEncontrada($i)): ?>
            <div class="carta encontrada">
                <img src="<?= $src ?>" width="80" height="80">
            </div>
        <?php elseif (!$juego->intentoRealizado()): ?>
            <a href="?carta=<?= $i ?>">
                <div class="carta clicable"></div>
            </a>
        <?php else: ?>
            <div class="carta"></div>
        <?php endif;
    }

    function displayBoard($juego){
    ?>
        <div class="board">
            <?php
                $cartas = $juego->cartas;
                for ($i = 0; $i < count($cartas); $i++):
                    createSquare($juego, $i);
                endfor;
            ?>
        </div>
    <?php
    }


    // Muestra la informacion de final de juego y el boton de volver a jugar
    function displayEndGame($juego){
        if ($juego->completado()): ?>
            <?php if ($juego->num_jugadores == 1): ?>
                <p>
                    ¡Felicidades! Has ganado el juego en <?= $juego->intentos ?> intentos.
                </p>
            <?php else: ?>
                <p>
                    Juego finalizado
                </p>
            <?php endif; ?>
            <?php
                $handle_click_attr = (
                    "location.href='main.php?restart=true&jugadores=" .
                    $juego->num_jugadores .
                    "'"
                )
            ?>
            <button
                onclick=<?= $handle_click_attr ?>
                type=button
            >
                Jugar de nuevo
            </button>
        <?php endif;
    }

    // Muestra informacion sobre el juego
    function displayInfo($juego){
    ?>
        <div>
            <?php if ($juego->num_jugadores == 2): ?>
                <p>Jugador actual: <?php echo $juego->jugador_actual + 1; ?></p>
                <p>Turnos: <?php echo $juego->intentos; ?></p>
            <?php else: ?>
                <p>Intentos: <?php echo $juego->intentos; ?></p>
            <?php endif; ?>

            <?php $aciertos = $juego->aciertos; ?>
            <?php if ($juego->num_jugadores == 1): ?>
                <p>Aciertos: <?php echo $aciertos[0]; ?></p>
            <?php else: ?>
                <p>Aciertos jugador 1: <?php echo $aciertos[0]; ?></p>
                <p>Aciertos jugador 2: <?php echo $aciertos[1]; ?></p>
            <?php endif; ?>

            <?php if ($juego->intentoRealizado()):
                $button_text = ($juego->num_jugadores == 2)
                    ? "Cambiar jugador"
                    : "Ocultar cartas";
                ?>
                <p>
                    <button onclick="location.href='?ocultar=true'">
                        <?= $button_text ?>
                    </button>
                </p>
            <?php endif; ?>

            <?php displayEndGame($juego); ?>
        </div>
    <?php
    }

?>

<!DOCTYPE html>
<html>
<head>
    <title>Juego de Memoria</title>
    <link rel="stylesheet" type="text/css" href="main.css">
</head>
<body>
    <h1>Juego de Memoria</h1>
    <div class='hbox'>
        <div>
            <?php
                displayBoard($juego)
            ?>
        </div>

        <div class="info">
            <?php displayInfo($juego); ?>
            <div>
                <?php // Boton para reiniciar el juego en cualquier momento ?>
                <p style="text-align: right;">
                    <small>
                        <button
                            class="boton-reiniciar"
                            onclick="location.href='index.php?restart=true'"
                            type=button
                        >
                            Reiniciar juego
                        </button>
                    </small>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
