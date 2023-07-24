<?php
    require_once "memory/game.php";

    use PHPUnit\Framework\TestCase;
    use JuegoMemoria\Juego\Juego;

    /** @return array<int> */
    function crearIdCartasEjemplo(): array {
        return [1, 2, 3];
    }

    /** @return array<int> */
    function crearIdsCartasColocadasEjemplo(): array {
        return array_merge(crearIdCartasEjemplo(), crearIdCartasEjemplo());
    }

    class GameTest extends TestCase
    {
        public function testNewGameOnePlayer(): void
        {
            $juego = new Juego(crearIdCartasEjemplo(), 1);
            $this->assertJuegoBienInicializado($juego);
            $this->assertEquals(1, count($juego->aciertos));
            $this->assertEquals(6, count($juego->cartas));
        }

        public function testNewGameTwoPlayers(): void
        {
            $juego = new Juego(crearIdCartasEjemplo(), 2);
            $this->assertJuegoBienInicializado($juego);
            $this->assertEquals(2, count($juego->aciertos));
        }

        public function testRegistrarCarta(): void {
            $juego = new Juego(crearIdCartasEjemplo(), 1);
            $juego->registrarCarta(0);
            $this->assertEquals(1, count($juego->intento_actual));
        }

        public function testRegistrarCartaDosVecesNoCambiaNada(): void {
            $juego = new Juego(crearIdCartasEjemplo(), 1);
            $juego->registrarCarta(0);
            $juego->registrarCarta(0);
            $this->assertEquals(1, count($juego->intento_actual));
        }

        public function testNoEncuentraUnaPareja(): void {
            $juego = new Juego(crearIdCartasEjemplo(), 1);
            $juego->cartas = crearIdsCartasColocadasEjemplo();
            $juego->registrarCarta(0);
            $juego->registrarCarta(1);
            // El intento actual tiene dos cartas
            $this->assertEquals(2, count($juego->intento_actual));
            // Hay 0 cartas anotadas como encontradas este turno
            $this->assertEquals(0, count($juego->encontradas_este_turno));
            // El jugador 1 no tiene aciertos anotados
            $this->assertEquals(0, $juego->aciertos[0]);
        }

        public function testEncuentraUnaPareja(): void {
            $juego = new Juego(crearIdCartasEjemplo(), 1);
            $juego->cartas = crearIdsCartasColocadasEjemplo();
            $juego->registrarCarta(0);
            $juego->registrarCarta(3);
            // El intento actual se pone a 0
            $this->assertEquals(0, count($juego->intento_actual));
            // Las dos cartas se anotan como encontradas este turno
            $this->assertEquals(2, count($juego->encontradas_este_turno));
            // Se le anota un acierto al jugador 1
            $this->assertEquals(1, $juego->aciertos[0]);
        }

        public function testEncuentraUnaParejaJugador2(): void {
            $juego = new Juego(crearIdCartasEjemplo(), 2);
            $juego->cartas = crearIdsCartasColocadasEjemplo();
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

        private function assertJuegoBienInicializado (Juego $juego): void
        {
            $this->assertEquals(0, count($juego->encontradas));
            $this->assertEquals(0, count($juego->encontradas_este_turno));
            $this->assertEquals(1, ($juego->turno));
        }
    }
?>
