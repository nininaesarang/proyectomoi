<?php
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrativo') {
    header("Location: ../../index.php");
    exit;
}

include '../../conexion.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    

    if (isset($_POST['accion']) && $_POST['accion'] == 'nueva_carga') {
        $id_docente = $_POST['id_docente'] ?? '';
        $id_materia = $_POST['id_materia'] ?? '';
        $id_grupo = $_POST['id_grupo'] ?? '';
        $id_ciclo = $_POST['id_ciclo'] ?? '';

        if (!empty($id_docente) && !empty($id_materia) && !empty($id_grupo) && !empty($id_ciclo)) {
            try {
                
                $sql_insert = "CALL sp_asignar_carga_academica(?, ?, ?, ?)";
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
    

    if (isset($_POST['accion']) && $_POST['accion'] == 'toggle_acta') {
        $id_carga = $_POST['id_carga'];
        $nuevo_estado = $_POST['nuevo_estado']; 
        
        try {
            $sql_toggle = "CALL sp_toggle_estado_acta(?, ?)";
            $stmt_toggle = $pdo->prepare($sql_toggle);
            $stmt_toggle->execute([$nuevo_estado, $id_carga]);
            
            $texto = $nuevo_estado == 1 ? "ABIERTA" : "CERRADA";
            $mensaje = "<div class='message-box success' style='text-align:center;'>¡El acta ahora está <strong>$texto</strong>!</div>";
        } catch (PDOException $e) {
            $mensaje = "<div class='message-box error'>Error al cambiar estado del acta: " . $e->getMessage() . "</div>";
        }
    }
}




$docentes = $pdo->query("CALL sp_obtener_docentes_activos()")->fetchAll();
$materias = $pdo->query("CALL sp_obtener_materias_simples()")->fetchAll();
$grupos   = $pdo->query("CALL sp_obtener_grupos_simples()")->fetchAll();
$ciclos   = $pdo->query("CALL sp_obtener_ciclos_activos()")->fetchAll();




try {
    
    $sql_cargas = "CALL sp_obtener_cargas_academicas_detalle()";
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
        .bg-green { background-color: #28a745; }
        .bg-red { background-color: #dc3545; }
        .bg-dark { background-color: #343a40; }
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
                    <li><a href="../alumnos/lista_alumnos.php">Alumnos</a></li>
                    <li><a href="docentes.php" class="active">Docentes</a></li>
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
        <div class="form-container" style="max-width: 900px; margin-bottom: 30px;">
            <h2>Asignar Carga Académica a Docente</h2>
            
            <?php echo $mensaje; ?>

            <form action="carga_academica.php" method="POST">
                <input type="hidden" name="accion" value="nueva_carga">
                
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

        <div class="table-container" style="max-width: 1100px; margin: 0 auto;">
            <h2>Cargas Académicas y Control de Actas</h2>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Docente</th>
                        <th>Materia Impartida</th>
                        <th>Grupo</th>
                        <th>Periodo</th>
                        <th>Estatus del Acta</th>
                        <th>Acción</th>
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
                                <td>
                                    <?php if(isset($ca['acta_abierta']) && $ca['acta_abierta'] == 1): ?>
                                        <span class="badge bg-green">Abierta</span>
                                    <?php else: ?>
                                        <span class="badge bg-red">Cerrada</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form action="carga_academica.php" method="POST" style="margin:0;">
                                        <input type="hidden" name="accion" value="toggle_acta">
                                        <input type="hidden" name="id_carga" value="<?php echo $ca['id_carga_academica']; ?>">
                                        
                                        <?php if(isset($ca['acta_abierta']) && $ca['acta_abierta'] == 1): ?>
                                            <input type="hidden" name="nuevo_estado" value="0">
                                            <button type="submit" class="btn-dashboard" style="padding: 6px 10px; font-size: 12px; border:none; cursor:pointer; background-color: #6c757d; color:white;">Cerrar Acta</button>
                                        <?php else: ?>
                                            <input type="hidden" name="nuevo_estado" value="1">
                                            <button type="submit" class="btn-dashboard btn-aceptar" style="padding: 6px 10px; font-size: 12px; border:none; cursor:pointer; background-color: #28a745;">Abrir Acta</button>
                                        <?php endif; ?>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">No hay materias asignadas a los docentes todavía.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>