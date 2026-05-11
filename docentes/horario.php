<?php
session_start();
require '../conexion.php';


$id_usuario = $_SESSION['id_usuario'];

$stmt = $pdo->prepare("CALL sp_obtener_id_docente(?)");
$stmt->execute([$id_usuario]);
$docente = $stmt->fetch();
$stmt->closeCursor();

$id_docente = $docente['id_docente'];

$sql = "CALL sp_obtener_horario_docente_detalle(?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_docente]);
$horario = $stmt->fetchAll();
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Docente - Horario</title>
    <link rel="stylesheet" href="../style.css">
</head>
<style>
h3, h1 {text-align: center;}
img {width: 100px;}
a {text-align: center;}
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #c4c2c2;
}
</style>
<body>
    <header>
        <div class="header-container">
            <img src="../img/logotec.png" alt="Instituto Tecnológico Superior de San Pedro">
            <h1>Docentes</h1>
            <nav>
                <ul>
                    <li><a href="docentes.php">Inicio</a></li>
                    <li><a href="gestion_academica.php">Gestión Académica</a></li>
                    <li><a href="gestion_calificaciones.php">Gestión de Calificaciones</a></li>
                    <li><a href="asistencias.php">Control de Asistencias</a></li>
                    <li><a href="aula_virtual.php">Aula Virtual</a></li>
                    <li><a href="seg_academico.php">Seguimiento Académico</a></li>
                    <li><a href="../logout.php">Salir</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main class="main-content">
        <div class="table-container">
            <h1>Horario</h1>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Materia</th>
                        <th>Grupo</th>
                        <th>Día</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th>Aula</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($horario as $fila){ ?>
                        <tr>
                            <td><?php echo $fila['nombre_materia']; ?></td>
                            <td><?php echo $fila['nombre_grupo']; ?></td>
                            <td><?php echo $fila['dia_semana']; ?></td>
                            <td><?php echo $fila['hora_inicio']; ?></td>
                            <td><?php echo $fila['hora_fin']; ?></td>
                            <td><?php echo $fila['aula']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div style="text-align: center;">
                <a class="btn-dashboard btn-historial" href="gestion_academica.php">Volver</a>
            </div>
        </div>
    </main>
</body>
</html>

