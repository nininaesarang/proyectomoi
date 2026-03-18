<?php
if (isset($_SESSION['id_usuario']) && $_SESSION['rol'] == 'alumno') {
    $id_log = $_SESSION['id_usuario'];

    try {
        $sql_ss = "SELECT ss.id_alumno 
                   FROM servicio_social ss
                   INNER JOIN alumnos a ON ss.id_alumno = a.id_alumno
                   WHERE a.id_usuario = ? 
                   LIMIT 1";
                   
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