<?php
    define('QUERY_TABLE_CREATION', "CREATE TABLE IF NOT EXISTS Partidas (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        inicio TIMESTAMP NOT NULL,
        fin TIMESTAMP NOT NULL,
        numero_jugadores INTEGER NOT NULL)");

    class DbManager {

        private function get_db_connection_with_table_partidas(){
            try {
                // Crea la conexión a la base de datos
                $db = new PDO('sqlite:partidas.db');

                // Establece el manejo de errores para que se generen excepciones
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Crea la tabla si no existe
                $db->exec(QUERY_TABLE_CREATION);
                return $db;
            } catch(PDOException $e) {
                // Muestra el error en caso de que exista
                echo $e->getMessage();
                return false;
            }
        }

        public function registrar_partida(string $inicio, string $fin, int $num_jugadores): int {
            $db = $this->get_db_connection_with_table_partidas();
            if ($db === false) {
                return false;
            }

            try {
                // Prepara la consulta SQL
                $stmt = $db->prepare("INSERT INTO Partidas (inicio, fin, numero_jugadores) VALUES (:inicio, :fin, :num_jugadores)");

                // Asocia los parámetros
                $stmt->bindParam(':inicio', $inicio);
                $stmt->bindParam(':fin', $fin);
                $stmt->bindParam(':num_jugadores', $num_jugadores);

                // Ejecuta la consulta
                $stmt->execute();

                // Retorna el ID de la partida recien insertada
                return $db->lastInsertId();
            } catch(PDOException $e) {
                // Muestra el error en caso de que exista
                echo $e->getMessage();
                return false;
            }
        }
    }
