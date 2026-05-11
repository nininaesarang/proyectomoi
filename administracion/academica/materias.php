<?php
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrativo') {
    header("Location: ../../index.php");
    exit;
}

include '../../conexion.php';

$mensaje = '';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_materia = trim($_POST['nombre_materia'] ?? '');
    $creditos = $_POST['creditos'] ?? '';

    if (!empty($nombre_materia) && !empty($creditos)) {
        try {
            $sql_insert = "CALL sp_insertar_materia(?, ?)";
            $stmt_insert = $pdo->prepare($sql_insert);
            $stmt_insert->execute([$nombre_materia, $creditos]);
            
            $mensaje = "<div class='message-box success'>¡Materia '$nombre_materia' registrada correctamente!</div>";
        } catch (PDOException $e) {
            $mensaje = "<div class='message-box error'>Error al registrar: " . $e->getMessage() . "</div>";
        }
    } else {
        $mensaje = "<div class='message-box error'>Por favor, llena todos los campos.</div>";
    }
}


try {
    $sql_materias = "CALL sp_obtener_lista_materias()";
    $stmt_materias = $pdo->query($sql_materias);
    $lista_materias = $stmt_materias->fetchAll();
} catch (PDOException $e) {
    echo "Error al cargar materias: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Materias - Administración</title>
    <link rel="stylesheet" href="../../style.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #c4c2c2; }
        img.logo { width: 100px; }
        .badge { padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 12px; color: white;}
        .bg-dark { background-color: #343a40; }
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
            <h2>Registrar Nueva Materia</h2>
            
            <?php echo $mensaje; ?>

            <form action="materias.php" method="POST">
                <div class="form-row">
                    <div class="form-group" style="flex: 2;">
                        <label for="nombre_materia">Nombre de la Materia</label>
                        <input type="text" id="nombre_materia" name="nombre_materia" placeholder="Ej. Sistemas Programables" required>
                    </div>
                    
                    <div class="form-group" style="flex: 1;">
                        <label for="creditos">Créditos</label>
                        <input type="number" id="creditos" name="creditos" min="1" max="10" placeholder="Ej. 5" required>
                    </div>
                </div>
                
                <div class="form-actions">
                    <a href="academica.php" class="btn-dashboard btn-historial" style="text-decoration:none; padding:11px 20px;">Volver al Menú</a>
                    <button type="submit" class="btn-primary">Guardar Materia</button>
                </div>
            </form>
        </div>

        <div class="table-container">
            <h2>Materias Registradas</h2>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre de la Asignatura</th>
                        <th>Créditos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($lista_materias) > 0): ?>
                        <?php foreach ($lista_materias as $m): ?>
                            <tr>
                                <td><?php echo $m['id_materia']; ?></td>
                                <td><strong><?php echo htmlspecialchars($m['nombre_materia']); ?></strong></td>
                                <td><span class="badge bg-dark"><?php echo htmlspecialchars($m['creditos']); ?> Créditos</span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" style="text-align: center;">No hay materias registradas en el catálogo.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>