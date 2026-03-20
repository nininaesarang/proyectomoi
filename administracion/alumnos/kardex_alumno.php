<?php
session_start();
// Seguridad
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

// 1. Datos del alumno
$stmt_alum = $pdo->prepare("SELECT matricula, nombre_completo, carrera, semestre_actual FROM alumnos WHERE id_alumno = ?");
$stmt_alum->execute([$id_alumno]);
$alumno = $stmt_alum->fetch();

if (!$alumno) {
    echo "Alumno no encontrado.";
    exit;
}

// 2. Historial del Kárdex para identificar materias y repeticiones
$sql_kardex = "SELECT k.*, m.nombre_materia, m.creditos, c.nombre_periodo 
               FROM kardex k
               INNER JOIN materias m ON k.id_materia = m.id_materia
               INNER JOIN ciclos_escolares c ON k.id_ciclo = c.id_ciclo
               WHERE k.id_alumno = ?
               ORDER BY c.id_ciclo ASC, m.nombre_materia ASC";
$stmt_kardex = $pdo->prepare($sql_kardex);
$stmt_kardex->execute([$id_alumno]);
$kardex = $stmt_kardex->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kárdex del Alumno</title>
    <link rel="stylesheet" href="../../style.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #c4c2c2; }
        img.logo { width: 100px; }
        .info-box { background: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center; font-size: 16px;}
        .badge { padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 12px; color: white;}
        .bg-green { background-color: #28a745; }
        .bg-red { background-color: #dc3545; }
        .bg-dark { background-color: #343a40; }
        .bg-warning { background-color: #ffc107; color: #212529; }
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
                    <li><a href="../academica/academica.php">Académica</a></li>
                    <li><a href="../../logout.php">Salir</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="table-container" style="max-width: 1000px;">
            <h2>Kárdex Académico Oficial</h2>
            
            <div class="info-box">
                <strong>Matrícula:</strong> <?php echo htmlspecialchars($alumno['matricula']); ?> | 
                <strong>Alumno:</strong> <?php echo htmlspecialchars($alumno['nombre_completo'] ?? 'Sin Nombre'); ?> <br>
                <strong>Carrera:</strong> <?php echo htmlspecialchars($alumno['carrera']); ?> | 
                <strong>Semestre Actual:</strong> <?php echo htmlspecialchars($alumno['semestre_actual']); ?>
            </div>

            <table class="history-table">
                <thead>
                    <tr>
                        <th>Periodo</th>
                        <th>Materia</th>
                        <th>Créditos</th>
                        <th>Calificación</th>
                        <th>Oportunidad</th>
                        <th>Estatus</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total_creditos = 0;
                    if (count($kardex) > 0): 
                        foreach ($kardex as $k): 
                            if ($k['estatus_aprobacion'] == 'Aprobado') {
                                $total_creditos += $k['creditos'];
                            }
                    ?>
                            <tr>
                                <td><?php echo htmlspecialchars($k['nombre_periodo']); ?></td>
                                <td><strong><?php echo htmlspecialchars($k['nombre_materia']); ?></strong></td>
                                <td><?php echo $k['creditos']; ?></td>
                                <td style="font-weight: bold; font-size: 16px; <?php echo $k['calificacion_definitiva'] < 70 ? 'color: red;' : 'color: green;'; ?>">
                                    <?php echo $k['calificacion_definitiva']; ?>
                                </td>
                                <td>
                                    <?php if($k['oportunidad'] == 'Ordinario'): ?>
                                        <span class="badge bg-green">1ra - Ordinario</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning"><?php echo htmlspecialchars($k['oportunidad']); ?> (Repetición)</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($k['estatus_aprobacion'] == 'Aprobado'): ?>
                                        <span class="badge bg-green">Aprobada</span>
                                    <?php else: ?>
                                        <span class="badge bg-red">Reprobada</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr style="background-color: #e9ecef; font-weight: bold;">
                            <td colspan="2" style="text-align: right;">Total de Créditos Aprobados:</td>
                            <td><span class="badge bg-dark"><?php echo $total_creditos; ?></span></td>
                            <td colspan="3"></td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 30px;">
                                <h3 style="color: #6c757d;">Sin historial académico</h3>
                                <p style="color: #6c757d;">El alumno aún no tiene calificaciones registradas por sus docentes.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div style="text-align: center; margin-top: 25px;">
                <button onclick="history.back()" class="btn-dashboard btn-historial" style="padding:10px 20px; border:none; cursor:pointer;">Volver Atrás</button>
            </div>
        </div>
    </main>
</body>
</html>