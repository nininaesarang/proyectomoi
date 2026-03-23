<?php
session_start();
require '../conexion.php';

$id_usuario = $_SESSION['id_usuario'];

/* que usuario es el docente*/ 
$sql_docente = "SELECT id_docente FROM docentes WHERE id_usuario = ?";
$stmt = $pdo->prepare($sql_docente);
$stmt->execute([$id_usuario]);
$docente = $stmt->fetch();

$id_docente = $docente['id_docente'];

$sql = "SELECT materias.nombre_materia, grupos.nombre_grupo, ciclos_escolares.nombre_periodo
FROM carga_academica
JOIN materias ON carga_academica.id_materia = materias.id_materia
JOIN grupos ON carga_academica.id_grupo = grupos.id_grupo
JOIN ciclos_escolares ON carga_academica.id_ciclo = ciclos_escolares.id_ciclo
WHERE carga_academica.id_docente = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$id_docente]);
$resultado = $stmt->fetchAll();
?>

<h2>Grupos asignados</h2>

<table border="1">


<!-- Tablita -->
<tr>
<th>Materia</th>
<th>Grupo</th>
<th>Ciclo</th>
</tr>

<!-- Disenito -->

<style>

/* Título */
h2{
    text-align: center;
    margin-top: 25px;
    color: #2c3e50;
    font-weight: 600;
}

/* Tabla */
table{
    width: 85%;
    margin: 25px auto;
    border-collapse: collapse;
    background-color: #ffffff;
    border: 1px solid #dcdcdc;
    font-family: Arial, Helvetica, sans-serif;
}

/* titulito */
table th{
    background-color: #d53333;
    color: #ffffff;
    padding: 10px;
    font-size: 14px;
    text-align: left;
}

/* Celdas */
table td{
    padding: 10px;
    border-bottom: 1px solid #e6e6e6;
    font-size: 14px;
    color: #333;
}

/* Filas */
table tr:nth-child(even){
    background-color: #f7f7f7;
}


table tr:hover td{
    background-color: #f0f0f0;
}

button:hover {
    background-color: #5a0b09;
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
<?php foreach($resultado as $fila){ ?>

<!-- Como se mira -->

<tr>
<td><?php echo $fila['nombre_materia']; ?></td>
<td><?php echo $fila['nombre_grupo']; ?></td>
<td><?php echo $fila['nombre_periodo']; ?></td>
</tr>

<?php } ?>



</table>

<li><a href="docentes.php">Volver</a></li>


