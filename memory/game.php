<?php
    namespace JuegoMemoria\Juego;

    class Juego {
        /** @var array<int> */
        public array $aciertos;
        /** @var array<int> */
        public array $cartas;
        /** @var array<int> */
        public array $encontradas;
        /** @var array<int> */
        public array $encontradas_este_turno;
        /** @var array<int> */
        public array $intento_actual;
        public int $jugador_actual;
        public int $num_jugadores;
        public int $turno;

        /**
         * @param array<int> $cartas_unicas
         */
        public function __construct(array $cartas_unicas, int $num_jugadores) {
            // Cartas del juego
            $this->cartas = array_merge($cartas_unicas, $cartas_unicas);
            // Barajar las cartas
            shuffle($this->cartas);
            $this->intento_actual = array();
            $this->encontradas = array();
            $this->encontradas_este_turno = array();
            $this->turno = 1;
            $this->aciertos = array_fill(0, $num_jugadores, 0);
            $this->jugador_actual = 0;
            $this->num_jugadores = $num_jugadores;
        }

        public function registrarCarta(int $indice_carta): void
        {
            assert(!$this->esCartaYaEncontrada($indice_carta));
            if (count($this->intento_actual) >= 2){
                // Intento ya completado
                return;
            }
            if ($this->esCartaDescubierta($indice_carta)) {
                // Ya elegida
                return;
            }
            // Anadimos la carta pulsada
            $this->intento_actual[] = $indice_carta;
            if ($this->intentoRealizado()){
                $this->evaluarTurno();
            }
        }

        public function evaluarTurno(): void {
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

        public function pasarTurno(): void {
            foreach($this->encontradas_este_turno as $i){
                $this->encontradas[] = $i;
            }
            $this->encontradas_este_turno = array();
            $this->intento_actual = array();
            if ($this->num_jugadores == 2){
                if ($this->jugador_actual == 0){
                    $this->jugador_actual = 1;
                } else {
                    $this->jugador_actual = 0;
                }
            }
            if ($this->jugador_actual == 0) {
                $this->turno++;
            }
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

    enum DisplayValue
    {
        case Descubierta;
        case YaEncontrada;
        case EncontradaEsteTurno;
        case Recogida;
        case Clicable;
        case NoClicable;
    }

    function getDisplayValue(Juego $juego, int $i): DisplayValue {
        if ($juego->esCartaDescubierta($i))
            return DisplayValue::Descubierta;
        elseif ($juego->esCartaYaEncontrada($i))
            return DisplayValue::YaEncontrada;
        elseif ($juego->esCartaEncontradaEsteTurno($i))
            return DisplayValue::EncontradaEsteTurno;
        elseif (!$juego->intentoRealizado())
            return DisplayValue::Clicable;
        else
            return DisplayValue::NoClicable;
    }
