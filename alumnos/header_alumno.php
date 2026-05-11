<?php
if (isset($_SESSION['id_usuario']) && $_SESSION['rol'] == 'alumno') {
    $id_log = $_SESSION['id_usuario'];

    try {
        $sql_ss = "CALL sp_verificar_registro_ss_alumno(?)";
        $stmt_ss = $pdo->prepare($sql_ss);
        $stmt_ss->execute([$id_log]);
        $registro_ss = $stmt_ss->fetch();
        $tiene_registro_ss = ($registro_ss !== false);
        
    } catch (PDOException $e) {
        $tiene_registro_ss = false;
    }

    $tiene_registro_ss = ($registro_ss !== false);
} else {
    $tiene_registro_ss = false;
}