<?php
session_start();
require '../conexion.php';

$id_logueado = $_SESSION['id_usuario'];
$target_dir = "../uploads/";

if(isset($_POST["submit"])) {

    
    $tipos_validos = ['aceptacion', 'liberacion', 'reporte1', 'reporte2', 'reporte3'];
    $tipo = $_POST['tipo_documento'];
    $uploadOk = 1;
    
    if (!in_array($tipo, $tipos_validos)) {
        die("Error: Tipo de documento no válido.");
    }
    
    
    $stmt_id = $pdo->prepare("CALL sp_obtener_id_matricula_alumno(?)");
    $stmt_id->execute([$id_logueado]);
    $alumno = $stmt_id->fetch();
    
    
    $stmt_id->closeCursor();

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
                $sql = "CALL sp_actualizar_documento_servicio(?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$id_alumno, $tipo, $target_file]);
                
                header("Location: servicio.php?msg=subida_exitosa");
                exit;
            } catch(PDOException $e) {
                echo "Error en BD: " . $e->getMessage();
            }

        } else {
            echo "Error al mover el archivo al servidor.";
        }
    }
}
?>