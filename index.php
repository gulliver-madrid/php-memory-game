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



// Obtener la carta seleccionada
if (isset($_GET['carta'])) {
    $cartas = $_SESSION['cartas'];
    if (count($_SESSION['intento_actual'])<2){
        $carta = $_GET['carta'];
        if (!in_array($carta, $_SESSION['intento_actual'])) {
            // Anadimos la carta pulsada
            $_SESSION['intento_actual'][] = $carta;
            if (count($_SESSION['intento_actual'])==2){
                $_SESSION['intentos']++;
                if ($cartas[$_SESSION['intento_actual'][0]] == $cartas[$_SESSION['intento_actual'][1]]) {
                    $_SESSION['aciertos']++;
                    $_SESSION['encontradas'][] = $_SESSION['intento_actual'][0];
                    $_SESSION['encontradas'][] = $_SESSION['intento_actual'][1];
                    $_SESSION['intento_actual'] = array();
                }
            }
        }
    }
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
             <?php
            $cartas = $_SESSION['cartas'];
            echo '<div class="board">';
            // echo 'tablero';
            for ($i = 0; $i < count($cartas); $i++) {
                if (in_array($i, $_SESSION['intento_actual']) ) {
                    echo '<div class="carta">'.$cartas[$i].'</div>';
                }
                elseif (in_array($i, $_SESSION['encontradas'])) {
                    echo '<div class="carta encontrada">'.$cartas[$i].'</div>';
                } else {
                    echo '<a href="?carta='.$i.'"><div class="carta">?</div></a>';
                }
            }
            echo '</div>';

            // Agregar el boton o enlace para reiniciar el juego en cualquier momento
            echo '<p style="text-align: right;"><small><a href="?restart=true">Reiniciar juego</a></small></p>';
            ?>
        </div>

        <div class="info">
            <p>Intentos: <?php echo $_SESSION['intentos']; ?></p>
            <p>Aciertos: <?php echo $_SESSION['aciertos']; ?></p>
            <?php
            if ($debug){
                echo "<p>intento_actual: ". arrayToString($_SESSION['intento_actual'])."</p>";
                echo "<p>encontradas: ". arrayToString($_SESSION['encontradas'])."</p>";
            }
            if (count($_SESSION['intento_actual'])==2){
                // Agregar el enlace para ocultar las cartas
                echo '<p><a href="?ocultar=true">Ocultar cartas</a></p>';
            }
            // Verificar si el juego ha terminado
            if ($_SESSION['aciertos'] == count($_SESSION['cartas'])/2) {
                echo "<p>";
                echo "¡Felicidades! Has ganado el juego en ".$_SESSION['intentos']." intentos.<br/>";
                echo '<a href="?restart=true">Jugar de nuevo</a>';
                echo "</p>";
            }
            ?>
        </div>

    </div>

</body>
</html>
