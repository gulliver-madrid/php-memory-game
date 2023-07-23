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

    /**
     * @param array<int> $aciertos
     * @return array<JugadorScorePresenter>
     */
    function crearJugadorScorePresenters(Juego $juego, array $aciertos): array{
        $presenters = [];
        foreach([0, 1] as $indice_jugador){
            $presenters[] = new JugadorScorePresenter($indice_jugador, $juego, $aciertos);
        }
        return $presenters;
    }

    class ScoresTwoPlayersPresenter {
        /** @param array<JugadorScorePresenter> $presenters */
        public function __construct(
            public array $presenters
        ) {}
    }
    class ScoresOnePlayerPresenter {
        public function __construct(
            public string $text
        ) {}
    }

    function crearScorePresenters(Juego $juego): ScoresOnePlayerPresenter | ScoresTwoPlayersPresenter {
        if ($juego->num_jugadores == 1) {
            return new ScoresOnePlayerPresenter("Aciertos: " . $juego->aciertos[0]);
        } else {
            return new ScoresTwoPlayersPresenter(crearJugadorScorePresenters($juego, $juego->aciertos));
        }
    }

    /**
     * @return array<string>
     */
    function getEtiquetasJugadorYTurnoPresenter(Juego $juego): array {
        return ($juego->num_jugadores == 2)
            ?[
                "Jugador actual: " . ($juego->jugador_actual + 1),
                "Turno: " . $juego->turno
            ]
            :["Intento: " . $juego->turno];
    }
