<?php

use PHPUnit\Framework\TestCase;
use JuegoMemoria\Juego\Juego;
use JuegoMemoria\Presenters\ScoresOnePlayerPresenter;
use JuegoMemoria\Presenters\ScoresTwoPlayersPresenter;
use function JuegoMemoria\Presenters\crearScorePresenters;

class CrearScorePresentersTest extends TestCase
{
    public function testCreaCorrectamenteScoresOnePlayerPresenter(): void
    {
        $juego = new Juego([], 1);
        $result = crearScorePresenters($juego);
        $this->assertInstanceOf(ScoresOnePlayerPresenter::class, $result);
    }

    public function testCreaCorrectamenteScoresTwoPlayersPresenter(): void
    {
        $juego = new Juego([], 2);
        $result = crearScorePresenters($juego);
        $this->assertInstanceOf(ScoresTwoPlayersPresenter::class, $result);
    }
}
