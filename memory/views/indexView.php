<?php
    function indexView(int $num_tarjetas): void {
        ?>
        <!DOCTYPE html>
        <html>
            <head>
                <title>Juego de Memoria</title>
                <link rel="stylesheet" type="text/css" href="styles/index.css">
                <?php if ($_SESSION['tema'] == 'claro'): ?>
                    <link rel="stylesheet" type="text/css" href="styles/light.css">
                <?php else: ?>
                    <link rel="stylesheet" type="text/css" href="styles/dark.css">
                <?php endif ?>
            </head>
            <body>
                <div class="container">
                    <h2 class="title">Bienvenido al Juego de Memoria</h2>
                    <div class="hbox">
                        <a href="main.php?restart=true&jugadores=1" class="start-button">Un jugador</a>
                        <a href="main.php?restart=true&jugadores=2" class="start-button">Dos jugadores</a>
                    </div>
                </div>
                <div class="container">
                    <form method="post">
                        <label>
                            <input type="radio" name="tema" value="claro"
                            <?php echo (!isset($_SESSION['tema']) || $_SESSION['tema'] == 'claro') ? 'checked' : ''; ?>>
                            Tema Claro
                        </label>
                        <label>
                            <input type="radio" name="tema" value="oscuro"
                            <?php echo (isset($_SESSION['tema']) && $_SESSION['tema'] == 'oscuro') ? 'checked' : ''; ?>>
                            Tema Oscuro
                        </label>
                        <br/>
                        <br/>
                        <input type="submit" value="Aplicar">
                    </form>
                </div>
                <hr>
                <h2>Seleccionar número de tarjetas</h2>
                <form method="post">
                    <label for="num_tarjetas">Número de tarjetas (entre 2 y 8):</label>
                    <input type="number" name="num_tarjetas" id="num_tarjetas" min="2" max="8" value="<?= $num_tarjetas ?>" required>
                    <input type="submit" value="Aplicar">
                </form>
            </body>
        </html>
    <?php
    }
