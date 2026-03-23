<?php
session_start();
include 'conexion.php';

$correo = $_POST['correo'] ?? '';
$password = $_POST['password'] ?? '';
$intento_rol = $_POST['rol'] ?? 'alumno';

if ($correo && $password) {

    $sql = "SELECT id_usuario, password, rol FROM usuarios WHERE correo = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$correo]);
    $usuario = $stmt->fetch();

    if($usuario && $password === $usuario['password']){
        if ($usuario['rol'] === $intento_rol) {
        
        $password_bd = $usuario['password'];
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