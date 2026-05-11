<?php
session_start();
require '../conexion.php';

$sql = "CALL sp_obtener_lista_alumnos_grupo()";
$stmt = $pdo->query($sql);
$alumnos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Docentes - Grupo</title>
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
            <h1>Lista de alumnos</h1>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Matrícula</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Grupo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($alumnos)): ?>
                        <?php foreach($alumnos as $datitos): ?>
                            <tr>
                                <td><?php echo $datitos['matricula']; ?></td>
                                <td><?php echo $datitos['nombre_completo']; ?></td>
                                <td><?php echo $datitos['correo']; ?></td>
                                <td><?php echo $datitos['nombre_grupo']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">Aún no hay alumnos registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <a href="gestion_academica.php" class="btn-dashboard btn-historial">Volver</a>
        </div>
    </main>
</body>
</html>



