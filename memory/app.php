<?php

    require_once 'dmManagement.php';
    require_once "game.php";

    class App {

        public function __construct(Juego $juego) {

            $this->juego = $juego;
            $this->db_manager = new DbManager();
            $this->timing = array("start"=>getCurrentTimeAsString());
        }

        public function registrarPartida(): void {
            $id_partida = $this->db_manager->registrar_partida($this->timing['start'], getCurrentTimeAsString(), $this->juego->num_jugadores);
            if ($id_partida === false){
                echo "No se pudo acceder a la base de datos correctamente";
            } else {
                echo "Partida registrada con el ID: " . $id_partida;
            }
        }
    }
