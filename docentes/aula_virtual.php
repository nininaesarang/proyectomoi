<?php
session_start();
require '../conexion.php';

// id de quien iniicio esion
$id_usuario = $_SESSION['id_usuario'];

 // buscar docentito consultita
$stmtDocente = $pdo->prepare("SELECT id_docente FROM docentes WHERE id_usuario = ?");
$stmtDocente->execute([$id_usuario]);
$docente = $stmtDocente->fetch(PDO::FETCH_ASSOC);

//si no hay
if(!$docente) {
    die("<p style='color:red;'>No se encontró el docente</p>");
}

$id_docente = $docente['id_docente'];

// subir tareita 
if(isset($_POST['subir'])) {
    $titulo = $_POST['titulo'];
    $tipo = $_POST['tipo'];
    $fecha_limite = $_POST['fecha_limite'];
    
    // guardamos lo q subimos
    if(isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
        $nombreArchivo = time() . "_" . $_FILES['archivo']['name'];
        $rutaDestino = "subirArch/" . $nombreArchivo; // donde se guardan archivitos
        move_uploaded_file($_FILES['archivo']['tmp_name'], $rutaDestino);

        // carga docenteee
        $stmtCarga = $pdo->prepare("SELECT id_carga_academica FROM carga_academica WHERE id_docente = ?");
        $stmtCarga->execute([$id_docente]);
        $carga = $stmtCarga->fetch(PDO::FETCH_ASSOC);

        //guardamos
        if($carga) {
            $id_carga_academica = $carga['id_carga_academica'];

            // guardar lo q se puso
            $sql = "INSERT INTO aula_virtual_materiales (id_carga_academica, titulo, tipo, ruta_archivo, fecha_limite)
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id_carga_academica, $titulo, $tipo, $rutaDestino, $fecha_limite]);

            echo "<p style='color:green;'>Archivo subido correctamente.</p>";
        } else {
            echo "<p style='color:red;'>Este docente no tiene carga académica asignada.</p>";
        }
    } else {
        echo "<p style='color:red;'>Error al subir archivo.</p>";
    }
}

// materia del docente
$stmtCarga = $pdo->prepare("SELECT id_carga_academica FROM carga_academica WHERE id_docente = ?");
$stmtCarga->execute([$id_docente]);
$carga = $stmtCarga->fetch(PDO::FETCH_ASSOC);
$id_carga_academica = $carga['id_carga_academica'] ?? 0;

$sql = "SELECT * FROM aula_virtual_materiales WHERE id_carga_academica = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_carga_academica]);
$materiales = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Docentes - Aula Virtual</title>
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
/* tareita actual */
.tarea {
    background-color: #ffffff;
    border-radius: 8px;
    padding: 15px 20px;
    margin: 15px auto;
    width: 450px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    transition: transform 0.2s ease;
}

/* Enlaces dentro de tarjeta */
.tarea a {
    color: #490806;
    text-decoration: none;
    font-weight: bold;
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
                    <li><a href="asistencias.php">Control de Asistencias</a></li>
                    <li><a href="#">Aula Virtual</a></li>
                    <li><a href="seg_academico.php">Seguimiento Académico</a></li>
                    <li><a href="../logout.php">Salir</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main class="main-content">
        <div class="table-container">
            <h1>Aula Virtual</h1>
            <h3>Subir nueva tarea/material</h3>
            <form method="post" enctype="multipart/form-data" class="form-container form-group">
            <label>Título:</label><br>
            <input type="text" name="titulo" required><br><br>

            <label>Tipo (tarea, proyecto, material):</label><br>
            <input type="text" name="tipo" required><br><br>

            <label>Archivo:</label><br>
            <div>
                <input class="inputfile" type="file" id="file" name="archivo" accept="application/pdf" required >
                <label for="file" class="flag"><span id="file-name">Selecciona un archivo</span></label>
            </div><br><br>

            <label>Fecha límite:</label><br>
            <input type="datetime-local" name="fecha_limite" required><br><br>
            <div class="form-actions">
                <button class="btn-dashboard btn-aceptar" type="submit" name="subir">Subir</button>
            </div>
        </form>
        </div>
        <br><br>
        <div class="table-container">
            <h3>Materiales y tareas actuales</h3>
            <?php if(count($materiales) > 0): ?>
                <?php foreach($materiales as $m): ?>
                    <div class="tarea">
                        <strong>Título:</strong> <?= htmlspecialchars($m['titulo']) ?><br>
                        <strong>Tipo:</strong> <?= htmlspecialchars($m['tipo']) ?><br>
                        <strong>Archivo:</strong> <a href="<?= htmlspecialchars($m['ruta_archivo']) ?>" target="_blank">Ver</a><br>
                        <strong>Fecha límite:</strong> <?= htmlspecialchars($m['fecha_limite']) ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay tareas subidas</p>
            <?php endif; ?>
            <div style="text-align: center;">
                <a class="btn-dashboard btn-historial" href="docentes.php">Volver</a>
            </div>
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