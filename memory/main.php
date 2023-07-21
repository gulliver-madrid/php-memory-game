<?php

    require_once "app.php";
    require_once "fileManager.php";

    use JuegoMemoria\App\App;
    use JuegoMemoria\Juego\Juego;
    use JuegoMemoria\Juego\DisplayValue;
    use function JuegoMemoria\Juego\getDisplayValue;

    $debug = false;
    const NUMBER_OF_CARDS = 4;

    // Iniciar la sesion
    if (!isset($_SESSION)) {
        session_start();
    }

    // Verificar si se debe reiniciar el juego
    if (isset($_GET['restart']) || !isset($_SESSION['app'])){
        assert(isset($_GET['jugadores']));
        $num_jugadores = $_GET['jugadores'];
        $num_tarjetas = isset($_SESSION['num_tarjetas'])
            ? $_SESSION['num_tarjetas']
            : NUMBER_OF_CARDS;
        $juego = startGame($num_jugadores, $num_tarjetas);
        $app = new App($juego);
        $_SESSION['app'] = $app;
    } else {
        $app = $_SESSION['app'];
        $juego = $app->juego;
    }

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

    function startGame(int $num_jugadores, int $num_tarjetas=NUMBER_OF_CARDS): Juego {
        assert($num_jugadores == 1 || $num_jugadores == 2);
        $image_files = obtenerArchivos("images");
        if ($image_files == false){
            $image_files = []; // TODO: generar error
        }
        $image_files = array_slice($image_files, 0, $num_tarjetas);
        $juego = new Juego($image_files, $num_jugadores);
        return $juego;
    }

    function debug_output(Juego $juego): void {
        echo "<p>Cartas barajadas: ".arrayToString($juego->cartas).'</p>';
        echo "<p>intento_actual: ". arrayToString($juego->intento_actual)."</p>";
        echo "<p>encontradas: ". arrayToString($juego->encontradas)."</p>";
    }

    function displayCardImage(string $src): string {
        return "<img src=\"{$src}\" width=\"80\" height=\"80\">";
    }

    // Crea una tarjeta en el tablero
    function displaySquare(Juego $juego, int $i): void {
        $cartas = $juego->cartas;
        $valor_carta = $cartas[$i];
        $src = "images/" . $valor_carta;

        switch (getDisplayValue($juego, $i)) {
            case DisplayValue::Descubierta:
                echo "<div class=\"carta descubierta\">" . displayCardImage($src) . "</div>";
                break;
            case DisplayValue::YaEncontrada:
                echo "<div class=\"carta recogida\"></div>";
                break;
            case DisplayValue::EncontradaEsteTurno:
                echo "<div class=\"carta encontrada\">" . displayCardImage($src) . "</div>";
                break;
            case DisplayValue::Clicable:
                echo "<a href=\"?carta={$i}\"><div class=\"carta clicable\"></div></a>";
                break;
            case DisplayValue::NoClicable:
                echo "<div class=\"carta\"></div>";
                break;
        }
    }

    function displayBoard(Juego $juego): void {
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
    function displayEndGame(App $app): void {
        $juego = $app->juego;
        if (!$juego->completado())
            return;

        $app->registrarPartida();

        $texto_fin_juego = ($juego->num_jugadores == 1) ?
             "¡Felicidades! Has ganado el juego en $juego->turno intentos." :
             "Juego finalizado";
        $handle_click_attr = (
            "location.href='main.php?restart=true&jugadores=" .
            $juego->num_jugadores .
            "'"
        )
        ?>
        <p><?= $texto_fin_juego ?></p>
        <button
            onclick=<?= $handle_click_attr ?>
            type=button
        >
            Jugar de nuevo
        </button>
        <?php
    }

    // Muestra informacion sobre el juego
    function displayInfo(App $app): void {
        $juego = $app->juego;
    ?>
        <div>
            <?php if ($juego->num_jugadores == 2): ?>
                <p>Jugador actual: <?= $juego->jugador_actual + 1 ?></p>
                <p>Turno: <?= $juego->turno ?></p>
            <?php else: ?>
                <p>Intento: <?= $juego->turno ?></p>
            <?php endif; ?>

            <?php $aciertos = $juego->aciertos; ?>
            <?php if ($juego->num_jugadores == 1): ?>
                <p>Aciertos: <?= $aciertos[0] ?></p>
            <?php else: ?>
                <div class="hbox">
                    <div class="aciertos <?php if ($juego->jugador_actual==0) echo "selected" ?>">
                        <p>Jugador 1</p>
                        <p class="aciertos-num"><?= $aciertos[0] ?></p>
                    </div>
                    <div class="aciertos <?php if ($juego->jugador_actual==1) echo "selected" ?>">
                        <p>Jugador 2</p>
                        <p class="aciertos-num"><?= $aciertos[1] ?></p>
                    </div>
                </div>
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

            <?php displayEndGame($app); ?>
        </div>
    <?php
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Juego de Memoria</title>
    <link rel="stylesheet" type="text/css" href="styles/main.css">
    <?php if ($_SESSION['tema'] == 'claro'): ?>
        <link rel="stylesheet" type="text/css" href="styles/main-light.css">
    <?php else: ?>
        <link rel="stylesheet" type="text/css" href="styles/main-dark.css">
    <?php endif ?>
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
            <?php displayInfo($app); ?>
            <div class="boton-reiniciar-container">
                <?php // Boton para reiniciar el juego en cualquier momento ?>
                <button
                    class="boton-reiniciar"
                    onclick="location.href='index.php?restart=true'"
                    type=button
                >
                    Reiniciar juego
                </button>
            </div>
        </div>
    </div>
</body>
</html>
