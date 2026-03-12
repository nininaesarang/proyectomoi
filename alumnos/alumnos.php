<?php
session_start();
require '../conexion.php';

$message = null;
$error_message = null;

if(isset($_GET['msg'])){
   if( $_GET['msg'] == 'error' && isset($_GET['detail'])){
    $error_message = htmlspecialchars(urldecode($_GET['detail']));
   }
}

try{
    $sql = "SELECT titulo,
    tipo,
    ruta_archivo,
    fecha_limite
    FROM
    aula_virtual_materiales
    ORDER BY
    fecha_limite ASC";
    $stmt = $pdo->query($sql);
    $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch(PDOException $e){
    $error_message = "Error al cargar las tareas: " . $e->getMessage();
    $tareas = [];
}
?>

<!DOCTYPE html>
<body><html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumnos - Aula Virtual</title>
    <link rel="stylesheet" href="../style.css">
<style>
h3 {text-align: center;}
img {width: 100px;}
a {text-align: center;}
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #c4c2c2;
}
</style>
</head>
    <header>
        <div class="header-container">
            <img src="../img/logotec.png" alt="Instituto Tecnológico Superior de San Pedro">
            <h1>Alumnos</h1>
            <nav>
                <ul>
                    <li><a href="#" class="active">Aula Virtual</a></li>
                    <li><a href="perfil.php">Perfil</a></li>
                    <li><a href="horarios.php">Horario</a></li>
                    <li><a href="calificaciones.php">Calificaciones</a></li>
                    <li><a href="finanzas.php">Estado Financiero</a></li>
                    <li><a href="club.php">Club Escolar</a></li>
                    <li><a href="servicio.php">Servicio Social</a></li>
                    <li><a href="../logout.php">Salir</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="table-container">
            <?php if ($error_message): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <h1 style="text-align: center;">Aula Virtual</h1>
            <br>
            <h3>Tareas Pendientes:</h3>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Tipo</th>
                        <th>Enlace</th>
                        <th>Fecha Límite</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($tareas) > 0): ?>
                        <?php foreach($tareas as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['titulo']); ?></td>
                                <td><?php echo htmlspecialchars($row['tipo']); ?></td>
                                <td><?php echo htmlspecialchars($row['ruta_archivo']); ?></td>
                                <td><?php echo date('dd/mm/yyyy', strtotime($row['fecha_limite'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center;">No hay tareas pendientes.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <br>
            <h3>Acceso al aula virtual: <a class="btn-dashboard btn-opcion" href="https://classroom.google.com/">Acceder</a></h3>
            <br>
            <label class="form-container form-group" style="display: block; text-align: center;"><strong>Nota: El profesor lo añadirá a su clase.</strong></label>
        </div>
        
    </main>
</body>
</html>