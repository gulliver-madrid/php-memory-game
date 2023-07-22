<?php
    namespace JuegoMemoria\App;
    require_once 'dmManagement.php';
    require_once "game.php";
    require_once "timeManagement.php";
    use JuegoMemoria\Juego\Juego;
    use JuegoMemoria\Extras\DbManager;
    use JuegoMemoria\Extras\TimeManager;

    class App {
        public Juego $juego;
        public DbManager $db_manager;
        public TimeManager $time_manager;
        /** @var array<string> */
        public array $timing;

        public function __construct(Juego $juego) {

            $this->juego = $juego;
            $this->db_manager = new DbManager();
            $this->time_manager = new TimeManager();
            $now = $this->time_manager->getCurrentTimeAsString();
            $this->timing = array("start"=>$now);
        }

        public function registrarPartida(): void {
            $now = $this->time_manager->getCurrentTimeAsString();
            $id_partida = $this->db_manager->registrar_partida($this->timing['start'], $now, $this->juego->num_jugadores);
            if ($id_partida === false){
                echo "No se pudo acceder a la base de datos correctamente";
            } else {
                echo "Partida registrada con el ID: " . $id_partida;
            }
        }

        public function getImagePath(string $imageName): string {
            return "images/" . $imageName;
        }
    }
