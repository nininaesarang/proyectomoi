<?php
session_start();
include 'conexion.php';

$correo = $_POST['correo'] ?? '';
$password = $_POST['password'] ?? '';

if ($correo && $password) {

    $sql = "SELECT id_usuario, password, rol FROM usuarios WHERE correo = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$correo]);
    $usuario = $stmt->fetch();


    if ($usuario) {
        
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
                header("Location: login.php?error=rol_desconocido");
        }
        exit;
    } else {
        header("Location: login.php?error=1");
        exit;
    }

    $stmt = null;
    $pdo = null;

} else {
    header("Location: login.php?error=campos_vacios");
    exit;
}
?>