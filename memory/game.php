<?php
    class Juego {
        public $cartas;
        public $intento_actual;
        public $encontradas;
        public $intentos;
        public $aciertos;

        public function __construct(array $cartas) {
            // Cartas del juego
            $this->cartas = $cartas;
            // Barajar las cartas
            shuffle($this->cartas);
            $this->intento_actual = array();
            $this->encontradas = array();
            $this->intentos = 0;
            $this->aciertos = 0;
        }

        public function registrarCarta(int $carta)
        {
            assert(!$this->esCartaYaEncontrada($carta));
            if (count($this->intento_actual) >= 2){
                // Intento ya completado
                return;
            }
            if ($this->esCartaDescubierta($carta)) {
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
            assert($this->intentoRealizado());
            [$primera, $segunda] = $this->intento_actual;
            $esAcierto = ($cartas[$primera] == $cartas[$segunda]);
            if ($esAcierto) {
                $this->aciertos++;
                $this->encontradas[] = $primera;
                $this->encontradas[] = $segunda;
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
