<?php
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
            if ($this->intentoRealizado()){
                $this->evaluarTurno();
            }
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

        public function esCartaDescubierta(int $i) {
            return in_array($i, $this->intento_actual);
        }

        public function esCartaYaEncontrada(int $i) {
            return in_array($i, $this->encontradas);
        }
        public function intentoRealizado(): bool {
            return count($this->intento_actual)==2;
        }
        public function completado(): bool {
            return $this->aciertos == count($this->cartas)/2;
        }
    }
