<?php
session_start();
require '../conexion.php';

$id_logueado = $_SESSION['id_usuario'];
$target_dir = "../uploads/";

if(isset($_POST["submit"])) {
    $tipo = $_POST['tipo_documento'];
    $uploadOk = 1;
    
    $stmt_id = $pdo->prepare("CALL formato_pdf(?)");
    $stmt_id->execute([$id_logueado]);
    $alumno = $stmt_id->fetch();
    $id_alumno = $alumno['id_alumno'];
    $matricula = $alumno['matricula'];
    $stmt_id->closeCursor();

    $fileName = $matricula . "_" . $tipo . "_" . time() . ".pdf";
    $target_file = $target_dir . $fileName;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if($fileType != "pdf") {
        echo "Error: Solo se permiten archivos PDF.";
        $uploadOk = 0;
    }

    if ($_FILES["fileUpload"]["size"] > 5000000) {
        echo "Error: El archivo es demasiado grande (Máx 5MB).";
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $target_file)) {
            
            try {
                $p_aceptacion = null;
                $p_liberacion = null;
                $p_reporte1 = null;
                $p_reporte2 = null;
                $p_reporte3 = null;

                switch($tipo) {
                    case 'aceptacion': $p_aceptacion = $target_file; break;
                    case 'liberacion': $p_liberacion = $target_file; break;
                    case 'reporte1':   $p_reporte1   = $target_file; break;
                    case 'reporte2':   $p_reporte2   = $target_file; break;
                    case 'reporte3':   $p_reporte3   = $target_file; break;
                }

                $stmt = $pdo->prepare("CALL subir_archivos_servicio(?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $id_alumno, 
                    $p_aceptacion, 
                    $p_liberacion, 
                    $p_reporte1, 
                    $p_reporte2, 
                    $p_reporte3
                ]);
                
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