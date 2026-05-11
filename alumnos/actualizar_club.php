<?php
session_start();
require '../conexion.php';
require 'header_alumno.php';

if(!isset($_SESSION['id_usuario'])){
    header("Location: ../login.php");
    exit();
}

$id_logueado = $_SESSION['id_usuario'];
$error_message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reinscripcion = $_POST['club'] ?? '';

    if($reinscripcion){
        try {
            $stmt_id = $pdo->prepare("CALL sp_obtener_id_alumno_por_usuario(?)");
            $stmt_id->execute([$id_logueado]);
            $alumno = $stmt_id->fetch(PDO::FETCH_ASSOC);
            $id_alumno = $alumno['id_alumno'] ?? null;

            if ($id_alumno) {
                $pdo->beginTransaction();
                
                $sql = "CALL sp_actualizar_club_alumno(?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$reinscripcion, $id_alumno]);
                
                $pdo->commit();
                header("Location: club.php?msg=actualizado_exitoso");
                exit();
            } else {
                $error_message = "No se encontró un registro de alumno vinculado a este usuario.";
            }
        } catch(PDOException $e) {
            $pdo->rollBack();
            $error_message = "Error al actualizar: " . $e->getMessage();
        }
    } else {
        $error_message = "Por favor, completa todos los campos.";
    }
}

try {
    $sql_club = "CALL sp_obtener_perfil_club_alumno(?)";
    $stmt_c = $pdo->prepare($sql_club);
    $stmt_c->execute([$id_logueado]);
    $club = $stmt_c->fetch(PDO::FETCH_ASSOC);
    if (!$club) {
        $club = [
            'matricula' => 'No disponible',
            'carrera' => 'No disponible',
            'semestre_actual' => 'N/A',
            'telefono' => 'N/A',
            'correo' => 'N/A',
            'estatus' => 'Sin registro',
            'nombre_actividad' => 'Sin registro',
            'creditos' => '0',
            'nombre_grupo' => 'Sin grupo'
        ];
    }
} catch(PDOException $e) {
    $error_message = "Error al cargar perfil: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Alumnos - Club Escolar</title>
    <link rel="stylesheet" href="../style.css">
    <style>
    h1 {text-align: center;}
    img {width: 100px;}
    a {text-align: center;}
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #c4c2c2;
    }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <img src="../img/logotec.png" alt="Instituto Tecnológico Superior de San Pedro">
            <h1>Alumnos</h1>
            <nav>
                <ul>
                    <li><a href="alumnos.php" class="active">Aula Virtual</a></li>
                    <li><a href="perfil.php">Perfil</a></li>
                    <li><a href="horarios.php">Horario</a></li>
                    <li><a href="calificaciones.php">Calificaciones</a></li>
                    <li><a href="finanzas.php">Estado Financiero</a></li>
                    <li><a href="#">Club Escolar</a></li>
                    <?php if (isset($_SESSION['id_usuario']) && $_SESSION['rol'] == 'alumno' && $tiene_registro_ss): ?>
                    <li><a href="servicio.php">Servicio Social</a></li>
                    <?php endif; ?>
                    <li><a href="../logout.php">Salir</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <?php if ($error_message): ?>
            <div style="color: red; text-align: center;"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="" method="POST" class="form-container form-group">
            <h1>Reinscripción al club</h1>
            <br>
            <label>Matrícula:</label> 
            <input type="text" value="<?php echo htmlspecialchars($club['matricula']); ?>" readonly>
            <br><br>
            <label>Carrera:</label> 
            <input type="text" value="<?php echo htmlspecialchars($club['carrera']); ?>" readonly>
            <br><br>
            <label>Semestre actual:</label>
            <input type="text" id="semestre_actual" name="semestre_actual" value="<?php echo htmlspecialchars($club['semestre_actual']);?>" readonly>
            <br> <br>
            <label>Teléfono:</label> 
            <input type="text" name="telefono" value="<?php echo htmlspecialchars($club['telefono']); ?>" readonly>
            <br><br>
            <label>Correo:</label>
            <input type="text" name="correo" value="<?php echo htmlspecialchars($club['correo']);?>" readonly>
            <br><br>
            <label>Club Escolar:</label> 
            <input type="text" name="club" required value="<?php echo htmlspecialchars($club['nombre_actividad']); ?>">
            <br><br>
            <label>Créditos: </label>
            <input type="text" id="creditos" name="creditos" value="<?php echo htmlspecialchars($club['creditos']);?>" readonly>
            <br> <br>
            <label>Grupo:</label> 
            <input type="text" value="<?php echo htmlspecialchars($club['nombre_grupo']); ?>" readonly>
            <br><br>
            <div class="form-actions">
                <button type="submit" class="btn-dashboard btn-aceptar">Guardar Cambios</button>
                <a href="club.php" style="margin-left: 10px;" class="btn-dashboard btn-secondary">Cancelar</a>
            </div>
        </form>
    </main>
</body>
</html>