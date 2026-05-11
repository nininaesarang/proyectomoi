<?php
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrativo') {sssss
    header("Location: ../../index.php");
    exit;
}

include '../../conexion.php';

$mensaje = '';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_periodo = $_POST['nombre_periodo'] ?? '';
    $activo = $_POST['activo'] ?? 'Sí';

    if (!empty($nombre_periodo)) {
        try {
            $sql_insert = "CALL sp_insertar_ciclo(?, ?)";
            $stmt_insert = $pdo->prepare($sql_insert);
            $stmt_insert->execute([$nombre_periodo, $activo]);
            $mensaje = "<div class='message-box success'>¡Ciclo escolar registrado correctamente!</div>";
        } catch (PDOException $e) {
            $mensaje = "<div class='message-box error'>Error al registrar: " . $e->getMessage() . "</div>";
        }
    } else {
        $mensaje = "<div class='message-box error'>El nombre del periodo es obligatorio.</div>";
    }
}


try {
    $sql = "CALL sp_obtener_ciclos_activos()";
    $stmt = $pdo->query($sql);
    $ciclos = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ciclos Escolares - Administración</title>
    <link rel="stylesheet" href="../../style.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #c4c2c2; }
        img.logo { width: 100px; }
        .badge { padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 12px; color: white;}
        .bg-green { background-color: #28a745; }
        .bg-gray { background-color: #6c757d; }
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
        <div class="form-container" style="margin-bottom: 30px;">
            <h2>Abrir Nuevo Ciclo Escolar</h2>
            
            <?php echo $mensaje; ?>

            <form action="ciclos.php" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre_periodo">Nombre del Periodo</label>
                        <input type="text" id="nombre_periodo" name="nombre_periodo" placeholder="Ej. Ene-Jun 2026" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="activo">¿Está Activo?</label>
                        <select name="activo" id="activo" style="width: 100%; padding: 10px; border-radius: 4px;">
                            <option value="Sí">Sí</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-actions">
                    <a href="academica.php" class="btn-dashboard btn-historial" style="text-decoration:none; padding:11px 20px;">Volver al Menú</a>
                    <button type="submit" class="btn-primary">Registrar Ciclo</button>
                </div>
            </form>
        </div>

        <div class="table-container">
            <h2>Ciclos Registrados</h2>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Periodo Escolar</th>
                        <th>Estatus</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($ciclos) > 0): ?>
                        <?php foreach ($ciclos as $c): ?>
                            <tr>
                                <td><?php echo $c['id_ciclo']; ?></td>
                                <td><strong><?php echo htmlspecialchars($c['nombre_periodo']); ?></strong></td>
                                <td>
                                    <?php if($c['activo'] == 'Sí'): ?>
                                        <span class="badge bg-green">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-gray">Cerrado</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" style="text-align: center;">No hay ciclos registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>