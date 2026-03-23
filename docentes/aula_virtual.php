<?php
session_start();
require '../conexion.php';

// id de quien iniicio esion
$id_usuario = $_SESSION['id_usuario'];


 // biscar docentito consultita


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

        //guardamso
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
    <title>Aula Virtual</title>
    <style>
        body {
    font-family: times new roman, serif;
    padding: 20px;
    background-color: #f5f5f5;
}

/* Títulos */
h2, h3 {
    color: #d53333;
    text-align: center;
}

/* Formulario para subir la tareita*/
.formulario {
    background-color: #ffffff;
    border-radius: 5px;
    padding: 20px;
    width: 400px;
    margin: 20px auto;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  
}



.formulario input[type="text"],
.formulario input[type="file"],
.formulario input[type="datetime-local"] {
    width: 100%;
    padding: 8px 10px;
    margin-bottom: 15px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 14px;
}

.formulario button {
    background-color: #a92d29;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.formulario button:hover {
    background-color: #5a0b09;
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
    <h2>Aula Virtual</h2>

    <div class="formulario">
        <h3>Subir nueva tarea/material</h3>
        <form method="post" enctype="multipart/form-data">
            <label>Título:</label><br>
            <input type="text" name="titulo" required><br><br>

            <label>Tipo (tarea, proyecto, material):</label><br>
            <input type="text" name="tipo" required><br><br>

            <label>Archivo:</label><br>
            <input type="file" name="archivo" required><br><br>

            <label>Fecha límite:</label><br>
            <input type="datetime-local" name="fecha_limite" required><br><br>

            <button type="submit" name="subir">Subir</button>
        </form>
    </div>

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
</body>

<li><a href="docentes.php">Volver</a></li>

</html>