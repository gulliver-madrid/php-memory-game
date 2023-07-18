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
        assert(isset($_GET['jugadores']));
        $num_jugadores = $_GET['jugadores'];
        $juego = startGame($num_jugadores);
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

    function startGame(int $num_jugadores){
        assert($num_jugadores == 1 || $num_jugadores == 2);
        $image_files = obtenerArchivos("images");
        if ($image_files == false){
            $image_files = []; // TODO: generar error
        }
        $cartas = array_merge($image_files, $image_files);
        $juego = new Juego($cartas, $num_jugadores);
        return $juego;
    }

    function debug_output(Juego $juego) {
        echo "<p>Cartas barajadas: ".arrayToString($juego->cartas).'</p>';
        echo "<p>intento_actual: ". arrayToString($juego->intento_actual)."</p>";
        echo "<p>encontradas: ". arrayToString($juego->encontradas)."</p>";
    }

    function displayCardImage(string $src){
    ?>
        <img src="<?= $src ?>" width="80" height="80">
    <?php
    }

    // Crea una tarjeta en el tablero
    function displaySquare(Juego $juego, int $i) {
        $cartas = $juego->cartas;
        $valor_carta = $cartas[$i];
        $src = "images/" . $valor_carta;
        switch (getDisplayValue($juego, $i)) {
            case DisplayValue::Descubierta: ?>
                <div class="carta descubierta">
                    <?= displayCardImage($src) ?>
                </div>
                <?php break;
            case DisplayValue::YaEncontrada: ?>
                <div class="carta recogida"></div>
                <?php break;
            case DisplayValue::EncontradaEsteTurno: ?>
                <div class="carta encontrada">
                    <?= displayCardImage($src) ?>
                </div>
                <?php break;
            case DisplayValue::Clicable: ?>
                <a href="?carta=<?= $i ?>">
                    <div class="carta clicable"></div>
                </a>
                <?php break;
            case DisplayValue::NoClicable: ?>
                <div class="carta"></div>
                <?php break;
        }
    }

    function displayBoard(Juego $juego){
    ?>
        <div class="board">
            <?php
                $cartas = $juego->cartas;
                for ($i = 0; $i < count($cartas); $i++):
                    displaySquare($juego, $i);
                endfor;
            ?>
        </div>
    <?php
    }


    // Muestra la informacion de final de juego y el boton de volver a jugar
    function displayEndGame(Juego $juego){
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
    function displayInfo(Juego $juego){
    ?>
        <div>
            <?php if ($juego->num_jugadores == 2): ?>
                <p>Jugador actual: <?= $juego->jugador_actual + 1 ?></p>
                <p>Turnos: <?= $juego->intentos ?></p>
            <?php else: ?>
                <p>Intentos: <?= $juego->intentos ?></p>
            <?php endif; ?>

            <?php $aciertos = $juego->aciertos; ?>
            <?php if ($juego->num_jugadores == 1): ?>
                <p>Aciertos: <?= $aciertos[0] ?></p>
            <?php else: ?>
                <p>Aciertos jugador 1: <?= $aciertos[0] ?></p>
                <p>Aciertos jugador 2: <?= $aciertos[1] ?></p>
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
