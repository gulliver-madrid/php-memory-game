<?php

    require_once "helpers.php";

    $debug = false;

    class Juego {
        public $cartas;
        public $intento_actual;
        public $encontradas;
        public $intentos;
        public $aciertos;

        public function __construct() {
            // Cartas del juego
            $this->cartas = array('A', 'A', 'B', 'B', 'C', 'C', 'D', 'D', 'E', 'E', 'F', 'F', 'G', 'G', 'H', 'H');
            // Barajar las cartas
            shuffle($this->cartas);
            $this->intento_actual = array();
            $this->encontradas = array();
            $this->intentos = 0;
            $this->aciertos = 0;
        }

        public function registrarCarta(int $carta)
        {
            if (count($this->intento_actual) >= 2){
                // Intento ya completado
                return;
            }
            if (in_array($carta, $this->intento_actual)) {
                // Ya elegida
                return;
            }
            // Anadimos la carta pulsada
            $this->intento_actual[] = $carta;
            if (count($this->intento_actual) != 2){
                // Turno no completado
                return;
            }

            $this->evaluarTurno();
            return;
        }


        public function evaluarTurno() {
            $this->intentos++;
            $cartas = $this->cartas;
            $esAcierto = ($cartas[$this->intento_actual[0]] == $cartas[$this->intento_actual[1]]);
            if ($esAcierto) {
                $this->aciertos++;
                $this->encontradas[] = $this->intento_actual[0];
                $this->encontradas[] = $this->intento_actual[1];
                $this->intento_actual = array();
            }
        }
    }

    // Iniciar el juego
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
                    for ($i = 0; $i < count($cartas); $i++) {
                        if (in_array($i, $juego->intento_actual) ) {
                            echo '<div class="carta descubierta">'.$cartas[$i].'</div>';
                        } elseif (in_array($i, $juego->encontradas)) {
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
                <p style="text-align: right;"><small><button onclick="location.href='index.php?restart=true'" type=button>Reiniciar juego</button></small></p>
            </div>
        </div>
    </div>
</body>
</html>
