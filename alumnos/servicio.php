<?php
session_start();
require '../conexion.php';

$id_logueado = $_SESSION['id_usuario'];
$error_message = null;

if(isset($_GET['msg'])){
   if( $_GET['msg'] == 'error' && isset($_GET['detail'])){
    $error_message = htmlspecialchars(urldecode($_GET['detail']));
   }
}

try{
    $stmt=$pdo->prepare("CALL servicio(?)");
    $stmt->execute([$id_logueado]);
    $servicio = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    $stmt_ss = $pdo->prepare("CALL menu_ss(?)");
    $stmt_ss->execute([$id_logueado]);
    $tiene_registro_ss = (int)$stmt_ss->fetchColumn() > 0;
    $stmt_ss->closeCursor();
    if (!$servicio) {
        $servicio = [];
    }
}
catch(PDOException $e){
    $error_message = "Error al cargar las tareas: " . $e->getMessage();
    $servicio = [];
}

$aceptacion = !empty($servicio['ruta_carta_aceptacion']);
$liberacion = !empty($servicio['ruta_carta_liberacion']);
$reporte1 = !empty($servicio['ruta_reporte1']);
$reporte2 = !empty($servicio['ruta_reporte2']);
$reporte3 = !empty($servicio['ruta_reporte3']);
?>

<!DOCTYPE html>
<body><html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumnos - Servicio Social</title>
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
.inputfile {
	width: 0.1px;
	height: 0.1px;
	opacity: 0;
	overflow: hidden;
	position: absolute;
	z-index: -1;
}
.inputfile + label {
    font-size: auto;
    font-weight: bold;
    color: white;
    background-color:  #de2720;
    transition: background-color 0.3s ease;
    display: inline-block;
    border-radius: 5px;
    padding: 12px 20px;
    cursor: pointer;
}
.inputfile:focus + label,
.inputfile + label:hover {
    background-color: #a11915;
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
                    <li><a href="club.php">Club Escolar</a></li>
                    <li><a href="#">Servicio Social</a></li>
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
            <h1>Gestión de Documentos de Servicio Social</h1>
            <br>
            <div class="form-group">
                <label>Horas liberadas: <strong><?php echo htmlspecialchars($servicio['horas_liberadas']);?></strong></label>
                <br>
               <form action="upload.php" method="POST" enctype="multipart/form-data" class="form-container">
                <div>
                    <label>Tipo de documento:</label>
                    <select name="tipo_documento" required>
                        <option value="aceptacion" <?php echo $aceptacion ? 'disabled' : ''; ?>>
                            Carta de aceptación <?php echo $aceptacion ? '(Subida)' : ''; ?></option>
                        <option value="liberacion" <?php echo $liberacion ? 'disabled' : ''; ?>>
                            Carta de liberación <?php echo $liberacion ? '(Subida)' : ''; ?></option>
                        <option value="reporte1" <?php echo $reporte1 ? 'disabled' : ''; ?>>
                            Primer reporte bimestral <?php echo $reporte1 ? '(Subida)' : ''; ?></option>
                        <option value="reporte2" <?php echo $reporte2 ? 'disabled' : ''; ?>>
                            Segundo reporte bimestral<?php echo $reporte2 ? '(Subida)' : ''; ?></option>
                        <option value="reporte3" <?php echo $reporte3 ? 'disabled' : ''; ?>>
                            Tercer reporte bimestral <?php echo $reporte3 ? '(Subida)' : ''; ?></option>
                    </select>
                </div>
                <br>
                <div>
                    <input class="inputfile" type="file" id="file" name="fileUpload" accept="application/pdf" required >
                    <label for="file" class="flag"><span id="file-name">Selecciona un archivo</span></label>
                </div>
                <div class="form-actions">
                    <button type="submit" name="submit" class="btn-dashboard btn-aceptar">Subir</button>
                </div>
               </form>
            </div>
            <label class="form-container form-group" style="display: block; text-align: center;"><strong>Nota: Procura subir el documento correcto.</strong></label>
        </div>
    </main>
    <script>
        document.getElementById('file').addEventListener('change', function(e) {
                var fileName = e.target.files[0].name;
                document.getElementById('file-name').innerHTML = fileName;
                this.nextElementSibling.style.backgroundColor = "#2d3748"; 
            });
    </script>
</body>
</html>