<?php
session_start();
require '../conexion.php';

// checar que se inixio sesion 

/* <?php 
session_start();
 
if(!isset($_SESSION["logueado"])){
    echo 'Usted no esta logueado';
    exit;
}
    usar die para que se detenga todo*/


if(!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'alumno'){
    die("Error: Debes iniciar sesión como alumno");
}

// guardar en id uaurio
$id_usuario = $_SESSION['id_usuario'];

//      sacar el id usuario                                                     
$stmtAl = $pdo->prepare("SELECT id_alumno FROM alumnos WHERE id_usuario = ?");
$stmtAl->execute([$id_usuario]);
$alumno = $stmtAl->fetch(PDO::FETCH_ASSOC);

if(!$alumno){
    die("Error: Este usuario no está registrado como alumno");
}

$id_alumno = $alumno['id_alumno'];

// llamar lo q se guardo q le mando el docente
$stmt = $pdo->prepare("
    SELECT mensajes.id_mensaje,
       mensajes.asunto,
       mensajes.mensaje,
       mensajes.fecha_envio,
       docentes.nombre_completo
    FROM mensajes
    INNER JOIN docentes ON mensajes.id_docente = docentes.id_docente
WHERE mensajes.id_alumno = ?
ORDER BY mensajes.fecha_envio DESC;
");


//los guarda para despues que sean los q mostramos en alumnos

$stmt->execute([$id_alumno]);
$mensajes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Mensajes del Docente</title>
<style>
table { border-collapse: collapse; width: 100%; }
th, td { border: 1px solid #000; padding: 5px; text-align: center; }
</style>
</head>
<body>

<h1>Mensajes del Docente</h1>

<table>
<tr>
    <th>Docente</th>
    <th>Asunto</th>
    <th>Mensaje</th>
    <th>Fecha</th>
</tr>

<?php foreach($mensajes as $m): ?>

    
<tr>
    <td><?= htmlspecialchars($m['nombre_completo']) ?></td>
    <td><?= htmlspecialchars($m['asunto']) ?></td>
    <td><?= nl2br(htmlspecialchars($m['mensaje'])) ?></td>
    <td><?= $m['fecha_envio'] ?></td>
</tr>
<?php endforeach; ?>

</table>

<br>
<a href="alumno.php">Volver</a>

</body>
</html>