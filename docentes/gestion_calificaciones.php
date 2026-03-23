<?php
session_start();
require '../conexion.php'; 

// materia del profe
$materia_id = $_GET['materia'] ?? 1; // por defecto materia 1

// alumnitos de su materia
$sql = "SELECT alumnos.id_alumno, alumnos.matricula, alumnos.carrera, 
        calificaciones_activas.unidad_1, calificaciones_activas.unidad_2, calificaciones_activas.unidad_3, 
        calificaciones_activas.unidad_4, calificaciones_activas.unidad_5, calificaciones_activas.unidad_6, 
        calificaciones_activas.promedio_final, calificaciones_activas.acta_cerrada
        FROM alumnos
        JOIN carga_academica ON carga_academica.id_grupo = alumnos.id_grupo
        LEFT JOIN calificaciones_activas ON calificaciones_activas.id_alumno = alumnos.id_alumno
        WHERE carga_academica.id_materia = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$materia_id]);
$alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// calificaciones
if ($_POST) {
    foreach ($_POST['unidad1'] as $id_alumno => $val) {
        
        $stmt = $pdo->prepare("SELECT * FROM calificaciones_activas WHERE id_alumno = ?");
        $stmt->execute([$id_alumno]);
        $calif = $stmt->fetch(PDO::FETCH_ASSOC);

        $u1 = $_POST['unidad1'][$id_alumno] ?: null;
        $u2 = $_POST['unidad2'][$id_alumno] ?: null;
        $u3 = $_POST['unidad3'][$id_alumno] ?: null;
        $u4 = $_POST['unidad4'][$id_alumno] ?: null;
        $u5 = $_POST['unidad5'][$id_alumno] ?: null;
        $u6 = $_POST['unidad6'][$id_alumno] ?: null;

        // promeddito
        $suma = 0; $count = 0;
        foreach ([$u1,$u2,$u3,$u4,$u5,$u6] as $v) {
            if ($v !== null && $v !== '') { $suma += $v; $count++; }
        }
        $promedio = $count > 0 ? round($suma / $count, 2) : null;

        if ($calif) {
            // nuevas calif
            $stmt2 = $pdo->prepare("UPDATE calificaciones_activas SET unidad_1=?, unidad_2=?, unidad_3=?, unidad_4=?, unidad_5=?, unidad_6=?, promedio_final=? WHERE id_alumno=? AND acta_cerrada IS NULL");
            $stmt2->execute([$u1,$u2,$u3,$u4,$u5,$u6,$promedio,$id_alumno]);
        } else {
            // agg
            $stmt2 = $pdo->prepare("INSERT INTO calificaciones_activas (id_alumno, unidad_1, unidad_2, unidad_3, unidad_4, unidad_5, unidad_6, promedio_final) VALUES (?,?,?,?,?,?,?,?)");
            $stmt2->execute([$id_alumno,$u1,$u2,$u3,$u4,$u5,$u6,$promedio]);
        }
    }
    header("Location: gestion_calificaciones.php?materia=$materia_id");
    exit;
}

// cerrar actia
if (isset($_GET['cerrar'])) {
    $stmt = $pdo->prepare("UPDATE calificaciones_activas SET acta_cerrada='si' WHERE id_alumno IN (SELECT a.id_alumno FROM alumnos a JOIN carga_academica c ON c.id_grupo=a.id_grupo WHERE c.id_materia=?)");
    $stmt->execute([$materia_id]);
    header("Location: gestion_calificaciones.php?materia=$materia_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Gestión de Calificaciones</title>
<style>
body {
    font-family: times new roman, serif;
    background-color: #f5f5f5;
    padding: 20px;
}

/* Títulos */
h1 {
    text-align: center;
    color: #000000;
    margin-bottom: 20px;
}

/* Tabla  */
table {
    width: 95%;
    margin: 20px auto;
    border-collapse: collapse;
    border-radius: 2px;
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



/* calificacion */
input[type=number] {
    width: 50px;
    padding: 4px;
    border-radius: 5px;
    border: 1px solid #ccc;
    text-align: center;
}

/* Botones guardar y cerrar */
button {
    display: block;
    margin: 15px auto;
    padding: 10px 25px;
    border: none;
    border-radius: 2px;
    font-weight: bold;
    cursor: pointer;
    background-color: #8a2421;
    color: white;
    transition: background-color 0.3s ease;
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

</head>
<body>
<h1>Gestión de Calificaciones </h1>

<form method="post">
<table>
<tr>
    <th>Matricula</th>
    <th>Carrera</th>
    <th>U1</th>
    <th>U2</th>
    <th>U3</th>
    <th>U4</th>
    <th>U5</th>
    <th>U6</th>
    <th>Promedio Final</th>
</tr>

<?php foreach ($alumnos as $al): ?>
<tr>
    <td><?= $al['matricula'] ?></td>
    <td><?= $al['carrera'] ?></td>
    <?php
    $disabled = $al['acta_cerrada'] ? 'disabled' : '';
    ?>
    <td><input type="number" name="unidad1[<?= $al['id_alumno'] ?>]" value="<?= $al['unidad_1'] ?>" <?= $disabled ?>></td>
    <td><input type="number" name="unidad2[<?= $al['id_alumno'] ?>]" value="<?= $al['unidad_2'] ?>" <?= $disabled ?>></td>
    <td><input type="number" name="unidad3[<?= $al['id_alumno'] ?>]" value="<?= $al['unidad_3'] ?>" <?= $disabled ?>></td>
    <td><input type="number" name="unidad4[<?= $al['id_alumno'] ?>]" value="<?= $al['unidad_4'] ?>" <?= $disabled ?>></td>
    <td><input type="number" name="unidad5[<?= $al['id_alumno'] ?>]" value="<?= $al['unidad_5'] ?>" <?= $disabled ?>></td>
    <td><input type="number" name="unidad6[<?= $al['id_alumno'] ?>]" value="<?= $al['unidad_6'] ?>" <?= $disabled ?>></td>
    <td><?= $al['promedio_final'] ?></td>
</tr>
<?php endforeach; ?>

</table>
<button type="submit">Guardar Calificaciones</button>
</form>

<form method="get">
<input type="hidden" name="materia" value="<?= $materia_id ?>">
<button type="submit" name="cerrar" value="1">Cerrar Acta</button>
</form>

<li><a href="docentes.php">Volver</a></li>

</body>
</html>