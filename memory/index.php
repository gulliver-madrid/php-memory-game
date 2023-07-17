<?php
    require_once "helpers.php";

    session_start();
    if (isset($_GET['restart'])){
        clearSession();
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Juego de Memoria</title>
    <link rel="stylesheet" type="text/css" href="index.css">
</head>
<body>
    <div class="container">
        <h2 class="title">Bienvenido al Juego de Memoria</h2>
        <a href="main.php?jugadores=1" class="start-button">Un jugador</a>
        <a href="main.php?jugadores=2" class="start-button">Dos jugadores</a>
    </div>
</body>
</html>
