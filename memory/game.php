<?php
    class Juego {
        public $cartas;
        public $intento_actual;
        public $encontradas;
        public $intentos;
        public $aciertos;
        public $jugador_actual;
        public $num_jugadores;

        public function __construct(array $cartas, int $num_jugadores) {
            // Cartas del juego
            $this->cartas = $cartas;
            // Barajar las cartas
            shuffle($this->cartas);
            $this->intento_actual = array();
            $this->encontradas = array();
            $this->encontradas_este_turno = array();
            $this->intentos = 0;
            $this->aciertos = array_fill(0, $num_jugadores, 0);
            $this->jugador_actual = 0;
            $this->num_jugadores = $num_jugadores;
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
                $this->aciertos[$this->jugador_actual]++;
                $this->encontradas_este_turno[] = $primera;
                $this->encontradas_este_turno[] = $segunda;
                $this->intento_actual = array();
            }
        }

        public function pasarTurno() {
            if ($this->num_jugadores == 2){
                if ($this->jugador_actual == 0){
                    $this->jugador_actual = 1;
                } else {
                    $this->jugador_actual = 0;
                }
            }
            foreach($this->encontradas_este_turno as $i){
                $this->encontradas[] = $i;
            }
            $this->encontradas_este_turno = array();
        }

        public function esCartaDescubierta(int $i): bool {
            return in_array($i, $this->intento_actual);
        }

        public function esCartaEncontradaEsteTurno(int $i): bool {
            return in_array($i, $this->encontradas_este_turno);
        }
        public function esCartaYaEncontrada(int $i): bool {
            return in_array($i, $this->encontradas);
        }
        public function intentoRealizado(): bool {
            return count($this->intento_actual)==2;
        }
        public function completado(): bool {
            return array_sum($this->aciertos) == count($this->cartas)/2;
        }
    }
