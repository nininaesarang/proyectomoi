<?php
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrativo') {
    header("Location: ../../index.php");
    exit;
}

include '../../conexion.php';

$mensaje = '';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_completo = trim($_POST['nombre_completo'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $estatus = $_POST['estatus'] ?? 'Activo';

    if (!empty($nombre_completo) && !empty($correo)) {
        try {
            
            $pdo->beginTransaction();

          
            $password_temporal = "profe123"; 
            $sql_user = "INSERT INTO usuarios (correo, password, rol) VALUES (?, ?, 'docente')";
            $stmt_user = $pdo->prepare($sql_user);
            $stmt_user->execute([$correo, $password_temporal]);
            
           
            $id_usuario_nuevo = $pdo->lastInsertId();

           
            $sql_docente = "INSERT INTO docentes (id_usuario, nombre_completo, estatus) VALUES (?, ?, ?)";
            $stmt_docente = $pdo->prepare($sql_docente);
            $stmt_docente->execute([$id_usuario_nuevo, $nombre_completo, $estatus]);

          
            $pdo->commit();
            
            $mensaje = "<div class='message-box success' style='text-align:center;'>
                            <strong>¡Docente registrado exitosamente!</strong><br><br>
                            Se creó su cuenta de acceso.<br>
                            Correo: <strong>$correo</strong><br>
                            Contraseña temporal: <strong>$password_temporal</strong>
                        </div>";
        } catch (PDOException $e) {
            $pdo->rollBack();
            $mensaje = "<div class='message-box error'>Error al registrar: " . $e->getMessage() . "</div>";
        }
    } else {
        $mensaje = "<div class='message-box error'>Por favor, llena el nombre y el correo.</div>";
    }
}


try {
    $sql_docentes = "SELECT d.id_docente, d.nombre_completo, d.estatus, u.correo 
                     FROM docentes d 
                     INNER JOIN usuarios u ON d.id_usuario = u.id_usuario 
                     ORDER BY d.id_docente DESC";
    $stmt_docentes = $pdo->query($sql_docentes);
    $lista_docentes = $stmt_docentes->fetchAll();
} catch (PDOException $e) {
    echo "Error al cargar docentes: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Docentes - Administración</title>
    <link rel="stylesheet" href="../../style.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #c4c2c2; }
        img.logo { width: 100px; }
        .badge { padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 12px; color: white;}
        .bg-green { background-color: #28a745; }
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
                    <li><a href="../alumnos/lista_alumnos.php">Alumnos</a></li>
                    <li><a href="docentes.php" class="active">Docentes</a></li>
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
        <div class="form-container" style="margin-bottom: 30px;">
            <h2>Alta de Nuevo Docente</h2>
            
            <?php echo $mensaje; ?>

            <form action="docentes.php" method="POST">
                <div class="form-row">
                    <div class="form-group" style="flex: 2;">
                        <label for="nombre_completo">Nombre Completo del Profesor(a)</label>
                        <input type="text" id="nombre_completo" name="nombre_completo" placeholder="Ej. Ing. Carlos Ruiz" required>
                    </div>
                    
                    <div class="form-group" style="flex: 2;">
                        <label for="correo">Correo Institucional</label>
                        <input type="email" id="correo" name="correo" placeholder="ejemplo@tecsanpedro.edu.mx" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="estatus">Estatus en el Plantel</label>
                    <select name="estatus" id="estatus" style="width: 100%; padding: 10px; border-radius: 4px;">
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo / Baja</option>
                    </select>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Registrar Docente</button>
                </div>
            </form>
        </div>

        <div class="table-container">
            <h2>Asignación Docentes</h2>

            <div style="text-align: right; margin-bottom: 15px;">
                <a href="carga_academica.php" class="btn-primary" style="text-decoration:none;">+ Asignar Materias</a>
            </div>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Completo</th>
                        <th>Cuenta de Acceso (Correo)</th>
                        <th>Estatus</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($lista_docentes) > 0): ?>
                        <?php foreach ($lista_docentes as $doc): ?>
                            <tr>
                                <td><?php echo $doc['id_docente']; ?></td>
                                <td><strong><?php echo htmlspecialchars($doc['nombre_completo']); ?></strong></td>
                                <td><?php echo htmlspecialchars($doc['correo']); ?></td>
                                <td>
                                    <?php if($doc['estatus'] == 'Activo'): ?>
                                        <span class="badge bg-green">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-red">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">No hay docentes registrados en el sistema.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>