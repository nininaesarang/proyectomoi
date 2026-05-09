<?php
session_start();
require '../conexion.php';
require 'header_alumno.php'; 

if(!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'alumno'){
    die("Error: Debes iniciar sesión como alumno");
}

$id_usuario = $_SESSION['id_usuario'];
$mensajes = [];

try {
    $stmt_mensajes = $pdo->prepare("CALL consultar_comentarios(?)");
    $stmt_mensajes->execute([$id_usuario]);

    $mensajes = $stmt_mensajes->fetchAll(PDO::FETCH_ASSOC);
    $stmt_mensajes->closeCursor();
    
    $stmt_ss = $pdo->prepare("CALL menu_ss(?)");
    $stmt_ss->execute([$id_usuario]);
    $tiene_registro_ss = (int)$stmt_ss->fetchColumn() > 0;
    $stmt_ss->closeCursor(); 

} catch (PDOException $e) {
    $error_message = "Error en el sistema: " . $e->getMessage();
    $mensajes = [];
    $tiene_registro_ss = false;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comentarios del Docente</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        h1 { text-align: center; margin-top: 20px; }
        img {width: 100px;}
        a {text-align: center;}
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #c4c2c2;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <img src="../img/logotec.png" alt="Instituto Tecnológico Superior de San Pedro">
            <h1>Alumnos</h1>
            <nav>
                <ul>
                    <li><a href="#">Aula Virtual</a></li>
                    <li><a href="perfil.php">Perfil</a></li>
                    <li><a href="horarios.php">Horario</a></li>
                    <li><a href="calificaciones.php">Calificaciones</a></li>
                    <li><a href="finanzas.php">Estado Financiero</a></li>
                    <li><a href="club.php">Club Escolar</a></li>
                    <?php if (isset($_SESSION['id_usuario']) && $_SESSION['rol'] == 'alumno' && $tiene_registro_ss): ?>
                    <li><a href="servicio.php">Servicio Social</a></li>
                    <?php endif; ?>
                    <li><a href="../logout.php">Salir</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main class="main-content">
        <div class="table-container">
            <h1>Comentarios del Docente</h1>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Docente</th>
                        <th>Asunto</th>
                        <th>Mensaje</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($mensajes)): ?>
                        <?php foreach($mensajes as $m): ?>
                            <tr>
                                <td><?= htmlspecialchars($m['nombre_completo']) ?></td>
                                <td><?= htmlspecialchars($m['asunto']) ?></td>
                                <td><?= nl2br(htmlspecialchars($m['mensaje'])) ?></td>
                                <td><?= htmlspecialchars($m['fecha_envio']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">No tienes comentarios registrados actualmente.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <br>
            <a class="btn-dashboard btn-historial" href="alumnos.php">Volver al Aula Virtual</a>
        </div>
    </main>
</body>
</html>