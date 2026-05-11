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
    $nuevo_correo = $_POST['correo'] ?? '';
    $nuevo_telefono = $_POST['telefono'] ?? '';

    if($nuevo_correo && $nuevo_telefono){
        try {
            $pdo->beginTransaction();
            
            $sql1 = "CALL sp_actualizar_datos_contacto(?, ?, ?)";
            $stmt1 = $pdo->prepare($sql1);
            $stmt1->execute([$nuevo_correo, $nuevo_telefono, $id_logueado]);

            $pdo->commit();
            header("Location: perfil.php?msg=actualizado_exitoso");
            exit();
        } catch(PDOException $e) {
            $pdo->rollBack();
            $error_message = "Error al actualizar: " . $e->getMessage();
        }
    } else {
        $error_message = "Por favor, completa todos los campos.";
    }
}

try {
    $sql_perfil = "CALL sp_obtener_perfil_alumno(?)";
    $stmt_p = $pdo->prepare($sql_perfil);
    $stmt_p->execute([$id_logueado]);
    $perfil = $stmt_p->fetch(PDO::FETCH_ASSOC);
    if (!$perfil) {
        $perfil = [
            'matricula' => 'No disponible',
            'carrera' => 'No disponible',
            'semestre_actual' => 'N/A',
            'telefono' => 'N/A',
            'correo' => 'N/A',
            'estatus' => 'Sin registro',
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
    <title>Alumnos - Perfil</title>
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
                    <li><a href="#">Perfil</a></li>
                    <li><a href="horarios.php">Horario</a></li>
                    <li><a href="calificaciones.php">Calificaciones</a></li>
                    <li><a href="finanzas.php">Estado Financiero</a></li>
                    <li><a href="club.php">Club Escolar</a></li>
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
            <h2>Datos Personales</h2>
            <br>
            <label>Matrícula:</label> 
            <input type="text" value="<?php echo htmlspecialchars($perfil['matricula']); ?>" readonly>
            <br><br>
            <label>Carrera:</label> 
            <input type="text" value="<?php echo htmlspecialchars($perfil['carrera']); ?>" readonly>
            <br><br>
            <label>Semestre actual:</label>
            <input type="text" id="semestre_actual" name="semestre_actual" value="<?php echo htmlspecialchars($perfil['semestre_actual']);?>" readonly>
            <br> <br>
            <label>Teléfono:</label> 
            <input type="text" name="telefono" required maxlength="10" value="<?php echo htmlspecialchars($perfil['telefono']); ?>">
            <small>Ingresa 10 dígitos. Ejemplo: 1234567890</small>
            <br><br>
            <label>Correo:</label> 
            <input type="email" name="correo" required value="<?php echo htmlspecialchars($perfil['correo']); ?>">
            <small>Ingresa el formato ejemplo@dominio.com</small>
            <br><br>
            <label>Estado: </label>
            <input type="text" id="estatus" name="estatus" value="<?php echo htmlspecialchars($perfil['estatus']);?>" readonly>
            <br> <br>
            <label>Créditos: </label>
            <input type="text" id="creditos" name="creditos" value="<?php echo htmlspecialchars($perfil['creditos']);?>" readonly>
            <br> <br>
            <label>Grupo:</label> 
            <input type="text" value="<?php echo htmlspecialchars($perfil['nombre_grupo']); ?>" readonly>
            <br><br>
            <div class="form-actions">
                <button type="submit" class="btn-dashboard btn-aceptar">Guardar Cambios</button>
                <a href="perfil.php" style="margin-left: 10px;" class="btn-dashboard btn-secondary">Cancelar</a>
            </div>
        </form>
    </main>
</body>
</html>