<?php

require_once 'memory/presenters.php';

use PHPUnit\Framework\TestCase;
use JuegoMemoria\Juego\Juego;

class JugadorScorePresenterTest extends TestCase {

    public function testConstructor(): void {
        // Crear un juego con jugador_actual como 0
        $juego = new Juego([], 2);
        $juego->jugador_actual = 0;

        // Aciertos para dos jugadores
        $aciertos = [10, 20];

        $jugadorPresenter = new JugadorScorePresenter(0, $juego, $aciertos);

        $this->assertEquals("Jugador 1", $jugadorPresenter->nombre_jugador);
        $this->assertEquals(true, $jugadorPresenter->is_selected);
        $this->assertEquals(10, $jugadorPresenter->aciertos_jugador);
    }
}
