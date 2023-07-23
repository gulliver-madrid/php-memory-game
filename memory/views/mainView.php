<?php
    require_once "app.php";
    require_once "presenters.php";

    use JuegoMemoria\App\App;
    use JuegoMemoria\Juego\Juego;
    use JuegoMemoria\Juego\DisplayValue;
    use function JuegoMemoria\Juego\getDisplayValue;


    function displayCardImage(string $src): string {
        return "<img src=\"{$src}\" width=\"80\" height=\"80\">";
    }

    // Crea una tarjeta en el tablero
    function displaySquare(App $app, int $i): void {
        $juego = $app->juego;
        $cartas = $juego->cartas;
        $imageName = $cartas[$i];
        $src = $app->getImagePath($imageName);
        switch (getDisplayValue($juego, $i)) {
            case DisplayValue::Descubierta:
                echo "<div class=\"carta descubierta\">" . displayCardImage($src) . "</div>";
                break;
            case DisplayValue::YaEncontrada:
                echo "<div class=\"carta recogida\"></div>";
                break;
            case DisplayValue::EncontradaEsteTurno:
                echo "<div class=\"carta encontrada\">" . displayCardImage($src) . "</div>";
                break;
            case DisplayValue::Clicable:
                echo "<a href=\"?carta={$i}\"><div class=\"carta clicable\"></div></a>";
                break;
            case DisplayValue::NoClicable:
                echo "<div class=\"carta\"></div>";
                break;
        }
    }

    function displayBoard(App $app): void {
        $juego = $app->juego;
    ?>
        <div class="board">
            <?php
                $cartas = $juego->cartas;
                for ($i = 0; $i < count($cartas); $i++):
                    displaySquare($app, $i);
                endfor;
            ?>
        </div>
    <?php
    }

    // Muestra la informacion de final de juego y el boton de volver a jugar
    function displayEndGame(App $app): void {
        $juego = $app->juego;
        if (!$juego->completado())
            return;

        $app->registrarPartida();

        $texto_fin_juego = ($juego->num_jugadores == 1) ?
             "¡Felicidades! Has ganado el juego en $juego->turno intentos." :
             "Juego finalizado";
        $handle_click_attr = (
            "location.href='main.php?restart=true&jugadores=" .
            $juego->num_jugadores .
            "'"
        )
        ?>
        <p><?= $texto_fin_juego ?></p>
        <button
            onclick=<?= $handle_click_attr ?>
            type=button
        >
            Jugar de nuevo
        </button>
        <?php
    }

    /**
     * @param array<int> $aciertos
     */
    function displayScores(Juego $juego, array $aciertos): void {
        $presenters = [];
        foreach([0, 1] as $indice_jugador){
            $presenters[] = new JugadorScorePresenter($indice_jugador, $juego, $aciertos);
        }
    ?>
        <div class="hbox">
            <?php foreach ($presenters as $presenter) : ?>
                <div class="aciertos <?php if ($presenter->is_selected) echo "selected"; ?>">
                    <p><?= $presenter->nombre_jugador ?></p>
                    <p class="aciertos-num"><?= $presenter->aciertos_jugador ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php
    }

    // Muestra informacion sobre el juego
    function displayInfo(App $app): void {
        $juego = $app->juego;
    ?>
        <div>
            <?php if ($juego->num_jugadores == 2): ?>
                <p>Jugador actual: <?= $juego->jugador_actual + 1 ?></p>
                <p>Turno: <?= $juego->turno ?></p>
            <?php else: ?>
                <p>Intento: <?= $juego->turno ?></p>
            <?php endif; ?>

            <?php $aciertos = $juego->aciertos; ?>
            <?php if ($juego->num_jugadores == 1): ?>
                <p>Aciertos: <?= $aciertos[0] ?></p>
            <?php else:
                displayScores($juego, $aciertos);
            endif; ?>

            <?php if ($juego->intentoRealizado()):
                $button_text = ($juego->num_jugadores == 2)
                    ? "Cambiar jugador"
                    : "Ocultar cartas";
                ?>
                <p>
                    <button onclick="location.href='?ocultar=true'">
                        <?= $button_text ?>
                    </button>
                </p>
            <?php endif; ?>

            <?php displayEndGame($app); ?>
        </div>
    <?php
    }

    function mainView(App $app, string $tema): void{
        ?>
        <!DOCTYPE html>
        <html>
            <head>
                <title>Juego de Memoria</title>
                <link rel="stylesheet" type="text/css" href="styles/main.css">
                <?php if ($tema == 'claro'): ?>
                    <link rel="stylesheet" type="text/css" href="styles/main-light.css">
                <?php else: ?>
                    <link rel="stylesheet" type="text/css" href="styles/main-dark.css">
                <?php endif ?>
            </head>
            <body>
                <h1>Juego de Memoria</h1>
                <div class='hbox'>
                    <div>
                        <?php
                            displayBoard($app)
                        ?>
                    </div>

                    <div class="info">
                        <?php displayInfo($app); ?>
                        <div class="boton-reiniciar-container">
                            <?php // Boton para reiniciar el juego en cualquier momento ?>
                            <form action="index.php" method="post">
                                <input type="hidden" name="restart" value="true">
                                <button type="submit" class="boton-reiniciar">
                                    Reiniciar juego
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </body>
        </html>
    <?php
    }
