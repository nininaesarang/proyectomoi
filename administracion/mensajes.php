<?php
session_start();
// Validar sesión como ADMIN (Lógica de tu compañera)
if(!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'administrativo'){
    die("Error: Debes iniciar sesión como administrativo");
}

include '../conexion.php'; // Ajustado a la ruta correcta si está en la carpeta raiz de administracion

$id_admin = $_SESSION['id_usuario'];

// Obtener mensajes (Lógica de tu compañera)
$stmt = $pdo->prepare("
    SELECT ma.id_mensaje,
           ma.asunto,
           ma.mensaje,
           ma.fecha_envio,
           d.nombre_completo
    FROM mensajes_admin ma
    INNER JOIN docentes d ON ma.id_docente = d.id_docente
    WHERE ma.id_admin = ?
    ORDER BY ma.fecha_envio DESC
");
$stmt->execute([$id_admin]);
$mensajes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bandeja de Mensajes - Administración</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #c4c2c2; }
        img.logo { width: 100px; }
        .badge { padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 12px; color: white;}
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
                    <li><a href="academica/academica.php">Académica</a></li>
                    <li><a href="reportes.php">Reportes</a></li>
                    <li><a href="mensajes.php" class="active">Mensajes</a></li>
                    <li><a href="../logout.php">Salir</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="table-container" style="max-width: 1000px; margin: 0 auto;">
            <h2>Bandeja de Solicitudes Docentes</h2>
            <p style="text-align: center; margin-bottom: 20px;">Solicitudes para reapertura de actas y calificaciones.</p>

            <table class="history-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Docente</th>
                        <th>Asunto</th>
                        <th>Mensaje</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($mensajes) > 0): ?>
                        <?php foreach($mensajes as $m): ?>
                            <tr>
                                <td style="white-space: nowrap;"><?php echo date('d/m/Y H:i', strtotime($m['fecha_envio'])); ?></td>
                                <td><strong><?php echo htmlspecialchars($m['nombre_completo']); ?></strong></td>
                                <td><?php echo htmlspecialchars($m['asunto']); ?></td>
                                <td style="text-align: left; font-size: 14px;"><?php echo nl2br(htmlspecialchars($m['mensaje'])); ?></td>
                                <td style="text-align: center;">
                                    <button class="btn-dashboard btn-aceptar" style="padding: 6px 12px; font-size: 12px;">Marcar Atendido</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 30px; color: #6c757d;">No hay mensajes nuevos.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>