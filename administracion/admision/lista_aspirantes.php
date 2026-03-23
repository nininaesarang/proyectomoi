<?php
session_start();
// Seguridad para que solo entre el administrador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrativo') {
    header("Location: ../index.php");
    exit;
}

include '../../conexion.php';

// Consultamos a todos los aspirantes registrados en la base de datos
try {
    $sql = "SELECT * FROM aspirantes ORDER BY id_aspirante DESC";
    $stmt = $pdo->query($sql);
    $aspirantes = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error en la consulta: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Aspirantes - Administración</title>
    <link rel="stylesheet" href="../../style.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #c4c2c2; }
        img.logo { width: 100px; }
        .badge { padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 12px; color: white;}
        .bg-red { background-color: #dc3545; }
        .bg-green { background-color: #28a745; }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <img src="../../img/logotec.png" alt="Tec San Pedro" class="logo">
            <h1>Panel Administrativo</h1>
            <nav>
                <ul>
                    <li><a href="../admin.php" class="active">Admisión</a></li>
                    <li><a href="#">Alumnos</a></li>
                    <li><a href="#">Docentes</a></li>
                    <li><a href="../logout.php">Salir</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="table-container" style="max-width: 1100px;">
            <h2>Control de Aspirantes Registrados</h2>
            
            <div style="text-align: right; margin-bottom: 15px;">
                <a href="nueva_ficha.php" class="btn-primary" style="text-decoration:none;">+ Nuevo Aspirante</a>
            </div>

            <table class="history-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Ficha</th>
                        <th>Pago Ficha</th>
                        <th>Examen</th>
                        <th>Documentos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($aspirantes) > 0): ?>
                        <?php foreach ($aspirantes as $asp): ?>
                            <tr>
                                <td><?php echo $asp['id_aspirante']; ?></td>
                                <td><?php echo htmlspecialchars($asp['nombre_completo']); ?></td>
                                <td><strong><?php echo htmlspecialchars($asp['ficha_referencia']); ?></strong></td>
                                
                                <td>
                                    <?php if($asp['pago_ficha_realizada'] == '1'): ?>
                                        <span class="badge bg-green">Pagado</span>
                                    <?php else: ?>
                                        <span class="badge bg-red">Pendiente</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php echo $asp['calificacion_examen'] !== null ? $asp['calificacion_examen'] . '/100' : 'S/N'; ?>
                                </td>

                                <td>
                                    <?php if($asp['docs_entregados'] == '1'): ?>
                                        <span class="badge bg-green">Entregados</span>
                                    <?php else: ?>
                                        <span class="badge bg-red">Faltan</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <a href="gestionar_aspirante.php?id=<?php echo $asp['id_aspirante']; ?>" class="btn-details" style="text-decoration:none;">Gestionar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center;">No hay aspirantes registrados todavía.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>