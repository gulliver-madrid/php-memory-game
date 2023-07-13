<?php
    require_once "helpers.php";

    $debug = false;

    // Iniciar el juego
    if (!isset($_SESSION)) {
        session_start();
    }

    // Verificar si se debe reiniciar el juego
    if (isset($_GET['restart']) || !isset($_SESSION['cartas'])) {
        // Cartas del juego
        $cartas = array('A', 'A', 'B', 'B', 'C', 'C', 'D', 'D', 'E', 'E', 'F', 'F', 'G', 'G', 'H', 'H');
        // Barajar las cartas
        shuffle($cartas);

        $_SESSION['cartas'] = $cartas;
        $_SESSION['intento_actual'] = array();
        $_SESSION['encontradas'] = array();
        $_SESSION['intentos'] = 0;
        $_SESSION['aciertos'] = 0;
    }

    if ($debug){
        echo "<p>Cartas barajadas: ".arrayToString($_SESSION['cartas']).'</p>';
    }

    function registrarCarta(array $cartas)
    {
        if (count($_SESSION['intento_actual']) >= 2){
            // Intento ya completado
            return;
        }
        $carta = $_GET['carta'];
        if (in_array($carta, $_SESSION['intento_actual'])) {
            // Ya elegida
            return;
        }
        // Anadimos la carta pulsada
        $_SESSION['intento_actual'][] = $carta;
        if (count($_SESSION['intento_actual']) != 2){
            // Turno no completado
            return;
        }
        // Evaluacion turno
        $_SESSION['intentos']++;
        $esAcierto = ($cartas[$_SESSION['intento_actual'][0]] == $cartas[$_SESSION['intento_actual'][1]]);
        if ($esAcierto) {
            $_SESSION['aciertos']++;
            $_SESSION['encontradas'][] = $_SESSION['intento_actual'][0];
            $_SESSION['encontradas'][] = $_SESSION['intento_actual'][1];
            $_SESSION['intento_actual'] = array();
        }
    }

    // Obtener la carta seleccionada
    if (isset($_GET['carta'])) {
        $cartas = $_SESSION['cartas'];
        registrarCarta($cartas);
    }

    if (isset($_GET['ocultar'])){
        $_SESSION['intento_actual'] = array();
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
                    $cartas = $_SESSION['cartas'];
                    for ($i = 0; $i < count($cartas); $i++) {
                        if (in_array($i, $_SESSION['intento_actual']) ) {
                            echo '<div class="carta descubierta">'.$cartas[$i].'</div>';
                        } elseif (in_array($i, $_SESSION['encontradas'])) {
                            echo '<div class="carta encontrada">'.$cartas[$i].'</div>';
                        } else {
                            echo '<a href="?carta='.$i.'"><div class="carta"></div></a>';
                        }
                    }
                ?>
            </div>
        </div>

        <div class="info">
            <div>
                <p>Intentos: <?php echo $_SESSION['intentos']; ?></p>
                <p>Aciertos: <?php echo $_SESSION['aciertos']; ?></p>
                <?php
                    if ($debug){
                        echo "<p>intento_actual: ". arrayToString($_SESSION['intento_actual'])."</p>";
                        echo "<p>encontradas: ". arrayToString($_SESSION['encontradas'])."</p>";
                    }
                    if (count($_SESSION['intento_actual'])==2){
                        // Agregar el enlace para ocultar las cartas
                        echo '<p><button onclick="location.href=\'?ocultar=true\'">Ocultar cartas</button></p>';
                    }
                    // Verificar si el juego ha terminado
                    if ($_SESSION['aciertos'] == count($_SESSION['cartas'])/2) {
                        echo "<p>¡Felicidades! Has ganado el juego en ".$_SESSION['intentos']." intentos.</p>";
                        echo '<button onclick="location.href=\'index.php?restart=true\'" type=button>Jugar de nuevo</button>';
                    }
                ?>
            </div>

            <div>
                <?php // Boton para reiniciar el juego en cualquier momento ?>
                <p style="text-align: right;"><small><button onclick="location.href='index.php?restart=true'" type=button>Reiniciar juego</button></small></p>
            </div>
        </div>
    </div>
</body>
</html>
