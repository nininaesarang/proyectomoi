<?php
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrativo') {
    header("Location: ../index.php");
    exit;
}

include '../../conexion.php';

$mensaje = '';
$id_aspirante = $_GET['id'] ?? null;

if (!$id_aspirante) {
    header("Location: lista_aspirantes.php");
    exit;
}


$sql_actual = "SELECT * FROM aspirantes WHERE id_aspirante = ?";
$stmt_actual = $pdo->prepare($sql_actual);
$stmt_actual->execute([$id_aspirante]);
$aspirante = $stmt_actual->fetch();

if (!$aspirante) {
    echo "Aspirante no encontrado.";
    exit;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pago_ficha = $_POST['pago_ficha'] ?? '0';
    $calificacion = !empty($_POST['calificacion']) ? $_POST['calificacion'] : null;
    $docs = $_POST['docs'] ?? '0';
    $pago_insc = $_POST['pago_inscripcion'] ?? '0';
    $aceptado = $_POST['aceptado'] ?? '0';

    try {
        $sql_update = "UPDATE aspirantes SET 
                pago_ficha_realizada = ?, 
                calificacion_examen = ?, 
                docs_entregados = ?, 
                pago_inscripcion_realizado = ?, 
                aceptado = ? 
                WHERE id_aspirante = ?";
        $stmt_update = $pdo->prepare($sql_update);

        $stmt_update->execute([$pago_ficha, $calificacion, $docs, $pago_insc, $aceptado, $id_aspirante]);
        
        $mensaje = "<div class='message-box success'>¡Datos de admisión actualizados correctamente!</div>";


        
        if ($aceptado == '1' && $aspirante['aceptado'] != '1') {
            
            $matricula_nueva = date("y") . rand(1000, 9999);
            $correo_nuevo = "A" . $matricula_nueva . "@tecsanpedro.edu.mx";
            $password_nueva = "12345678";


            $sql_user = "INSERT INTO usuarios (correo, password, rol) VALUES (?, ?, 'alumno')";
            $stmt_user = $pdo->prepare($sql_user);
            $stmt_user->execute([$correo_nuevo, $password_nueva]);
            $id_usuario_nuevo = $pdo->lastInsertId();

     
            $sql_alumno = "INSERT INTO alumnos (id_usuario, matricula, nombre_completo, carrera, semestre_actual, estatus, creditos) 
                           VALUES (?, ?, ?, 'Ingeniería en Sistemas', 1, 'Activo', 0)";
            $stmt_alumno = $pdo->prepare($sql_alumno);

            $stmt_alumno->execute([$id_usuario_nuevo, $matricula_nueva, $aspirante['nombre_completo']]);

            $mensaje .= "<div class='message-box' style='margin-top:10px; border:1px solid #007bff; background-color:#cce5ff; color:#004085; text-align:center;'>
                            <strong>¡ALUMNO MATRICULADO OFICIALMENTE! 🎉</strong><br><br>
                            El perfil del alumno se creó automáticamente.<br>
                            Matrícula asignada: <strong>$matricula_nueva</strong><br>
                            Correo: <strong>$correo_nuevo</strong><br>
                            Contraseña temporal: <strong>$password_nueva</strong>
                         </div>";
        }


        $stmt_actual->execute([$id_aspirante]);
        $aspirante = $stmt_actual->fetch();

    } catch (PDOException $e) {
        $mensaje = "<div class='message-box error'>Error al actualizar: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Aspirante</title>
    <link rel="stylesheet" href="../../style.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #c4c2c2; }
        img.logo { width: 100px; }
        .info-box { background: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center; font-size: 18px;}
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
                    <li><a href="../logout.php">Salir</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="form-container">
            <h2>Gestión de Aspirante</h2>
            
            <div class="info-box">
                <strong>Aspirante:</strong> <?php echo htmlspecialchars($aspirante['nombre_completo']); ?> <br>
                <strong>Ficha:</strong> <?php echo htmlspecialchars($aspirante['ficha_referencia']); ?>
            </div>

            <?php echo $mensaje; ?>

            <form action="gestionar_aspirante.php?id=<?php echo $id_aspirante; ?>" method="POST">
                
                <div class="form-row">
                    <div class="form-group">
                        <label>¿Pagó la Ficha?</label>
                        <select name="pago_ficha" style="width: 100%; padding: 10px; border-radius: 4px;">
                            <option value="0" <?php echo $aspirante['pago_ficha_realizada'] == '0' ? 'selected' : ''; ?>>No </option>
                            <option value="1" <?php echo $aspirante['pago_ficha_realizada'] == '1' ? 'selected' : ''; ?>>Sí </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Calificación Examen (0-100)</label>
                        <input type="number" name="calificacion" min="0" max="100" value="<?php echo htmlspecialchars($aspirante['calificacion_examen']); ?>" placeholder="Ej. 85">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>¿Entregó Documentos? (Acta, CURP, etc.)</label>
                        <select name="docs" style="width: 100%; padding: 10px; border-radius: 4px;">
                            <option value="0" <?php echo $aspirante['docs_entregados'] == '0' ? 'selected' : ''; ?>>Faltan</option>
                            <option value="1" <?php echo $aspirante['docs_entregados'] == '1' ? 'selected' : ''; ?>>Completos</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>¿Pagó Inscripción Formal?</label>
                        <select name="pago_inscripcion" style="width: 100%; padding: 10px; border-radius: 4px;">
                            <option value="0" <?php echo $aspirante['pago_inscripcion_realizado'] == '0' ? 'selected' : ''; ?>>Pendiente</option>
                            <option value="1" <?php echo $aspirante['pago_inscripcion_realizado'] == '1' ? 'selected' : ''; ?>>Pagado</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Estatus de Admisión</label>
                    <select name="aceptado" style="width: 100%; padding: 10px; border-radius: 4px; background-color: #f8f9fa; font-weight: bold;">
                        <option value="0" <?php echo $aspirante['aceptado'] == '0' ? 'selected' : ''; ?>>En Proceso / Rechazado</option>
                        <option value="1" <?php echo $aspirante['aceptado'] == '1' ? 'selected' : ''; ?>>ACEPTADO</option>
                    </select>
                </div>
                
                <div class="form-actions">
                    <a href="lista_aspirantes.php" class="btn-dashboard btn-historial" style="text-decoration:none; padding:11px 20px;">Regresar a la Lista</a>
                    <button type="submit" class="btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>