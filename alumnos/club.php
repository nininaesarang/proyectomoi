<?php
session_start();
require '../conexion.php';
require 'header_alumno.php';

$id_logueado = $_SESSION['id_usuario'];
$error_message = null;

if(isset($_GET['msg'])){
   if( $_GET['msg'] == 'error' && isset($_GET['detail'])){
    $error_message = htmlspecialchars(urldecode($_GET['detail']));
   }
}

try{
    $stmt = $pdo->prepare("CALL consultar_club(?)");
    $stmt->execute([$id_logueado]);
    $club = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    $stmt_ss = $pdo->prepare("CALL menu_ss(?)");
    $stmt_ss->execute([$id_logueado]);
    $tiene_registro_ss = (int)$stmt_ss->fetchColumn() > 0;
    $stmt_ss->closeCursor();
}
catch(PDOException $e){
    $error_message = "Error al cargar las tareas: " . $e->getMessage();
    $club = [];
    $tiene_registro_ss = false;
}
?>

<!DOCTYPE html>
<body><html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumnos - Club Escolar</title>
    <link rel="stylesheet" href="../style.css">
<style>
h1, h3 {text-align: center;}
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
    <header>
        <div class="header-container">
            <img src="../img/logotec.png" alt="Instituto Tecnológico Superior de San Pedro">
            <h1>Alumnos</h1>
            <nav>
                <ul>
                    <li><a href="alumnos.php">Aula Virtual</a></li>
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
                <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <div class="form-container">
            <h1>Datos personales</h1>
            <div class=" form-container form-group">
                <label>Matrícula:</label>
                <input type="text" id="matricula" name="matricula" value="<?php echo htmlspecialchars($club['matricula'] ?? '');?>" readonly>
                <br> <br>
                <label>Carrera:</label>
                <input type="text" id="carrera" name="carrera" value="<?php echo htmlspecialchars($club['carrera'] ?? '');?>" readonly>
                <br> <br>
                <label>Semestre actual:</label>
                <input type="text" id="semestre_actual" name="semestre_actual" value="<?php echo htmlspecialchars($club['semestre_actual'] ?? '');?>" readonly>
                <br> <br>
                <label>Teléfono: </label>
                <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($club['telefono'] ?? '');?>" readonly>
                <br> <br>
                <label>Correo: </label>
                <input type="text" id="correo" name="correo" value="<?php echo htmlspecialchars($club['correo'] ?? '');?>" readonly>
                <br> <br>
                <label>Estado: </label>
                <input type="text" id="estatus" name="estatus" value="<?php echo htmlspecialchars($club['estatus'] ?? '');?>" readonly>
                <br> <br>
                <label>Club Escolar: </label> 
                <input type="text" name="club" value="<?php echo htmlspecialchars($club['nombre_actividad'] ?? ''); ?>" readonly>
                <br><br>
                <label>Créditos: </label>
                <input type="text" id="creditos" name="creditos" value="<?php echo htmlspecialchars($club['creditos'] ?? '');?>" readonly>
                <br> <br>
                <label>Grupo: </label>
                <input type="text" id="nombre_grupo" name="nombre_grupo" value="<?php echo htmlspecialchars($club['nombre_grupo'] ?? '');?>" readonly>                                             
            </div>
            <br>
            <h3>Incribirse al Club Escolar: <a class="btn-dashboard btn-opcion" href="actualizar_club.php">Inscribirse</a></h3>
            <br>
            <label class="form-container form-group" style="display: block; text-align: center;"><strong>Nota: Procura inscribirte en el período disponible.</strong></label>
        </div>
    </main>
</body>
</html>