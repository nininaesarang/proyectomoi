<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrativo') {
    header("Location: ../index.php");
    exit;
}

include '../conexion.php';


// 1. CONSULTAS PARA LAS TARJETAS DE CONTEO
try {
    $total_alumnos = $pdo->query("SELECT COUNT(*) FROM alumnos WHERE estatus = 'Activo'")->fetchColumn();
    $total_docentes = $pdo->query("SELECT COUNT(*) FROM docentes WHERE estatus = 'Activo'")->fetchColumn();
    $total_materias = $pdo->query("SELECT COUNT(*) FROM materias")->fetchColumn();
    $total_grupos = $pdo->query("SELECT COUNT(*) FROM grupos")->fetchColumn();
} catch (PDOException $e) {
    echo "Error en los contadores: " . $e->getMessage();
}

// 2. CONSULTA PARA LA LISTA DE GRUPOS
try {
    $sql_grupos = "SELECT g.nombre_grupo, c.nombre_periodo, 
                   (SELECT COUNT(*) FROM alumnos a WHERE a.id_grupo = g.id_grupo AND a.estatus = 'Activo') as cantidad_alumnos
                   FROM grupos g
                   LEFT JOIN ciclos_escolares c ON g.id_ciclo = c.id_ciclo
                   ORDER BY g.id_grupo DESC";
    $lista_grupos = $pdo->query($sql_grupos)->fetchAll();
} catch (PDOException $e) {
    echo "Error al cargar los grupos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes y Dashboard - Administración</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #c4c2c2; }
        img.logo { width: 100px; }
        
        /* Tarjetas de Estadísticas (KPIs) */
        .kpi-container { display: flex; gap: 20px; justify-content: space-between; margin-bottom: 30px; flex-wrap: wrap; }
        .kpi-card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); flex: 1; text-align: center; min-width: 200px; border-bottom: 4px solid #dc3545; }
        .kpi-card h3 { margin: 0; color: #6c757d; font-size: 16px; text-transform: uppercase; }
        .kpi-card .number { font-size: 36px; font-weight: bold; color: #343a40; margin: 10px 0 0 0; }
        
        .badge { padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 12px; color: white;}
        .bg-dark { background-color: #343a40; }
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
                    <li><a href="horarios.php">Horarios</a></li>
                    <li><a href="academica/academica.php">Académica</a></li>
                    <li><a href="pagos.php">Pagos</a></li>
                    <li><a href="reportes.php" class="active">Reportes</a></li>
                    <li><a href="mensajes.php">Mensajes</a></li>
                    <li><a href="../logout.php">Salir</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <h2>📊 Métricas y Estado del Plantel</h2>
        <p style="text-align: center; margin-bottom: 30px; color: #555;">Resumen en tiempo real de la infraestructura escolar.</p>

        <div class="kpi-container" style="max-width: 1100px; margin: 0 auto 30px auto;">
            <div class="kpi-card" style="border-bottom-color: #007bff;">
                <h3>Alumnos Activos</h3>
                <p class="number"><?php echo $total_alumnos; ?></p>
            </div>
            <div class="kpi-card" style="border-bottom-color: #28a745;">
                <h3>Docentes Activos</h3>
                <p class="number"><?php echo $total_docentes; ?></p>
            </div>
            <div class="kpi-card" style="border-bottom-color: #ffc107;">
                <h3>Materias</h3>
                <p class="number"><?php echo $total_materias; ?></p>
            </div>
            <div class="kpi-card" style="border-bottom-color: #6f42c1;">
                <h3>Grupos Abiertos</h3>
                <p class="number"><?php echo $total_grupos; ?></p>
            </div>
        </div>

        <div class="table-container" style="max-width: 1100px; margin: 0 auto;">
            <h2>Lista de Grupos y Matrícula</h2>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Nombre del Grupo</th>
                        <th>Ciclo Escolar</th>
                        <th>Alumnos Inscritos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($lista_grupos) > 0): ?>
                        <?php foreach ($lista_grupos as $grupo): ?>
                            <tr>
                                <td><strong>Grupo <?php echo htmlspecialchars($grupo['nombre_grupo']); ?></strong></td>
                                <td><?php echo htmlspecialchars($grupo['nombre_periodo'] ?? 'Sin asignar'); ?></td>
                                <td>
                                    <span class="badge bg-dark"><?php echo $grupo['cantidad_alumnos']; ?> Alumnos</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" style="text-align: center;">No hay grupos registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>