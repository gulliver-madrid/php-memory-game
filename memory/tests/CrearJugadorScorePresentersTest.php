<?php

require_once 'memory/presenters.php';

use PHPUnit\Framework\TestCase;
use JuegoMemoria\Juego\Juego;

class CrearJugadorScorePresentersTest extends TestCase {

    public function testCrearJugadorScorePresenters(): void {
        // Crear un juego con jugador_actual como 0
        $juego = new Juego([], 2);
        $juego->jugador_actual = 0;

        // Aciertos para dos jugadores
        $aciertos = [10, 20];

        $presenters = crearJugadorScorePresenters($juego, $aciertos);

        // Debe haber dos presentadores
        $this->assertCount(2, $presenters);

        // Comprobar los datos del primer presentador
        $this->assertEquals("Jugador 1", $presenters[0]->nombre_jugador);
        $this->assertEquals(true, $presenters[0]->is_selected);
        $this->assertEquals(10, $presenters[0]->aciertos_jugador);

        // Comprobar los datos del segundo presentador
        $this->assertEquals("Jugador 2", $presenters[1]->nombre_jugador);
        $this->assertEquals(false, $presenters[1]->is_selected);
        $this->assertEquals(20, $presenters[1]->aciertos_jugador);
    }
}
