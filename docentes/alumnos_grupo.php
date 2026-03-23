<?php
session_start();
require '../conexion.php';

$sql = "SELECT alumnos.matricula,
       usuarios.correo,
       grupos.nombre_grupo
FROM alumnos
JOIN usuarios ON alumnos.id_usuario = usuarios.id_usuario
JOIN grupos ON alumnos.id_grupo = grupos.id_grupo;";

//Traer datos

$stmt = $pdo->query($sql);
$alumnos = $stmt->fetchAll();
?>

<h2 style="text-align:center; color:#000000;">Lista de alumnos</h2>

<style>

/* Estilo de la tabla */
table {
    width: 80%;
    margin: 30px auto;
    border-collapse: collapse;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    border-radius: 2px;
    overflow: hidden;
    background-color: #ffffff;
}

/* Encabezado de la tabla */
th {
    background-color: #d53333;
    color: white;
    font-size: 16px;
    padding: 12px 15px;
    text-align: center;
}

/* Filas de la tabla */
td {
    padding: 12px 15px;
    text-align: center;
    color: #555;
    font-size: 14px;
    border-bottom: 1px solid #e0e0e0;
}



/* Botón volver */
li{
    list-style: none;
    text-align: center;
    margin-top: 30px;
}

li a{
    text-decoration: none;
    background-color: #6c757d;
    color: white;
    padding: 10px 18px;
    border-radius: 5px;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

li a:hover{
    background-color: #5a6268;
}
</style>


<table border="1">

<tr>
<th>Matrícula</th>
<th>Correo</th>
<th>Grupo</th>

</tr>




<?php foreach($alumnos as $datitos){ ?>

<tr>
<td><?php echo $datitos['matricula']; ?></td>
<td><?php echo $datitos['correo']; ?></td>
<td><?php echo $datitos['nombre_grupo']; ?></td>
</tr>

<?php } ?>

</table>

<li><a href="docentes.php">Volver</a></li>



