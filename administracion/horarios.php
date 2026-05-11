<?php
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrativo') {
    header("Location: ../../index.php");
    exit;
}

include '../conexion.php';
$mensaje = '';


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crear_horario'])) {
    $id_carga = $_POST['id_carga_academica'];
    $dia = $_POST['dia_semana'];
    $inicio = $_POST['hora_inicio'];
    $fin = $_POST['hora_fin'];
    $aula = $_POST['aula'];

    try {
        $sql = "CALL sp_insertar_horario(?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_carga, $dia, $inicio, $fin, $aula]);
        $mensaje = "<div class='message-box success'>¡Horario asignado con éxito!</div>";
    } catch (PDOException $e) {
        $mensaje = "<div class='message-box error'>Error al insertar: " . $e->getMessage() . "</div>";
    }
}


$clases = $pdo->query("CALL sp_obtener_clases_horarios()")->fetchAll();


$horarios_actuales = $pdo->query("CALL sp_obtener_horarios_actuales()")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin - Gestión de Horarios</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .grid-form { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .full-width { grid-column: span 2; }
    </style>
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
            <img src="../img/logotec.png" alt="Instituto Tecnológico Superior de San Pedro" class="logo">
            <h1>Panel Administrativo</h1>
            <nav>
                <ul>
                    <li><a href="admin.php" class="active">Admisión</a></li>
                    <li><a href="alumnos/lista_alumnos.php">Alumnos</a></li>
                    <li><a href="docentes/docentes.php">Docentes</a></li>
                    <li><a href="horarios.php">Horarios</a></li>
                    <li><a href="academica/academica.php">Académica</a></li>
                    <li><a href="pagos.php">Pagos</a></li>
                    <li><a href="reportes.php">Reportes</a></li>
                    <li><a href="mensajes.php">Mensajes</a></li>
                    <li><a href="../logout.php">Salir</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main class="main-content">
        <div class="form-container" style="max-width: 800px;">
            <h1>Registrar Nuevo Bloque de Horario</h1>
            <?= $mensaje ?>

            <form method="POST" class="form-group">
                <div class="grid-form">
                    <div class="full-width">
                        <label>Materia y Grupo:</label>
                        <select name="id_carga_academica" required>
                            <?php foreach($clases as $c): ?>
                                <option value="<?= $c['id_carga_academica'] ?>">
                                    <?= $c['nombre_materia'] ?> - Grupo: <?= $c['nombre_grupo'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label>Día de la Semana:</label>
                        <select name="dia_semana" required>
                            <option value="lunes">Lunes</option>
                            <option value="martes">Martes</option>
                            <option value="miercoles">Miércoles</option>
                            <option value="jueves">Jueves</option>
                            <option value="viernes">Viernes</option>
                        </select>
                    </div>
                    <div>
                        <label>Aula:</label>
                        <input type="text" name="aula" placeholder="Ej: A7, LAB ISC" required>
                    </div>
                    <div>
                        <label>Hora Inicio:</label>
                        <input type="time" name="hora_inicio" required>
                    </div>
                    <div>
                        <label>Hora Fin:</label>
                        <input type="time" name="hora_fin" required>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" name="crear_horario" class="btn-dashboard btn-primary">Guardar en Horarios</button>
                </div>
            </form>
        </div>

        <div class="table-container" style="margin-top: 30px;">
            <h3>Últimos Horarios Asignados</h3>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Materia</th>
                        <th>Día</th>
                        <th>Hora</th>
                        <th>Aula</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($horarios_actuales as $h): ?>
                    <tr>
                        <td><?= $h['nombre_materia'] ?> (<?= $h['nombre_grupo'] ?>)</td>
                        <td style="text-transform: capitalize;"><?= $h['dia_semana'] ?></td>
                        <td><?= $h['hora_inicio'] ?> de <?= $h['hora_fin'] ?></td>
                        <td><strong><?= $h['aula'] ?></strong></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>