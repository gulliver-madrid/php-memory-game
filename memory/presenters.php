<?php

    use JuegoMemoria\Juego\Juego;

    class JugadorScorePresenter {
        public string $nombre_jugador;
        public bool $is_selected;
        public int $aciertos_jugador;
        /**
         * @param array<int> $aciertos
         */
        public function __construct(int $indice_jugador, Juego $juego, array $aciertos){
            $this->nombre_jugador ="Jugador " . ($indice_jugador + 1);
            $this->is_selected = ($juego->jugador_actual == $indice_jugador);
            $this->aciertos_jugador = $aciertos[$indice_jugador];
        }
    }
