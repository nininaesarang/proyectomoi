<?php
session_start();
include 'conexion.php';

$correo = $_POST['correo'] ?? '';
$password = $_POST['password'] ?? '';
$intento_rol = $_POST['rol'] ?? 'alumno';

if ($correo && $password) {

    
    $sql = "CALL sp_obtener_usuario_login(?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$correo, $password]);
    $usuario = $stmt->fetch();


    if($usuario){
        if ($usuario['rol'] === $intento_rol) {
    
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['rol'] = $usuario['rol'];

        switch($usuario['rol'])
        {
            case 'administrativo':
                header("Location: administracion/admin.php");
                break;
            case 'docente':
                header("Location: docentes/docentes.php");
                break;
            case 'alumno':
                header("Location: alumnos/alumnos.php");
                break;
            default:
                header("Location: login.php?error=rol_desconocido&acceso=" . $intento_rol);
        }
        exit;
        } 
        else {
        header("Location: login.php?error=1&acceso=" . $intento_rol);
        exit;
        }
    }
    else {
        header("Location: login.php?error=1&acceso=" . $intento_rol);
        exit;
    }

} else {
    header("Location: login.php?error=campos_vacios&acceso=" . $intento_rol);
    exit;
}
?>