<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrativo') {
    header("Location: ../../index.php");
    exit;
}

include '../../conexion.php';

try {
    $sql = "CALL sp_obtener_alumnos_kardex()";
    $stmt = $pdo->query($sql);
    $alumnos = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Kárdex - Administración</title>
    <link rel="stylesheet" href="../../style.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #c4c2c2; }
        img.logo { width: 100px; }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <img src="../../img/logotec.png" alt="Tec San Pedro" class="logo">
            <h1>Panel Administrativo</h1>
            <nav>
                <ul>
                    <li><a href="academica.php" class="active">Académica</a></li>
                    <li><a href="../../logout.php">Salir</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="table-container" style="max-width: 900px;">
            <h2>Control del Kárdex</h2>
            <p style="text-align:center; margin-bottom:20px;">Selecciona un alumno para revisar su historial de calificaciones y materias cursadas.</p>

            <table class="history-table">
                <thead>
                    <tr>
                        <th>Matrícula</th>
                        <th>Nombre del Alumno</th>
                        <th>Semestre</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($alumnos) > 0): ?>
                        <?php foreach ($alumnos as $alum): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($alum['matricula']); ?></strong></td>
                                <td><?php echo htmlspecialchars($alum['nombre_completo'] ?? 'Sin Nombre'); ?></td>
                                <td><?php echo htmlspecialchars($alum['semestre_actual']); ?></td>
                                <td>
                                    <a href="../alumnos/kardex_alumno.php?id=<?php echo $alum['id_alumno']; ?>" class="btn-details" style="text-decoration:none; background-color: #28a745;">Abrir Kárdex</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">No hay alumnos registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <div style="text-align: center; margin-top: 20px;">
                <a href="academica.php" class="btn-dashboard btn-historial" style="text-decoration:none; padding:10px 20px;">Volver al Menú Académico</a>
            </div>
        </div>
    </main>
</body>
</html>