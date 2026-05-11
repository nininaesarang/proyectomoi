<?php
session_start();
require '../conexion.php';
require 'header_alumno.php';

$message = null;
$error_message = null;

if(!isset($_SESSION['id_usuario'])){
    header("Location: ../login.php");
    exit();
}

$id_logueado = $_SESSION['id_usuario'];

try{
    $sql = "CALL sp_obtener_perfil_alumno(?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_logueado]);
    $perfil = $stmt->fetch(PDO::FETCH_ASSOC);
    
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
}
catch(PDOException $e){
    $error_message = "Error al cargar la información personal: " . $e->getMessage();
    $perfil = [];
}
?>

<!DOCTYPE html>
<body><html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <div class="form-container">
            <h1>Perfil</h1>
            <div class=" form-container form-group">
                <label>Matrícula:</label>
                <input type="text" id="matricula" name="matricula" value="<?php echo htmlspecialchars($perfil['matricula']);?>" readonly>
                <br> <br>
                <label>Carrera:</label>
                <input type="text" id="carrera" name="carrera" value="<?php echo htmlspecialchars($perfil['carrera']);?>" readonly>
                <br> <br>
                <label>Semestre actual:</label>
                <input type="text" id="semestre_actual" name="semestre_actual" value="<?php echo htmlspecialchars($perfil['semestre_actual']);?>" readonly>
                <br> <br>
                <label>Teléfono: </label>
                <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($perfil['telefono']);?>" readonly>
                <br> <br>
                <label>Correo: </label>
                <input type="text" id="correo" name="correo" value="<?php echo htmlspecialchars($perfil['correo']);?>" readonly>
                <br> <br>
                <label>Estado: </label>
                <input type="text" id="estatus" name="estatus" value="<?php echo htmlspecialchars($perfil['estatus']);?>" readonly>
                <br> <br>
                <label>Créditos: </label>
                <input type="text" id="creditos" name="creditos" value="<?php echo htmlspecialchars($perfil['creditos']);?>" readonly>
                <br> <br>
                <label>Grupo: </label>
                <input type="text" id="nombre_grupo" name="nombre_grupo" value="<?php echo htmlspecialchars($perfil['nombre_grupo']);?>" readonly>
                <br> <br>
                <label class="form-container" style="text-align: center;">Actualizar correo y teléfono: <a class="btn-dashboard btn-opcion" href="actualizar.php">Actualizar datos</a></label>                                                
            </div>
        
        </div>
    </main>
</body>
</html>