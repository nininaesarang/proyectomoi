<?php
session_start();
require '../conexion.php';
require 'header_alumno.php';

$message = null;
$error_message = null;

if(isset($_GET['msg'])){
   if( $_GET['msg'] == 'error' && isset($_GET['detail'])){
    $error_message = htmlspecialchars(urldecode($_GET['detail']));
   }
}

$id_logueado = $_SESSION['id_usuario'];

try{

    $sql = "CALL sp_obtener_calificaciones_activas_alumno(?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_logueado]);
    $calif = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql_kardex = "CALL sp_obtener_kardex_alumno_usuario(?)";
    $stmt_k = $pdo->prepare($sql_kardex);
    $stmt_k->execute([$id_logueado]);
    $kardex_data = $stmt_k->fetchAll(PDO::FETCH_ASSOC);
}
catch(PDOException $e){
    $error_message = "Error al cargar " . $e->getMessage();
    $calif = [];
    $kardex_data = [];
}

date_default_timezone_set('America/Mexico_City');
?>

<!DOCTYPE html>
<html lang="es"> <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumnos - Calificaciones</title>
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
<body>
    <header>
        <div class="header-container">
            <img src="../img/logotec.png" alt="Instituto Tecnológico Superior de San Pedro">
            <h1>Alumnos</h1>
            <nav>
                <ul>
                    <li><a href="alumnos.php">Aula Virtual</a></li>
                    <li><a href="perfil.php">Perfil</a></li>
                    <li><a href="horarios.php">Horario</a></li>
                    <li><a href="#">Calificaciones</a></li>
                    <li><a href="finanzas.php">Estado Financiero</a></li>
                    <li><a href="club.php">Club Escolar</a></li>
                    <?php if (isset($_SESSION['id_usuario']) && $_SESSION['rol'] == 'alumno' && $tiene_registro_ss): ?>
                    <li><a href="servicio.php">Servicio Social</a></li>
                    <?php endif; ?>
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

            <h1 style="text-align: center;">Calificaciones y Kardex</h1>
            <br>
            <div class="form-group">
                <?php if(!empty($calif)): ?>
                    <label><strong>Grupo: </strong><?php echo htmlspecialchars($calif[0]['nombre_grupo']); ?></label>
                    <label><strong>Periodo: </strong><?php echo htmlspecialchars($calif[0]['nombre_periodo']); ?></label>
                <?php endif; ?>
            </div>
            
            <div class="form-group"">
                <label for="select-filter">Ver:</label>
                <select onchange="vista()" id="select-filter">
                    <option value="section-calif">Calificaciones</option>
                    <option value="section-kardex">Kardex</option>
                </select>
                <a onclick="imprimir()" class="btn-dashboard btn-opcion">Generar PDF</a>
            </div>

            <div id="section-calif" class="form-container main-view">
                <h2>Calificaciones Parciales</h2>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Materia / Docente</th>
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
                        <?php if(count($calif) > 0): ?>
                            <?php foreach($calif as $row): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($row['nombre_materia']);?></strong><br>
                                        <small><?php echo htmlspecialchars($row['nombre_completo']);?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['unidad_1']);?></td>
                                    <td><?php echo htmlspecialchars($row['unidad_2']);?></td>
                                    <td><?php echo htmlspecialchars($row['unidad_3']);?></td>
                                    <td><?php echo htmlspecialchars($row['unidad_4']);?></td>
                                    <td><?php echo htmlspecialchars($row['unidad_5']);?></td>
                                    <td><?php echo htmlspecialchars($row['unidad_6']);?></td>
                                    <td><?php echo htmlspecialchars($row['promedio_final']);?></td>
                                </tr>
                            <?php endforeach;?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" style="text-align: center;">No han subido calificaciones.</td>
                            </tr>
                        <?php endif;?>
                    </tbody>
                </table>
                <br>
            <label class="form-container form-group" style="display: block; text-align: center;"><strong>Nota: El profesor subirá las calificaciones.</strong></label>
            </div>
            <div id="section-kardex" class="form-container main-view">
                <h2>Kardex Académico</h2>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Materia</th>
                            <th>Periodo</th>
                            <th>Calificación</th>
                            <th>Aprobación</th>
                            <th>Oportunidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($kardex_data) > 0): ?>
                            <?php foreach($kardex_data as $row_k): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($row_k['nombre_materia']);?></strong></td>
                                    <td><?php echo htmlspecialchars($row_k['nombre_periodo']);?></td>
                                    <td><?php echo htmlspecialchars($row_k['calificacion_definitiva']);?></td>
                                    <td><?php echo htmlspecialchars($row_k['estatus_aprobacion']);?></td>
                                    <td><?php echo htmlspecialchars($row_k['oportunidad']);?></td>
                                </tr>
                            <?php endforeach;?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center;">No tienes un kardex aún.</td>
                            </tr>
                        <?php endif;?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        function vista() {
            const selector = document.getElementById('select-filter');
            const vistaSeleccionada = selector.value;
            const vistas = document.querySelectorAll('.main-view');

            vistas.forEach(div => {
                if (div.id === vistaSeleccionada) {
                    div.style.display = 'block';
                }
                else {
                    div.style.display = 'none';
                }
            });
        }
    function imprimir() {
    window.print();
    }
    </script>
</body>
</html>