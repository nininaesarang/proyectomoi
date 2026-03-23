<?php
session_start();
require '../conexion.php';



//se guarada el q inicio sesion en la vriable
$id_usuario = $_SESSION['id_usuario'];

$sql_docente = "SELECT id_docente FROM docentes WHERE id_usuario = ?";
$stmt = $pdo->prepare($sql_docente);
$stmt->execute([$id_usuario]);

//se trae
$docente = $stmt->fetch();
$id_docente = $docente['id_docente'] ?? 0;

// Alumnitos
$sql_alumnos = "SELECT alumnos.id_alumno,
       alumnos.matricula,
       usuarios.correo,
       alumnos.semestre_actual
FROM alumnos
JOIN usuarios ON usuarios.id_usuario = alumnos.id_usuario
JOIN carga_academica ON carga_academica.id_grupo = alumnos.id_grupo
WHERE carga_academica.id_docente = ?
ORDER BY alumnos.semestre_actual, alumnos.matricula";




$stmt = $pdo->prepare($sql_alumnos);
$stmt->execute([$id_docente]);
$alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);


//mandar la asistencia

 //hacer clic
if(isset($_POST['guardar_asistencia'])){
    //assitencitas por dia
    foreach($_POST['asistencia'] as $id_alumno => $estatus){
        $fecha = date('Y-m-d H:i:s');

       // guardar en tablita
        $sql_insert = "INSERT INTO asistencias (id_alumno, id_carga_academica, fecha, estatus)
                       VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql_insert);

        //ejecutanos
        $stmt->execute([$id_alumno, $_POST['id_carga_academica'], $fecha, $estatus]);
    }

    //se guardo bien y todo
    echo "<p style='color:green'>Asistencias registradas correctamente.</p>";
}

// Traer carga academicaa
$sql_carga = "SELECT * FROM carga_academica WHERE id_docente = ? LIMIT 1";

//conusultita
$stmt = $pdo->prepare($sql_carga);
//id del prof que se logeo
$stmt->execute([$id_docente]);
$carga = $stmt->fetch();
//guardar
$id_carga = $carga['id_carga_academica'] ?? 0;

// aistencia

//contamos segun a lo q le demos clic
$sql_historial = "
SELECT id_alumno,


       SUM(CASE WHEN estatus='Faltó' THEN 1 ELSE 0 END) AS faltas,
       SUM(CASE WHEN estatus='Retardo' THEN 1 ELSE 0 END) AS retardos,
       COUNT(*) AS total
FROM asistencias
WHERE id_carga_academica = ?
GROUP BY id_alumno
";

//preparamos y ejectams
$stmt = $pdo->prepare($sql_historial);
$stmt->execute([$id_carga]);
$historial = [];
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $faltas = $row['faltas'];
    $total = $row['total'];
    $porcentaje = $total > 0 ? round((($total - $faltas)/$total)*100,2) : 100;
    $estado = $faltas >= 3 ? "En riesgo" : "Normal";

    //guarda aui
    $historial[$row['id_alumno']] = [
        'faltas' => $faltas,
        'retardos' => $row['retardos'],
        'porcentaje' => $porcentaje,
        'estado' => $estado
    ];
}
?>



<h2 style="text-align:center; color:#000000;">Registro de Asistencias</h2>

<style>
/* Tabla */
table {
    width: 90%;
    margin: 30px auto;
    border-collapse: collapse;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    border-radius: 2px;
    overflow: hidden;
    background-color: #ffffff;
}

/* titulito */
th {
    background-color: #d53333;
    color: white;
    font-size: 16px;
    padding: 12px 15px;
    text-align: center;
}

/* Filas */
td {
    padding: 12px 15px;
    text-align: center;
    color: #555;
    font-size: 14px;
    border-bottom: 1px solid #e0e0e0;
}




select {
    padding: 6px 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

/* Botón guardar */
input[type="submit"] {
    display: block;
    margin: 20px auto;
    padding: 10px 25px;
    background-color: #b4322e;
    color: white;
    font-weight: bold;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

input[type="submit"]:hover {
    background-color: #5a0b09;
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


<h2>Registro de Asistencias</h2>
<form method="post">
<input type="hidden" name="id_carga_academica" value="<?= $id_carga ?>">
<table border="1" cellpadding="5" cellspacing="0">
<tr>
    <th>Alumno</th>
    <th>Matricula</th>
    <th>Semestre</th>
    <th>Asistencia</th>
    <th>Historial</th>
    <th>Estado</th>
</tr>

<?php foreach($alumnos as $alumno): 
    $id_al = $alumno['id_alumno'];
    $hist = $historial[$id_al] ?? ['faltas'=>0,'retardos'=>0,'porcentaje'=>100,'estado'=>'Normal'];
?>
<tr>
    <td><?= $alumno['correo'] ?></td>
    <td><?= $alumno['matricula'] ?></td>
    <td><?= $alumno['semestre_actual'] ?></td>
    <td>
        <select name="asistencia[<?= $id_al ?>]">
            <option value="Asistió">Asistió</option>
            <option value="Faltó">Faltó</option>
            <option value="Retardo">Retardo</option>
        </select>
    </td>
    <td>
        <?= "Faltas: ".$hist['faltas']." | Retardos: ".$hist['retardos']." | Asistencia: ".$hist['porcentaje']."%" ?>
    </td>
    <td><?= $hist['estado'] ?></td>
</tr>
<?php endforeach; ?>
</table>
<br>
<input type="submit" name="guardar_asistencia" value="Guardar Asistencias">
</form>


<li><a href="docentes.php">Volver</a></li>