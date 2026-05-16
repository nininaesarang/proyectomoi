<?php
session_start();
require '../conexion.php';

$id_usuario = $_SESSION['id_usuario'];

$stmt = $pdo->prepare("CALL consultar_docente(?);");
$stmt->execute([$id_usuario]);

//se trae
$docente = $stmt->fetch(PDO::FETCH_ASSOC);
$id_docente = $docente['id_docente'] ?? 0;

// traer alumnitos
$stmt = $pdo->prepare("call traer_alumnos(?)");
$stmt->execute([$id_docente]);
$alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Traer carga academicaa
//conusultita
$stmt = $pdo->prepare("call traer_carga_academica(?)");
//id del prof que se logeo
$stmt->execute([$id_docente]);
$carga = $stmt->fetch(PDO::FETCH_ASSOC);
//guardar
$id_carga = $carga['id_carga_academica'] ?? 0;

//mandar la asistencia
 //hacer clic
// --- GUARDAR ASISTENCIA ---
if(isset($_POST['guardar_asistencia'])){
    try {
        $stmt = $pdo->prepare("CALL sp_registrar_asistencia(?, ?, ?)");
        foreach($_POST['asistencia'] as $id_alumno => $estatus){
            $stmt->execute([$id_alumno, $_POST['id_carga_academica'], $estatus]);
            $stmt->closeCursor(); // Vital para el loop
        }
        $msg = "Asistencias registradas correctamente.";
    } catch(Exception $e) {
        $error = $e->getMessage();
    }
}

// --- OBTENER ESTADÍSTICAS ---
$historial = [];
if($id_carga > 0) {
    $stmt = $pdo->prepare("CALL sp_get_reporte_asistencias(?)");
    $stmt->execute([$id_carga]);
    
    // Ya no necesitas el cálculo manual en el while, SQL ya te dio todo
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $historial[$row['id_alumno']] = $row; 
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Docentes - Control de Asistencias</title>
    <link rel="stylesheet" href="../style.css">
</head>
<style>
h3, h1 {text-align: center;}
img {width: 100px;}
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #c4c2c2;
}
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
</style>
<body>
        <header>
        <div class="header-container">
            <img src="../img/logotec.png" alt="Instituto Tecnológico Superior de San Pedro">
            <h1>Docentes</h1>
            <nav>
                <ul>
                    <li><a href="docentes.php">Inicio</a></li>
                    <li><a href="gestion_academica.php">Gestión Académica</a></li>
                    <li><a href="gestion_calificaciones.php">Gestión de Calificaciones</a></li>
                    <li><a href="#">Control de Asistencias</a></li>
                    <li><a href="aula_virtual.php">Aula Virtual</a></li>
                    <li><a href="seg_academico.php">Seguimiento Académico</a></li>
                    <li><a href="../logout.php">Salir</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main class="main-content">
        <div class="table-container">
            <h1>Registro de Asistencias</h1>
            <form method="post" class="form-container form-group">
                <input type="hidden" name="id_carga_academica" value="<?= $id_carga ?>">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Alumno</th>
                            <th>Matricula</th>
                            <th>Semestre</th>
                            <th>Asistencia</th>
                            <th>Historial</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($alumnos as $alumno): 
                    $id_al = $alumno['id_alumno'];
                    $hist = $historial[$id_al] ?? ['faltas'=>0,'retardos'=>0,'porcentaje'=>100,'estado'=>'Normal'];
                ?>
                <tr>
                    <td><?= $alumno['nombre_completo'] ?></td>
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
                    </tbody>
                </table>
                <input class="form-group" type="submit" name="guardar_asistencia" value="Guardar Asistencias">

            </form>
            <div style="text-align: right;">
                <a class= "btn-dashboard btn-historial" href="docentes.php">Volver</a>
            </div>
        </div>
    </main>
</body>
</html>