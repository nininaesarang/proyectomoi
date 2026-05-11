<?php
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrativo') {
    header("Location: ../../index.php");
    exit;
}

include '../../conexion.php';

$id_alumno = $_GET['id'] ?? null;
if (!$id_alumno) {
    header("Location: lista_alumnos.php");
    exit;
}

$mensaje = '';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_grupo = !empty($_POST['id_grupo']) ? $_POST['id_grupo'] : null;
    $semestre = $_POST['semestre'] ?? 1;
    $estatus = $_POST['estatus'] ?? 'Activo';

    try {
        $sql_upd = "CALL sp_actualizar_alumno_grupo(?, ?, ?, ?)";
        $stmt_upd = $pdo->prepare($sql_upd);
        $stmt_upd->execute([$id_alumno, $id_grupo, $semestre, $estatus]);
        $stmt_upd->closeCursor();
        $mensaje = "<div class='message-box success'>¡Datos del alumno actualizados correctamente!</div>";
    } catch (PDOException $e) {
        $mensaje = "<div class='message-box error'>Error al actualizar: " . $e->getMessage() . "</div>";
    }
}


$sql_alum = "CALL sp_obtener_alumno_por_id(?)";
$stmt_alum = $pdo->prepare($sql_alum);
$stmt_alum->execute([$id_alumno]);
$alumno = $stmt_alum->fetch();
$stmt_alum->closeCursor();

if (!$alumno) {
    echo "Alumno no encontrado.";
    exit;
}


$sql_grupos = "CALL sp_obtener_grupos_lista()";
$stmt_grupos = $pdo->query($sql_grupos);
$grupos = $stmt_grupos->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Alumno</title>
    <link rel="stylesheet" href="../../style.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #c4c2c2; }
        img.logo { width: 100px; }
        .info-box { background: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center; font-size: 18px;}
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <img src="../../img/logotec.png" alt="Tec San Pedro" class="logo">
            <h1>Panel Administrativo</h1>
            <nav>
                <ul>
                    <li><a href="lista_alumnos.php" class="active">Alumnos</a></li>
                    <li><a href="../../logout.php">Salir</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="form-container">
            <h2>Asignación y Estatus del Alumno</h2>
            
            <div class="info-box">
                <strong>Matrícula:</strong> <?php echo htmlspecialchars($alumno['matricula']); ?> <br>
                <strong>Nombre:</strong> <?php echo htmlspecialchars($alumno['nombre_completo'] ?? 'Sin Nombre'); ?> <br>
                <strong>Carrera:</strong> <?php echo htmlspecialchars($alumno['carrera']); ?>
            </div>

            <?php echo $mensaje; ?>

            <form action="asignar_alumno.php?id=<?php echo $id_alumno; ?>" method="POST">
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Semestre Actual</label>
                        <input type="number" name="semestre" min="1" max="13" value="<?php echo htmlspecialchars($alumno['semestre_actual']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Asignar a Grupo</label>
                        <select name="id_grupo" style="width: 100%; padding: 10px; border-radius: 4px;">
                            <option value="">-- Sin Grupo Asignado --</option>
                            <?php foreach($grupos as $g): ?>
                                <option value="<?php echo $g['id_grupo']; ?>" <?php echo $alumno['id_grupo'] == $g['id_grupo'] ? 'selected' : ''; ?>>
                                    Grupo <?php echo htmlspecialchars($g['nombre_grupo']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Estatus Escolar</label>
                    <select name="estatus" style="width: 100%; padding: 10px; border-radius: 4px; font-weight: bold;">
                        <option value="Activo" <?php echo $alumno['estatus'] == 'Activo' ? 'selected' : ''; ?>>Activo (Regular)</option>
                        <option value="Baja Temporal" <?php echo $alumno['estatus'] == 'Baja Temporal' ? 'selected' : ''; ?>>Baja Temporal</option>
                        <option value="Baja Definitiva" <?php echo $alumno['estatus'] == 'Baja Definitiva' ? 'selected' : ''; ?>>Baja Definitiva</option>
                        <option value="Egresado" <?php echo $alumno['estatus'] == 'Egresado' ? 'selected' : ''; ?>>Egresado</option>
                    </select>
                </div>
                
                <div class="form-actions">
                    <a href="lista_alumnos.php" class="btn-dashboard btn-historial" style="text-decoration:none; padding:11px 20px;">Regresar a la Lista</a>
                    <button type="submit" class="btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>