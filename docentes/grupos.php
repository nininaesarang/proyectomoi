<?php
session_start();
require '../conexion.php';

$id_usuario = $_SESSION['id_usuario'];

/* que usuario es el docente*/ 
$stmt = $pdo->prepare("CALL consultar_docente(?)");
$stmt->execute([$id_usuario]);
$docente = $stmt->fetch(PDO::FETCH_ASSOC);

$id_docente = $docente['id_docente'];

$stmt = $pdo->prepare("CALL consultar_grupos_a(?)");
$stmt->execute([$id_docente]);
$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Docentes - Grupos asignados</title>
    <link rel="stylesheet" href="../style.css">
</head>

<!-- Diseñito -->
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
    <main class="main-container"><br><br>
        <div class="table-container">
            <h1>Grupos asignados</h1>
            <table class="history-table">
                <thead>
                    <!-- Tablita -->
                    <tr>
                        <th>Materia</th>
                        <th>Grupo</th>
                        <th>Ciclo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($resultado as $fila){ ?>

                <!-- Como se mira -->

                    <tr>
                        <td><?php echo $fila['nombre_materia']; ?></td>
                        <td><?php echo $fila['nombre_grupo']; ?></td>
                        <td><?php echo $fila['nombre_periodo']; ?></td>
                    </tr>

                    <?php } ?>
                </tbody>
            </table>
            <div style="text-align:center;">
                <a class="btn-dashboard btn-historial" href="gestion_academica.php">Volver</a>
            </div>
        </div>
    </main>
</body>
</html>


