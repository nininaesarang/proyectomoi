<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrativo') {
    header("Location: ../index.php");
    exit;
}

include '../conexion.php';

$mensaje = '';

// Procesar formularios (Crear cobro o Marcar como pagado)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Asignar un nuevo cobro a un alumno
    if (isset($_POST['accion']) && $_POST['accion'] == 'nuevo_cobro') {
        $id_alumno = $_POST['id_alumno'];
        $concepto = $_POST['concepto'];
        $monto = $_POST['monto'];
        $fecha_limite = $_POST['fecha_limite'];

        try {
            $sql = "INSERT INTO finanzas_adeudos (id_alumno, concepto_pago, monto, fecha_vencimiento, pagado, referencia_bancaria) VALUES (?, ?, ?, ?, 'Pendiente')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id_alumno, $concepto, $monto, $fecha_limite]);
            $mensaje = "<div class='message-box success'>¡Cobro de $concepto asignado correctamente al alumno!</div>";
        } catch (PDOException $e) {
            $mensaje = "<div class='message-box error'>Error al asignar cobro: " . $e->getMessage() . "</div>";
        }
    }
    
    // 2. Marcar un cobro pendiente como PAGADO
    if (isset($_POST['accion']) && $_POST['accion'] == 'pagar') {
        $id_pago = $_POST['id_pago'];
        try {
            // Actualizamos el estatus y guardamos la fecha y hora exacta del pago
            $sql = "UPDATE finanzas_adeudos SET pagado = 'Pagado', fecha_pago = NOW() WHERE id_adeudo = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id_pago]);
            $mensaje = "<div class='message-box success'>¡El pago se ha registrado exitosamente!</div>";
        } catch (PDOException $e) {
            $mensaje = "<div class='message-box error'>Error al procesar el pago: " . $e->getMessage() . "</div>";
        }
    }
}

// CONSULTAS PARA LA INTERFAZ

// 1. Obtener lista de alumnos activos para el formulario
$alumnos = $pdo->query("SELECT id_alumno, matricula, nombre_completo FROM alumnos WHERE estatus = 'Activo' ORDER BY nombre_completo")->fetchAll();

// 2. Obtener el historial completo de pagos (Pendientes y Pagados)
$sql_pagos = "SELECT p.*, a.matricula, a.nombre_completo 
              FROM finanzas_adeudos p 
              INNER JOIN alumnos a ON p.id_alumno = a.id_alumno 
              ORDER BY p.pagado DESC, p.fecha_vencimiento ASC";
$lista_pagos = $pdo->query($sql_pagos)->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Pagos - Administración</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #c4c2c2; }
        img.logo { width: 100px; }
        .badge { padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 12px; color: white;}
        .bg-green { background-color: #28a745; }
        .bg-red { background-color: #dc3545; }
        .bg-warning { background-color: #ffc107; color: #212529; }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <img src="../img/logotec.png" alt="Tec San Pedro" class="logo">
            <h1 style="margin: 0 20px;">Panel Administrativo</h1>
            <nav>
                <ul>
                    <li><a href="admin.php">Admisión</a></li>
                    <li><a href="alumnos/lista_alumnos.php">Alumnos</a></li>
                    <li><a href="docentes/docentes.php">Docentes</a></li>
                    <li><a href="../administracion/horarios.php">Horarios</a></li>
                    <li><a href="academica/academica.php">Académica</a></li>
                    <li><a href="pagos.php" class="active">Pagos</a></li>
                    <li><a href="reportes.php">Reportes</a></li>
                    <li><a href="mensajes.php">Mensajes</a></li>
                    <li><a href="../logout.php">Salir</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="form-container" style="max-width: 900px; margin-bottom: 30px;">
            <h2>Asignar Nuevo Cobro a Alumno</h2>
            <?php echo $mensaje; ?>
            
            <form action="pagos.php" method="POST">
                <input type="hidden" name="accion" value="nuevo_cobro">
                
                <div class="form-row">
                    <div class="form-group" style="flex: 2;">
                        <label>1. Selecciona al Alumno</label>
                        <select name="id_alumno" style="width: 100%; padding: 10px; border-radius: 4px;" required>
                            <option value="">-- Elige un Alumno --</option>
                            <?php foreach ($alumnos as $a): ?>
                                <option value="<?php echo $a['id_alumno']; ?>">
                                    <?php echo htmlspecialchars($a['matricula'] . ' - ' . $a['nombre_completo']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group" style="flex: 1;">
                        <label>2. Concepto de Pago</label>
                        <select name="concepto" style="width: 100%; padding: 10px; border-radius: 4px;" required>
                            <option value="Reinscripción Semestral">Reinscripción Semestral</option>
                            <option value="Curso de Inglés">Curso de Inglés</option>
                            <option value="Constancia de Estudios">Constancia de Estudios</option>
                            <option value="Examen Extraordinario">Examen Extraordinario</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>3. Monto a Cobrar ($)</label>
                        <input type="number" name="monto" step="0.01" min="1" placeholder="Ej. 1500.00" required>
                    </div>
                    <div class="form-group">
                        <label>4. Referencia bancaria</label>
                        <input type="text" name="referencia_bancaria">
                    </div>

                    <div class="form-group">
                        <label>5. Fecha Límite de Pago</label>
                        <input type="date" name="fecha_limite" required>
                    </div>
                    
                </div>
                
                <div class="form-actions" style="text-align: right;">
                    <button type="submit" class="btn-primary">Generar Cobro Pendiente</button>
                </div>
            </form>
        </div>

        <div class="table-container" style="max-width: 1000px; margin: 0 auto;">
            <h2>Estado de Cuenta Escolar</h2>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Matrícula</th>
                        <th>Alumno</th>
                        <th>Concepto</th>
                        <th>Monto</th>
                        <th>Vencimiento</th>
                        <th>Estatus</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($lista_pagos) > 0): ?>
                        <?php foreach($lista_pagos as $p): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($p['matricula']); ?></strong></td>
                                <td><?php echo htmlspecialchars($p['nombre_completo']); ?></td>
                                <td><?php echo htmlspecialchars($p['concepto_pago']); ?></td>
                                <td>$<?php echo number_format($p['monto'], 2); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($p['fecha_vencimiento'])); ?></td>
                                <td>
                                    <?php if($p['pagado'] == 'Pagado'): ?>
                                        <span class="badge bg-green">Pagado</span><br>
                                        <small style="color:gray; font-size:10px;"><?php echo date('d/m/Y', strtotime($p['fecha_pago'])); ?></small>
                                    <?php else: ?>
                                        <span class="badge bg-red">Pendiente</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($p['pagado'] == 'Pendiente'): ?>
                                        <form action="pagos.php" method="POST" style="margin:0;">
                                            <input type="hidden" name="accion" value="pagar">
                                            <input type="hidden" name="id_pago" value="<?php echo $p['id_pago']; ?>">
                                            <button type="submit" class="btn-dashboard btn-aceptar" style="padding: 6px 10px; font-size: 12px; border:none; cursor:pointer;">Cobrar</button>
                                        </form>
                                    <?php else: ?>
                                        <span style="color: #28a745; font-weight:bold;">✓ Liquidado</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 20px;">No hay registros de pagos ni adeudos.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>