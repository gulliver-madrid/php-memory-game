<?php

    require_once "helpers.php";
    require_once "fileManager.php";
    require_once "game.php";

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
        $juego = new Juego($cartas);
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
    }

    if ($debug){
        echo "<p>Cartas barajadas: ".arrayToString($juego->cartas).'</p>';
        echo "<p>intento_actual: ". arrayToString($juego->intento_actual)."</p>";
        echo "<p>encontradas: ". arrayToString($juego->encontradas)."</p>";
    }

?>

<!DOCTYPE html>
<html>
<head>
    <title>Juego de Memoria</title>
    <link rel="stylesheet" type="text/css" href="index.css">
</head>
<body>
    <h1>Juego de Memoria</h1>
    <div class='hbox'>
        <div>
            <div class="board">
                <?php
                    $cartas = $juego->cartas;
                    for ($i = 0; $i < count($cartas); $i++):
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
                        <?php elseif (count($juego->intento_actual) < 2): ?>
                            <a href="?carta=<?= $i ?>">
                                <div class="carta clicable"></div>
                            </a>
                        <?php else: ?>
                            <div class="carta"></div>
                        <?php endif;
                    endfor;
                ?>
            </div>
        </div>

        <div class="info">
            <div>
                <p>Intentos: <?php echo $juego->intentos; ?></p>
                <p>Aciertos: <?php echo $juego->aciertos; ?></p>
                <?php if ($juego->intentoRealizado()): ?>
                    <p>
                        <button onclick="location.href='?ocultar=true'">
                            Ocultar cartas
                        </button>
                    </p>
                <?php endif; ?>

                <?php if ($juego->completado()): ?>
                    <p>
                        ¡Felicidades! Has ganado el juego en <?= $juego->intentos ?> intentos.
                    </p>
                    <button
                        onclick="location.href='index.php?restart=true'"
                        type=button
                    >
                        Jugar de nuevo
                    </button>
                <?php endif; ?>
            </div>

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