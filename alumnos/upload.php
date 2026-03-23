<?php
session_start();
require '../conexion.php';

$id_logueado = $_SESSION['id_usuario'];
$target_dir = "../uploads/";

if(isset($_POST["submit"])) {

    $mapa_columnas = [
        'aceptacion' => 'ruta_carta_aceptacion',
        'liberacion' => 'ruta_carta_liberacion',
        'reporte1'   => 'ruta_reporte1',
        'reporte2'   => 'ruta_reporte2',
        'reporte3'   => 'ruta_reporte3'
    ];
    $tipo = $_POST['tipo_documento'];
    $uploadOk = 1;
    $columna = $mapa_columnas[$tipo] ?? null;
    
    $stmt_id = $pdo->prepare("SELECT id_alumno, matricula FROM alumnos WHERE id_usuario = ?");
    $stmt_id->execute([$id_logueado]);
    $alumno = $stmt_id->fetch();
    $id_alumno = $alumno['id_alumno'];
    $matricula = $alumno['matricula'];

    $fileName = $matricula . "_" . $tipo . "_" . time() . ".pdf";
    $target_file = $target_dir . $fileName;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if($fileType != "pdf") {
        echo "Error: Solo se permiten archivos PDF.";
        $uploadOk = 0;
    }

    if ($_FILES["fileUpload"]["size"] > 5000000) {
        echo "Error: El archivo es demasiado grande.";
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $target_file)) {
            
            try {
                $sql = "UPDATE servicio_social SET $columna = ? WHERE id_alumno = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$target_file, $id_alumno]);
                
                header("Location: servicio.php?msg=subida_exitosa");
            } catch(PDOException $e) {
                echo "Error en BD: " . $e->getMessage();
            }

        } else {
            echo "Error al mover el archivo al servidor.";
        }
    }
}
?>