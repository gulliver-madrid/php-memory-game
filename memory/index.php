<?php

    require_once "helpers.php";
    require_once "game.php";

    $debug = false;

    // Iniciar la sesion
    if (!isset($_SESSION)) {
        session_start();
    }

    // Verificar si se debe reiniciar el juego
    if (isset($_GET['restart']) || !isset($_SESSION['juego'])){
        $juego = new Juego();
        $_SESSION['juego'] = $juego;
    } else {
        $juego = $_SESSION['juego'];
    }


    if ($debug){
        echo "<p>Cartas barajadas: ".arrayToString($juego->cartas).'</p>';
        echo "<p>intento_actual: ". arrayToString($juego->intento_actual)."</p>";
        echo "<p>encontradas: ". arrayToString($juego->encontradas)."</p>";
    }

    // Obtener la carta seleccionada
    if (isset($_GET['carta'])) {
        $juego->registrarCarta($_GET['carta']);
    }

    if (isset($_GET['ocultar'])){
        $juego->intento_actual = array();
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
                        if ($juego->esCartaDescubierta($i)): ?>
                            <div class="carta descubierta">
                                <?= $valor_carta ?>
                            </div>
                        <?php elseif ($juego->esCartaYaEncontrada($i)): ?>
                            <div class="carta encontrada">
                                <?= $valor_carta ?>
                            </div>
                        <?php else: ?>
                            <a href="?carta=<?= $i ?>">
                                <div class="carta"></div>
                            </a>
                        <?php endif;
                    endfor;
                ?>
            </div>
        </div>

        <div class="info">
            <div>
                <p>Intentos: <?php echo $juego->intentos; ?></p>
                <p>Aciertos: <?php echo $juego->aciertos; ?></p>
                <?php

                    if (count($juego->intento_actual)==2){
                        // Agregar el enlace para ocultar las cartas
                        echo '<p><button onclick="location.href=\'?ocultar=true\'">Ocultar cartas</button></p>';
                    }
                    // Verificar si el juego ha terminado
                    if ($juego->aciertos == count($juego->cartas)/2) {
                        echo "<p>¡Felicidades! Has ganado el juego en ".$juego->intentos." intentos.</p>";
                        echo '<button onclick="location.href=\'index.php?restart=true\'" type=button>Jugar de nuevo</button>';
                    }
                ?>
            </div>

            <div>
                <?php // Boton para reiniciar el juego en cualquier momento ?>
                <p style="text-align: right;">
                    <small>
                        <button
                            onclick="location.href='index.php?restart=true'" type=button>
                                Reiniciar juego
                        </button>
                    </small>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
