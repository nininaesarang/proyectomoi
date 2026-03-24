<?php
session_start();
require '../conexion.php'; 

// 1. Identificar materia
$materia_id = $_GET['materia'] ?? ($_POST['materia_id'] ?? 1);

if (isset($_GET['reabrir'])) {
    $sql_reabrir = "UPDATE calificaciones_activas 
                    SET acta_cerrada = NULL 
                    WHERE id_alumno IN (
                        SELECT a.id_alumno 
                        FROM alumnos a 
                        JOIN carga_academica c ON c.id_grupo = a.id_grupo 
                        WHERE c.id_materia = ?
                    )";
    $stmt_r = $pdo->prepare($sql_reabrir);
    $stmt_r->execute([$materia_id]);
    header("Location: gestion_calificaciones.php?materia=$materia_id&msg=reabierta");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unidad1'])) {
    foreach ($_POST['unidad1'] as $id_alumno => $val) {
        $u1 = $_POST['unidad1'][$id_alumno] ?: null;
        $u2 = $_POST['unidad2'][$id_alumno] ?: null;
        $u3 = $_POST['unidad3'][$id_alumno] ?: null;
        $u4 = $_POST['unidad4'][$id_alumno] ?: null;
        $u5 = $_POST['unidad5'][$id_alumno] ?: null;
        $u6 = $_POST['unidad6'][$id_alumno] ?: null;

        $notas = [$u1, $u2, $u3, $u4, $u5, $u6];
        $suma = 0; $count = 0;
        foreach ($notas as $v) {
            if ($v !== null && $v !== '') { $suma += $v; $count++; }
        }
        $promedio = $count > 0 ? round($suma / $count, 2) : null;

        $check = $pdo->prepare("SELECT id_alumno FROM calificaciones_activas WHERE id_alumno = ?");
        $check->execute([$id_alumno]);
        
        if ($check->fetch()) {
            // Si existe, ACTUALIZAMOS (solo si el acta no está cerrada)
            $sql_upd = "UPDATE calificaciones_activas 
                        SET unidad_1=?, unidad_2=?, unidad_3=?, unidad_4=?, unidad_5=?, unidad_6=?, promedio_final=? 
                        WHERE id_alumno=? AND (acta_cerrada IS NULL OR acta_cerrada != 'si')";
            $stmt = $pdo->prepare($sql_upd);
            $stmt->execute([$u1, $u2, $u3, $u4, $u5, $u6, $promedio, $id_alumno]);
        } else {
            // Si no existe, INSERTAMOS
            $sql_ins = "INSERT INTO calificaciones_activas (id_alumno, unidad_1, unidad_2, unidad_3, unidad_4, unidad_5, unidad_6, promedio_final) 
                        VALUES (?,?,?,?,?,?,?,?)";
            $stmt = $pdo->prepare($sql_ins);
            $stmt->execute([$id_alumno, $u1, $u2, $u3, $u4, $u5, $u6, $promedio]);
        }
    }
    header("Location: gestion_calificaciones.php?materia=$materia_id&msg=ok");
    exit;
}

if (isset($_GET['cerrar'])) {
    $stmt_c = $pdo->prepare("UPDATE calificaciones_activas SET acta_cerrada='si' WHERE id_alumno IN (SELECT a.id_alumno FROM alumnos a JOIN carga_academica c ON c.id_grupo=a.id_grupo WHERE c.id_materia=?)");
    $stmt_c->execute([$materia_id]);
    header("Location: gestion_calificaciones.php?materia=$materia_id&msg=cerrada");
    exit;
}

$sql = "SELECT a.id_alumno, a.matricula, a.carrera, 
               ca.unidad_1, ca.unidad_2, ca.unidad_3, ca.unidad_4, ca.unidad_5, ca.unidad_6, 
               ca.promedio_final, ca.acta_cerrada
        FROM alumnos a
        JOIN carga_academica c ON c.id_grupo = a.id_grupo
        LEFT JOIN calificaciones_activas ca ON ca.id_alumno = a.id_alumno
        WHERE c.id_materia = ?
        ORDER BY a.matricula ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$materia_id]);
$alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$esta_cerrada = (!empty($alumnos) && $alumnos[0]['acta_cerrada'] == 'si');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Calificaciones - ITS San Pedro</title>
    <link rel="stylesheet" href="../style.css">
</head>
<style>
   h1, h3 {text-align: center;}
   img {width: 100px;}
   a {text-align: center;}
   body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #c4c2c2;
   } 
</style>
<body>
    <header>
        <div class="header-container">
            <img src="../img/logotec.png" alt="Instituto Tecnológico Superior de San Pedro">
            <h1>Docentes</h1>
            <nav>
                <ul>
                    <li><a href="docentes.php">Inicio</a></li>
                    <li><a href="gestion_academica.php">Gestión Académica</a></li>
                    <li><a href="gestion_calificaciones.php">Gestión de Calificaciones</a></li>
                    <li><a href="asistencias.php">Control de Asistencias</a></li>
                    <li><a href="aula_virtual.php">Aula Virtual</a></li>
                    <li><a href="seg_academico.php">Seguimiento Académico</a></li>
                    <li><a href="../logout.php">Salir</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="table-container form-container form-group">
            <h1>Gestión de Calificaciones</h1>
            
            <?php if(isset($_GET['msg'])): ?>
                <div style="background: #d4edda; color: #155724; padding: 10px; text-align: center; border-radius: 5px;">
                    ¡Acción realizada con éxito!
                </div>
            <?php endif; ?>

            <form method="post" action="gestion_calificaciones.php?materia=<?= $materia_id ?>">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Matrícula</th>
                            <th>U1</th>
                            <th>U2</th>
                            <th>U3</th>
                            <th>U4</th>
                            <th>U5</th>
                            <th>U6</th>
                            <th>Promedio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alumnos as $al): ?>
                        <tr>
                            <td><?= htmlspecialchars($al['matricula']) ?></td>
                            <?php 
                                $read = ($esta_cerrada) ? 'readonly' : '';
                                $style = ($esta_cerrada) ? 'background-color: #f0f0f0; color: #888;' : '';
                            ?>
                            <td><input type="number" name="unidad1[<?= $al['id_alumno'] ?>]" value="<?= $al['unidad_1'] ?>" <?= $read ?> style="<?= $style ?>"></td>
                            <td><input type="number" name="unidad2[<?= $al['id_alumno'] ?>]" value="<?= $al['unidad_2'] ?>" <?= $read ?> style="<?= $style ?>"></td>
                            <td><input type="number" name="unidad3[<?= $al['id_alumno'] ?>]" value="<?= $al['unidad_3'] ?>" <?= $read ?> style="<?= $style ?>"></td>
                            <td><input type="number" name="unidad4[<?= $al['id_alumno'] ?>]" value="<?= $al['unidad_4'] ?>" <?= $read ?> style="<?= $style ?>"></td>
                            <td><input type="number" name="unidad5[<?= $al['id_alumno'] ?>]" value="<?= $al['unidad_5'] ?>" <?= $read ?> style="<?= $style ?>"></td>
                            <td><input type="number" name="unidad6[<?= $al['id_alumno'] ?>]" value="<?= $al['unidad_6'] ?>" <?= $read ?> style="<?= $style ?>"></td>
                            <td><strong><?= $al['promedio_final'] ?? '--' ?></strong></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="form-actions" style="text-align: center; margin-top: 20px;">
                    <?php if (!$esta_cerrada): ?>
                        <button class="btn-dashboard btn-aceptar" type="submit">Guardar Cambios</button><br><br>
                    <?php else: ?>
                        <p style="color: #de2720;"><strong>Acta Cerrada:</strong> No se pueden realizar cambios.</p>
                    <?php endif; ?>
                </div>
            </form>
            <div style="text-align: center; display: flex; justify-content: center; gap: 20px;">
                <?php if (!$esta_cerrada): ?>
                    <a href="gestion_calificaciones.php?materia=<?= $materia_id ?>&cerrar=1" 
                       class="btn-dashboard btn-opcion" 
                       onclick="return confirm('¿Cerrar acta? Esto bloqueará la edición.')">
                       Cerrar Acta
                    </a>
                <?php else: ?>
                    <a href="gestion_calificaciones.php?materia=<?= $materia_id ?>&reabrir=1" 
                       class="btn-dashboard btn-aceptar" 
                       style="background-color: #28a745;"
                       onclick="return confirm('¿Deseas habilitar la edición nuevamente?')">
                       Reabrir para Editar
                    </a>
                <?php endif; ?>
            </div>
            <br>
            <div style="text-align:center;">
                <a class="btn-dashboard btn-historial" href="docentes.php">Volver</a>
            </div>
        </div>
    </main>
</body>
</html>