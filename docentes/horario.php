<?php
session_start();
require '../conexion.php';


//busacamos q user es
$id_usuario = $_SESSION['id_usuario'];

$sql_docente = "SELECT id_docente FROM docentes WHERE id_usuario = ?";
$stmt = $pdo->prepare($sql_docente);
$stmt->execute([$id_usuario]);
$docente = $stmt->fetch();

$id_docente = $docente['id_docente'];


// consultita
$sql = "SELECT 
    materias.nombre_materia,grupos.nombre_grupo,horarios.dia_semana,horarios.hora_inicio,
    horarios.hora_fin,
    horarios.aula
FROM horarios
JOIN carga_academica 
    ON horarios.id_carga_academica = carga_academica.id_carga_academica
JOIN materias 
    ON carga_academica.id_materia = materias.id_materia
JOIN grupos 
    ON carga_academica.id_grupo = grupos.id_grupo
WHERE carga_academica.id_docente = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$id_docente]);
$horario = $stmt->fetchAll();
?>


<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Horario del Docente</title>
<style>
body {
    font-family: times new roman, serif;
    background-color: #f5f5f5;
    padding: 20px;
}

/* Título */
h2 {
    text-align: center;
    color: #d53333;
    margin-bottom: 20px;
}

/* Tabla  */
table {
    width: 90%;
    margin: 20px auto;
    border-collapse: collapse;
    border-radius: 8px;
    overflow: hidden;
    background-color: #ffffff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

th {
    background-color: #d53333;
    color: white;
    padding: 10px;
    text-align: center;
}

td {
    padding: 10px;
    text-align: center;
    border-bottom: 1px solid #e0e0e0;
}



/* Botón volver */
li {
    list-style: none;
    text-align: center;
    margin-top: 30px;
}

li a {
    text-decoration: none;
    background-color: #6c757d;
    color: white;
    padding: 10px 18px;
    border-radius: 5px;
    font-weight: bold;
    transition: background-color 0.3s ease;
}


</style>
</head>
<body>

<h2>Horario</h2>

<table border="1">
<tr>
    <th>Materia</th>
    <th>Grupo</th>
    <th>Día</th>
    <th>Inicio</th>
    <th>Fin</th>
    <th>Aula</th>
</tr>

<?php foreach($horario as $fila){ ?>
<tr>
    <td><?php echo $fila['nombre_materia']; ?></td>
    <td><?php echo $fila['nombre_grupo']; ?></td>
    <td><?php echo $fila['dia_semana']; ?></td>
    <td><?php echo $fila['hora_inicio']; ?></td>
    <td><?php echo $fila['hora_fin']; ?></td>
    <td><?php echo $fila['aula']; ?></td>
</tr>
<?php } ?>

</table>

<li><a href="docentes.php">Volver</a></li>
