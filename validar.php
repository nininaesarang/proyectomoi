<?php
session_start();
include 'conexion.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = filter_var(trim($_POST['correo']), FILTER_SANITIZE_EMAIL);
    $password_form = $_POST['password'];
    $sql = "SELECT id, password, rol FROM usuarios WHERE correo = :correo";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':correo', $email, PDO::PARAM_STR);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($usuario) {
        
        $password_bd = $usuario['password'];

        if ($usuario && password_verify($password_form, $usuario['password'])) {
            $_SESSION['user_id'] = $usuario['id'];
        $_SESSION['user_rol'] = $usuario['rol'];

        header("Location: alumnos.php");
        exit;}
    } else {
        header("Location: login.php?error=1");
        exit;
    }

    $stmt = null;
    $pdo = null;

} else {
    header("Location: login.php");
    exit;
}
?>