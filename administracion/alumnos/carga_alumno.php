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
    $materias_seleccionadas = $_POST['materias'] ?? [];

    try {
        $pdo->beginTransaction();

        $sql_delete = "DELETE FROM carga_alumnos WHERE id_alumno = ?";
        $stmt_delete = $pdo->prepare($sql_delete);
        $stmt_delete->execute([$id_alumno]);

 
        if (!empty($materias_seleccionadas)) {
            $sql_insert = "INSERT INTO carga_alumnos (id_alumno, id_carga_academica) VALUES (?, ?)";
            $stmt_insert = $pdo->prepare($sql_insert);
            foreach ($materias_seleccionadas as $id_carga) {
                $stmt_insert->execute([$id_alumno, $id_carga]);
            }
        }

        $pdo->commit();
        $mensaje = "<div class='message-box success'>¡Materias asignadas correctamente al alumno!</div>";
    } catch (PDOException $e) {
        $pdo->rollBack();
        $mensaje = "<div class='message-box error'>Error al guardar: " . $e->getMessage() . "</div>";
    }
}

$stmt_alum = $pdo->prepare("SELECT matricula, nombre_completo, semestre_actual FROM alumnos WHERE id_alumno = ?");
$stmt_alum->execute([$id_alumno]);
$alumno = $stmt_alum->fetch();

$sql_clases = "SELECT ca.id_carga_academica, m.nombre_materia, m.creditos, d.nombre_completo AS profe, g.nombre_grupo, c.nombre_periodo 
               FROM carga_academica ca
               INNER JOIN materias m ON ca.id_materia = m.id_materia
               INNER JOIN docentes d ON ca.id_docente = d.id_docente
               INNER JOIN grupos g ON ca.id_grupo = g.id_grupo
               INNER JOIN ciclos_escolares c ON ca.id_ciclo = c.id_ciclo
               ORDER BY g.nombre_grupo ASC, m.nombre_materia ASC";
$clases_abiertas = $pdo->query($sql_clases)->fetchAll();


$stmt_inscritas = $pdo->prepare("SELECT id_carga_academica FROM carga_alumnos WHERE id_alumno = ?");
$stmt_inscritas->execute([$id_alumno]);
$inscritas_array = $stmt_inscritas->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carga Académica del Alumno</title>
    <link rel="stylesheet" href="../../style.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #c4c2c2; }
        img.logo { width: 100px; }
        .info-box { background: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center; font-size: 16px;}
        .checkbox-container { display: flex; align-items: center; gap: 10px; padding: 10px; border-bottom: 1px solid #ddd; }
        .checkbox-container:hover { background-color: #f1f1f1; }
        .badge { padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 12px; color: white;}
        .bg-purple { background-color: #6f42c1; }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <img src="../../img/logotec.png" alt="Tec San Pedro" class="logo">
            <h1 style="margin: 0 20px;">Panel Administrativo</h1>
            <nav>
                <ul>
                    <li><a href="../admin.php">Admisión</a></li>
                    <li><a href="lista_alumnos.php" class="active">Alumnos</a></li>
                    <li><a href="../docentes/docentes.php">Docentes</a></li>
                    <li><a href="../horarios.php">Horarios</a></li>
                    <li><a href="../academica/academica.php">Académica</a></li>
                    <li><a href="../pagos.php">Pagos</a></li>
                    <li><a href="../reportes.php">Reportes</a></li>
                    <li><a href="../mensajes.php">Mensajes</a></li>
                    <li><a href="../../logout.php">Salir</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="form-container" style="max-width: 900px;">
            <h2>Asignar Materias</h2>
            
            <div class="info-box">
                <strong>Matrícula:</strong> <?php echo htmlspecialchars($alumno['matricula']); ?> | 
                <strong>Alumno:</strong> <?php echo htmlspecialchars($alumno['nombre_completo']); ?> |
                <strong>Semestre:</strong> <?php echo htmlspecialchars($alumno['semestre_actual']); ?>
            </div>

            <?php echo $mensaje; ?>

            <form action="carga_alumno.php?id=<?php echo $id_alumno; ?>" method="POST">
                <h3 style="margin-bottom: 15px;">Clases Disponibles en el Periodo</h3>
                
                <div style="background: white; border: 1px solid #ccc; border-radius: 5px; padding: 10px; max-height: 400px; overflow-y: auto;">
                    <?php if (count($clases_abiertas) > 0): ?>
                        <?php foreach ($clases_abiertas as $clase): ?>
                            <?php $checked = in_array($clase['id_carga_academica'], $inscritas_array) ? 'checked' : ''; ?>
                            <div class="checkbox-container">
                                <input type="checkbox" name="materias[]" value="<?php echo $clase['id_carga_academica']; ?>" id="clase_<?php echo $clase['id_carga_academica']; ?>" <?php echo $checked; ?> style="transform: scale(1.5); margin-right: 10px;">
                                <label for="clase_<?php echo $clase['id_carga_academica']; ?>" style="cursor: pointer; width: 100%;">
                                    <strong><?php echo htmlspecialchars($clase['nombre_materia']); ?></strong> 
                                    (Créditos: <?php echo $clase['creditos']; ?>) <br>
                                    <span style="color: #555; font-size: 14px;">👨‍🏫 Docente: <?php echo htmlspecialchars($clase['profe']); ?> | </span>
                                    <span class="badge bg-purple">Grupo <?php echo htmlspecialchars($clase['nombre_grupo']); ?></span>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="text-align: center; color: red;">No hay clases abiertas. Primero asigna materias a los docentes.</p>
                    <?php endif; ?>
                </div>

                <div class="form-actions" style="margin-top: 20px; display: flex; justify-content: space-between;">
                    <a href="lista_alumnos.php" class="btn-dashboard btn-historial" style="text-decoration:none; padding:11px 20px;">Regresar a Lista</a>
                    <button type="submit" class="btn-primary">Guardar Carga Académica</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>