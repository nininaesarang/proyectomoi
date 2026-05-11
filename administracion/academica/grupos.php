<?php
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrativo') {
    header("Location: ../../index.php");
    exit;
}

include '../../conexion.php';

$mensaje = '';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_grupo = trim($_POST['nombre_grupo'] ?? '');
    $id_ciclo = $_POST['id_ciclo'] ?? '';

    if (!empty($nombre_grupo) && !empty($id_ciclo)) {
        try {
            $sql_insert = "CALL sp_insertar_grupo(?, ?)";
            $stmt_insert = $pdo->prepare($sql_insert);
            $stmt_insert->execute([$id_ciclo, $nombre_grupo]);
            
            $mensaje = "<div class='message-box success'>¡Grupo $nombre_grupo registrado correctamente!</div>";
        } catch (PDOException $e) {
            $mensaje = "<div class='message-box error'>Error al registrar: " . $e->getMessage() . "</div>";
        }
    } else {
        $mensaje = "<div class='message-box error'>Por favor, llena todos los campos.</div>";
    }
}


try {
    $sql_ciclos = "CALL sp_obtener_ciclos_activos()";
    $stmt_ciclos = $pdo->query($sql_ciclos);
    $ciclos = $stmt_ciclos->fetchAll();
} catch (PDOException $e) {
    echo "Error al cargar ciclos: " . $e->getMessage();
}


try {
    $sql_grupos = "CALL sp_obtener_grupos_detalles()";
    $stmt_grupos = $pdo->query($sql_grupos);
    $lista_grupos = $stmt_grupos->fetchAll();
} catch (PDOException $e) {
    echo "Error al cargar grupos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Grupos - Administración</title>
    <link rel="stylesheet" href="../../style.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #c4c2c2; }
        img.logo { width: 100px; }
        .badge { padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 12px; color: white;}
        .bg-blue { background-color: #007bff; }
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
            <h2>Crear Nuevo Grupo</h2>
            
            <?php echo $mensaje; ?>

            <form action="grupos.php" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre_grupo">Nombre del Grupo (Ej. 8A, 2C)</label>
                        <input type="text" id="nombre_grupo" name="nombre_grupo" placeholder="Escribe el nombre del grupo" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="id_ciclo">Asignar al Ciclo Escolar</label>
                        <select name="id_ciclo" id="id_ciclo" style="width: 100%; padding: 10px; border-radius: 4px;" required>
                            <option value="">-- Selecciona un Periodo --</option>
                            <?php foreach ($ciclos as $c): ?>
                                <option value="<?php echo $c['id_ciclo']; ?>">
                                    <?php echo htmlspecialchars($c['nombre_periodo']); ?> 
                                    (<?php echo $c['activo'] == 'Sí' ? 'Activo' : 'Cerrado'; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-actions">
                    <a href="academica.php" class="btn-dashboard btn-historial" style="text-decoration:none; padding:11px 20px;">Volver al Menú</a>
                    <button type="submit" class="btn-primary">Registrar Grupo</button>
                </div>
            </form>
        </div>

        <div class="table-container">
            <h2>Grupos Registrados</h2>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre del Grupo</th>
                        <th>Ciclo Escolar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($lista_grupos) > 0): ?>
                        <?php foreach ($lista_grupos as $g): ?>
                            <tr>
                                <td><?php echo $g['id_grupo']; ?></td>
                                <td><span class="badge bg-blue">Grupo <?php echo htmlspecialchars($g['nombre_grupo']); ?></span></td>
                                <td><strong><?php echo htmlspecialchars($g['nombre_periodo'] ?? 'Sin asignar'); ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" style="text-align: center;">No hay grupos registrados todavía.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>