<?php
session_start();
require '../conexion.php';

//q el uuario si inicio sesion
if(!isset($_SESSION['id_usuario'])){
    die("Error: Debes iniciar sesión primero");
}

$id_usuario = $_SESSION['id_usuario']; 

$stmtDoc = $pdo->prepare("SELECT id_docente FROM docentes WHERE id_usuario = ?");
$stmtDoc->execute([$id_usuario]);
$docente = $stmtDoc->fetch(PDO::FETCH_ASSOC);

if(!$docente){
    die("Error: Este usuario no está registrado como docente");
}

$id_docente = $docente['id_docente'];

//Guardar observaciones
if(isset($_POST['guardar_observacion'])){
    // ... tu código actual
}

//Enviar mendajito
if(isset($_POST['enviar_mensaje'])){
    $id_alumno = $_POST['id_alumno'];
    $asunto = $_POST['asunto'];
    $mensaje = $_POST['mensaje'];

    $stmt = $pdo->prepare("INSERT INTO mensajes (id_docente, id_alumno, asunto, mensaje) VALUES (?,?,?,?)");
    $stmt->execute([$id_docente, $id_alumno, $asunto, $mensaje]);

    echo "<p style='color:green;'>Mensaje enviado al alumno</p>";
}

//Consultita
$sql = "SELECT 
    alumnos.id_alumno,alumnos.matricula,alumnos.carrera,calificaciones_activas.promedio_final,
    seguimiento_academico.obersevaciones,
    seguimiento_academico.nivel_riesgo,
    alumnos.nombre_completo,
    usuarios.correo
FROM alumnos
LEFT JOIN calificaciones_activas ON calificaciones_activas.id_alumno = alumnos.id_alumno
LEFT JOIN seguimiento_academico 
    ON seguimiento_academico.id_alumno = alumnos.id_alumno
    AND seguimiento_academico.id_docente = ?
LEFT JOIN usuarios ON usuarios.id_usuario = alumnos.id_usuario
ORDER BY alumnos.matricula";

$stmt = $pdo->prepare($sql);
$stmt->execute([$id_docente]);
$alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguimiento Académico</title>
    <link rel="stylesheet" href="../style.css">
</head>
<style>
h3, h1 {text-align: center;}
img {width: 100px;}
a {text-align: center;}
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #c4c2c2;
}
/* Textarea */
textarea {
    width: 100%;
    padding: 6px;
    border-radius: 2px;
    border: 1px solid #ccc;
}

/* Inputs y selects */
input[type=text], select {
    padding: 6px 10px;
    border-radius: 2px;
    border: 1px solid #ccc;
    width: 100%;
    box-sizing: border-box;
}

/* Botones */
button, input[type=submit] {
    padding: 10px 20px;
    border-radius: 2px;
    border: none;
    font-weight: bold;
    cursor: pointer;
    background-color: #720f0c;
    color: white;
    transition: background-color 0.3s ease;
}

button:hover, input[type=submit]:hover {
    background-color: #5a0b09;
}

/* tablita */
.alto { background-color: #ffb3b3; }
.medio { background-color: #ffe0b3; }
.bajo { background-color: #ffffff; }

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
                    <li><a href="asistencias.php">Control de Asistencias</a></li>
                    <li><a href="aula_virtual.php">Aula Virtual</a></li>
                    <li><a href="#">Seguimiento Académico</a></li>
                    <li><a href="../logout.php">Salir</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main class="main-content">
        <div class="table-container">
            <h1>Seguimiento Académico</h1>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Nombre (correo)</th>
                            <th>Matrícula</th>
                            <th>Carrera</th>
                            <th>Promedio</th>
                            <th>Observaciones</th>
                            <th>Riesgo</th>
                            <th>Guardar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($alumnos as $al): 
                            $clase = '';
                            $texto_riesgo = 'Sin datos';
                        
                            if($al['nivel_riesgo'] == 2){
                                $clase = 'alto';
                                $texto_riesgo = 'Alto';
                            } elseif($al['nivel_riesgo'] == 1){
                                $clase = 'medio';
                                $texto_riesgo = 'Medio';
                            } elseif($al['nivel_riesgo'] == 0){
                                $clase = 'bajo';
                                $texto_riesgo = 'Bajo';
                            }
                        ?>
                    </tbody>
                        
                    <tr class="<?= $clase ?>">
                        <form method="post" class="form-container form-group">
                        <td><?= htmlspecialchars($al['correo']) ?></td>
                        <td><?= $al['matricula'] ?></td>
                        <td><?= $al['carrera'] ?></td>
                        <td><?= $al['promedio_final'] ?></td>
                        
                        <td>
                            <textarea name="observacion"><?= htmlspecialchars($al['obersevaciones']) ?></textarea>
                            <input type="hidden" name="id_alumno" value="<?= $al['id_alumno'] ?>">
                            <input type="hidden" name="promedio" value="<?= $al['promedio_final'] ?>">
                        </td>
                        
                        <td><?= $texto_riesgo ?></td>
                        
                        <td><button type="submit" name="guardar_observacion">Guardar</button></td>
                        </form>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <br><br>
                <h2>Alumnos que estan en riesgo con un promedio menos de 70</h2>
                <ul class="lista-riesgo">
                <?php foreach($alumnos as $al): ?>
                    <?php if($al['promedio_final'] !== null && $al['promedio_final'] < 70): ?>
                        <li>
                            <?= $al['nombre_completo'] ?> - 
                            <?= $al['matricula'] ?> : 
                            Promedio <?= $al['promedio_final'] ?> ⚠️
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
                </ul>
                    
                <h2>Enviar Mensajes</h2>
                <form method="post" class="form-container">
                    <div class="form-group">
                        <label>Alumno (correo):</label><br>
                    <select name="id_alumno" required>
                        <option value="">Selecciona un alumno</option>
                        <?php foreach($alumnos as $al): ?>
                            <option value="<?= $al['id_alumno'] ?>">
                                <?= htmlspecialchars($al['nombre_completo']) ?> - <?= $al['matricula'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select><br><br>
                        
                    <label>Asunto:</label><br>
                    <input type="text" name="asunto" required><br><br>
                        
                    <label>Mensaje:</label><br>
                    <textarea name="mensaje" rows="5" required></textarea><br><br>
                        
                    <button type="submit" name="enviar_mensaje">Enviar</button>
                    
                </form>
                <div style="text-align: center;">
                    <a class="btn-dashboard btn-historial" href="docentes.php">Volver</a>
                </div>
        </div>
    </main>
</body>
</html>