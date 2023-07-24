<?php
    namespace JuegoMemoria\App;

    require_once 'dmManagement.php';
    require_once "fileManager.php";
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
        /** @var array<string> $image_files */
        private $image_files;

        public function __construct(int $num_jugadores, int $num_tarjetas) {

            $this->db_manager = new DbManager();
            $this->time_manager = new TimeManager();
            $now = $this->time_manager->getCurrentTimeAsString();
            $this->timing = array("start"=>$now);
            $image_files = obtenerArchivos("images");
            if ($image_files == false){
                $image_files = []; // TODO: generar error
            }
            $this->image_files = $image_files;
            $num_imagenes = count($this->image_files);
            $num_tarjetas = min($num_tarjetas, $num_imagenes);
            $id_cartas = range(0, $num_tarjetas - 1);
            $this->juego = new Juego($id_cartas, $num_jugadores);
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

        public function getImagePath(int $id_carta): string {
            $imageFilename = $this->image_files[$id_carta];
            return "images/" . $imageFilename;
        }
    }
