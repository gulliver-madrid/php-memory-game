<?php
    use PHPUnit\Framework\TestCase;
    require_once "memory/game.php";

    function crearValoresCartasEjemplo(): array {
        return ['a', 'b', 'c'];
    }
    function crearValoresCartasColocadasEjemplo(): array {
        return array_merge(crearValoresCartasEjemplo(), crearValoresCartasEjemplo());
    }

    class GameTest extends TestCase
    {
        public function testNewGameOnePlayer()
        {
            $juego = new Juego(crearValoresCartasEjemplo(), 1);
            $this->assertJuegoBienInicializado($juego);
            $this->assertEquals(1, count($juego->aciertos));
            $this->assertEquals(6, count($juego->cartas));
        }

        public function testNewGameTwoPlayers()
        {
            $juego = new Juego(crearValoresCartasEjemplo(), 2);
            $this->assertJuegoBienInicializado($juego);
            $this->assertEquals(2, count($juego->aciertos));
        }

        public function testRegistrarCarta(){
            $juego = new Juego(crearValoresCartasEjemplo(), 1);
            $juego->registrarCarta(0);
            $this->assertEquals(1, count($juego->intento_actual));
        }

        public function testRegistrarCartaDosVecesNoCambiaNada(){
            $juego = new Juego(crearValoresCartasEjemplo(), 1);
            $juego->registrarCarta(0);
            $juego->registrarCarta(0);
            $this->assertEquals(1, count($juego->intento_actual));
        }

        public function testNoEncuentraUnaPareja(){
            $juego = new Juego(crearValoresCartasEjemplo(), 1);
            $juego->cartas = crearValoresCartasColocadasEjemplo();
            $juego->registrarCarta(0);
            $juego->registrarCarta(1);
            // El intento actual tiene dos cartas
            $this->assertEquals(2, count($juego->intento_actual));
            // Hay 0 cartas anotadas como encontradas este turno
            $this->assertEquals(0, count($juego->encontradas_este_turno));
            // El jugador 1 no tiene aciertos anotados
            $this->assertEquals(0, $juego->aciertos[0]);
        }

        public function testEncuentraUnaPareja(){
            $juego = new Juego(crearValoresCartasEjemplo(), 1);
            $juego->cartas = crearValoresCartasColocadasEjemplo();
            $juego->registrarCarta(0);
            $juego->registrarCarta(3);
            // El intento actual se pone a 0
            $this->assertEquals(0, count($juego->intento_actual));
            // Las dos cartas se anotan como encontradas este turno
            $this->assertEquals(2, count($juego->encontradas_este_turno));
            // Se le anota un acierto al jugador 1
            $this->assertEquals(1, $juego->aciertos[0]);
        }

        public function testEncuentraUnaParejaJugador2(){
            $juego = new Juego(crearValoresCartasEjemplo(), 2);
            $juego->cartas = crearValoresCartasColocadasEjemplo();
            // El jugador 1 falla
            $juego->registrarCarta(0);
            $juego->registrarCarta(1);
            $juego->pasarTurno();
            // El jugador 2 acierta
            $juego->registrarCarta(0);
            $juego->registrarCarta(3);
            // El intento actual se pone a 0
            $this->assertEquals(0, count($juego->intento_actual));
            // Las dos cartas se anotan como encontradas este turno
            $this->assertEquals(2, count($juego->encontradas_este_turno));
            // Se le anota un acierto al jugador 2
            $this->assertEquals(0, $juego->aciertos[0]);
            $this->assertEquals(1, $juego->aciertos[1]);
        }

        private function assertJuegoBienInicializado (Juego $juego)
        {
            $this->assertEquals(0, count($juego->encontradas));
            $this->assertEquals(0, count($juego->encontradas_este_turno));
            $this->assertEquals(1, ($juego->turno));
        }
    }
?>
