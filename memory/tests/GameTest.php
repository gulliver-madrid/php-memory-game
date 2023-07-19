<?php
    use PHPUnit\Framework\TestCase;
    require_once "memory/game.php";

    function crearValoresCartasEjemplo(): array {
        return ['a', 'b', 'c'];
    }

    class GameTest extends TestCase
    {
        public function testGameOnePlayer()
        {
            $juego = new Juego(crearValoresCartasEjemplo(), 1);
            $this->assertJuegoBienInicializado($juego);
            $this->assertEquals(1, count($juego->aciertos));
            $this->assertEquals(6, count($juego->cartas));
        }
        public function testGameTwoPlayers()
        {
            $juego = new Juego(crearValoresCartasEjemplo(), 2);
            $this->assertJuegoBienInicializado($juego);
            $this->assertEquals(2, count($juego->aciertos));
        }
        private function assertJuegoBienInicializado (Juego $juego)
        {
            $this->assertEquals(0, count($juego->encontradas));
            $this->assertEquals(0, count($juego->encontradas_este_turno));
            $this->assertEquals(1, ($juego->turno));
        }
    }
?>
