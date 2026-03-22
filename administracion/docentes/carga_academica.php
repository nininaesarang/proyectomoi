<?php
session_start();
// Seguridad
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrativo') {
    header("Location: ../../index.php");
    exit;
}

include '../../conexion.php';

$mensaje = '';

// Si se presionó el botón para asignar la materia
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_docente = $_POST['id_docente'] ?? '';
    $id_materia = $_POST['id_materia'] ?? '';
    $id_grupo = $_POST['id_grupo'] ?? '';
    $id_ciclo = $_POST['id_ciclo'] ?? '';

    if (!empty($id_docente) && !empty($id_materia) && !empty($id_grupo) && !empty($id_ciclo)) {
        try {
            $sql_insert = "INSERT INTO carga_academica (id_docente, id_materia, id_grupo, id_ciclo) 
                           VALUES (?, ?, ?, ?)";
            $stmt_insert = $pdo->prepare($sql_insert);
            $stmt_insert->execute([$id_docente, $id_materia, $id_grupo, $id_ciclo]);
            
            $mensaje = "<div class='message-box success'>¡Carga académica asignada correctamente al docente!</div>";
        } catch (PDOException $e) {
            $mensaje = "<div class='message-box error'>Error al asignar: " . $e->getMessage() . "</div>";
        }
    } else {
        $mensaje = "<div class='message-box error'>Por favor, selecciona todas las opciones.</div>";
    }
}

// ==========================================
// CONSULTAS PARA LLENAR LOS DESPLEGABLES
// ==========================================
$docentes = $pdo->query("SELECT id_docente, nombre_completo FROM docentes WHERE estatus = 'Activo'")->fetchAll();
$materias = $pdo->query("SELECT id_materia, nombre_materia FROM materias")->fetchAll();
$grupos = $pdo->query("SELECT id_grupo, nombre_grupo FROM grupos")->fetchAll();
$ciclos = $pdo->query("SELECT id_ciclo, nombre_periodo FROM ciclos_escolares WHERE activo = 'Sí'")->fetchAll();

// ==========================================
// CONSULTA PARA VER LAS ASIGNACIONES ACTUALES
// ==========================================
try {
    $sql_cargas = "SELECT ca.id_carga_academica, d.nombre_completo, m.nombre_materia, g.nombre_grupo, c.nombre_periodo 
                   FROM carga_academica ca
                   INNER JOIN docentes d ON ca.id_docente = d.id_docente
                   INNER JOIN materias m ON ca.id_materia = m.id_materia
                   INNER JOIN grupos g ON ca.id_grupo = g.id_grupo
                   INNER JOIN ciclos_escolares c ON ca.id_ciclo = c.id_ciclo
                   ORDER BY ca.id_carga_academica DESC";
    $cargas = $pdo->query($sql_cargas)->fetchAll();
} catch (PDOException $e) {
    echo "Error al cargar las asignaciones: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carga Académica - Administración</title>
    <link rel="stylesheet" href="../../style.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #c4c2c2; }
        img.logo { width: 100px; }
        .badge { padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 12px; color: white;}
        .bg-purple { background-color: #6f42c1; }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <img src="../../img/logotec.png" alt="Tec San Pedro" class="logo">
            <h1>Panel Administrativo</h1>
            <nav>
                <ul>

                    <li><a href="docentes.php" class="active">Docentes</a></li>
                    <li><a href="../../logout.php">Salir</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="form-container" style="margin-bottom: 30px;">
            <h2>Asignar Carga Académica a Docente</h2>
            
            <?php echo $mensaje; ?>

            <form action="carga_academica.php" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label>1. Selecciona al Docente</label>
                        <select name="id_docente" style="width: 100%; padding: 10px; border-radius: 4px;" required>
                            <option value="">-- Elige un Profesor --</option>
                            <?php foreach ($docentes as $d): ?>
                                <option value="<?php echo $d['id_docente']; ?>"><?php echo htmlspecialchars($d['nombre_completo']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>2. Selecciona la Materia</label>
                        <select name="id_materia" style="width: 100%; padding: 10px; border-radius: 4px;" required>
                            <option value="">-- Elige una Asignatura --</option>
                            <?php foreach ($materias as $m): ?>
                                <option value="<?php echo $m['id_materia']; ?>"><?php echo htmlspecialchars($m['nombre_materia']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>3. Asignar al Grupo</label>
                        <select name="id_grupo" style="width: 100%; padding: 10px; border-radius: 4px;" required>
                            <option value="">-- Elige el Salón --</option>
                            <?php foreach ($grupos as $g): ?>
                                <option value="<?php echo $g['id_grupo']; ?>">Grupo <?php echo htmlspecialchars($g['nombre_grupo']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>4. Ciclo Escolar Actual</label>
                        <select name="id_ciclo" style="width: 100%; padding: 10px; border-radius: 4px;" required>
                            <option value="">-- Elige el Periodo --</option>
                            <?php foreach ($ciclos as $c): ?>
                                <option value="<?php echo $c['id_ciclo']; ?>"><?php echo htmlspecialchars($c['nombre_periodo']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-actions">
                    <a href="docentes.php" class="btn-dashboard btn-historial" style="text-decoration:none; padding:11px 20px;">Volver a Docentes</a>
                    <button type="submit" class="btn-primary">Guardar Asignación</button>
                </div>
            </form>
        </div>

        <div class="table-container">
            <h2>Cargas Académicas Activas</h2>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Docente</th>
                        <th>Materia Impartida</th>
                        <th>Grupo</th>
                        <th>Periodo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($cargas) > 0): ?>
                        <?php foreach ($cargas as $ca): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($ca['nombre_completo']); ?></strong></td>
                                <td><?php echo htmlspecialchars($ca['nombre_materia']); ?></td>
                                <td><span class="badge bg-purple">Grupo <?php echo htmlspecialchars($ca['nombre_grupo']); ?></span></td>
                                <td><?php echo htmlspecialchars($ca['nombre_periodo']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">No hay materias asignadas a los docentes todavía.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>