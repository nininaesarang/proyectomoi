<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrativo') {
    header("Location: ../../index.php");
    exit;
}

include '../../conexion.php';

try {

    $sql = "SELECT a.id_alumno, a.matricula, a.nombre_completo, a.carrera, a.semestre_actual, a.estatus, g.nombre_grupo 
            FROM alumnos a 
            LEFT JOIN grupos g ON a.id_grupo = g.id_grupo
            ORDER BY a.semestre_actual ASC, a.matricula ASC";
    $stmt = $pdo->query($sql);
    $alumnos_inscritos = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error en la base de datos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Alumnos - Administración</title>
    <link rel="stylesheet" href="../../style.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #c4c2c2; }
        img.logo { width: 100px; }
        .badge { padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 12px; color: white;}
        .bg-green { background-color: #28a745; }
        .bg-gray { background-color: #6c757d; }
        .bg-red { background-color: #dc3545; }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <img src="../../img/logotec.png" alt="Tec San Pedro" class="logo">
            <h1>Panel Administrativo</h1>
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
        <div class="table-container" style="max-width: 1100px;">
            <h2>Panel de Alumnos</h2>

            <table class="history-table">
                <thead>
                    <tr>
                        <th>Matrícula</th>
                        <th>Nombre del Alumno</th>
                        <th>Carrera</th>
                        <th>Semestre</th>
                        <th>Grupo</th>
                        <th>Estatus</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($alumnos_inscritos) > 0): ?>
                        <?php foreach ($alumnos_inscritos as $alum): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($alum['matricula']); ?></strong></td>
                                <td><?php echo htmlspecialchars($alum['nombre_completo'] ?? 'Sin Nombre'); ?></td>
                                <td><?php echo htmlspecialchars($alum['carrera']); ?></td>
                                <td><?php echo htmlspecialchars($alum['semestre_actual']); ?></td>
                                
                                <td>
                                    <?php echo $alum['nombre_grupo'] ? htmlspecialchars($alum['nombre_grupo']) : '<span style="color:red; font-size:12px;">Sin asignar</span>'; ?>
                                </td>

                                <td>
                                    <?php if($alum['estatus'] == 'Activo'): ?>
                                        <span class="badge bg-green">Activo</span>
                                    <?php elseif($alum['estatus'] == 'Baja Temporal'): ?>
                                        <span class="badge bg-gray">Baja Temporal</span>
                                    <?php else: ?>
                                        <span class="badge bg-red"><?php echo htmlspecialchars($alum['estatus']); ?></span>
                                    <?php endif; ?>
                                </td>

                                <td style="white-space: nowrap; text-align: center;">
                                    <div style="display: flex; flex-direction: column; gap: 6px; align-items: center;">
                                        <a href="asignar_alumno.php?id=<?php echo $alum['id_alumno']; ?>" class="btn-details" style="text-decoration:none; display:inline-block; padding:8px 15px; width: 140px; box-sizing: border-box; border-radius: 4px;">Editar / Grupo</a>
                                        
                                        <a href="carga_alumno.php?id=<?php echo $alum['id_alumno']; ?>" class="btn-primary" style="text-decoration:none; display:inline-block; padding:7px 14px; width: 140px; box-sizing: border-box; border-radius: 4px;">Materias</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center;">No hay alumnos inscritos en el sistema.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>