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
    $sql = "SELECT g.nombre_grupo,
    h.dia_semana,
    h.hora_inicio,
    h.hora_fin,
    h.aula,
    m.nombre_materia,
    d.nombre_completo,
    ce.nombre_periodo
    FROM alumnos a
    inner join grupos g on a.id_grupo = g.id_grupo
    inner join carga_academica ca on g.id_grupo = ca.id_grupo
    inner join horarios h on h.id_carga_academica = ca.id_carga_academica
    inner join materias m on m.id_materia = ca.id_materia
    inner join docentes d on d.id_docente = ca.id_docente
    inner join ciclos_escolares ce on ce.id_ciclo = ca.id_ciclo
    where a.id_usuario = ?
    ORDER BY FIELD(h.dia_semana, 'lunes', 'martes', 'miercoles', 'jueves', 'viernes'), h.hora_inicio ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_logueado]);
    $horario = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch(PDOException $e){
    $error_message = "Error al cargar el horario " . $e->getMessage();
    $horario = [];
}

date_default_timezone_set('America/Mexico_City');
?>

<!DOCTYPE html>
<html lang="es"> <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumnos - Horarios</title>
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
                    <li><a href="#">Horario</a></li>
                    <li><a href="calificaciones.php">Calificaciones</a></li>
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

            <h1 style="text-align: center;">Horario</h1>
            <div class="form-group">
                <?php if(!empty($horario)): ?>
                    <label><strong>Grupo: </strong><?php echo htmlspecialchars($horario[0]['nombre_grupo']); ?></label>
                    <label><strong>Periodo: </strong><?php echo htmlspecialchars($horario[0]['nombre_periodo']); ?></label>
                <?php endif; ?>
            </div>
            
            <div class="form-group"">
                <label for="select-filter">Ver horario del día:</label>
                <select onchange="filtrar()" id="select-filter">
                    <option value="todos">Semana Completa</option>
                    <option value="lunes">Lunes</option>
                    <option value="martes">Martes</option>
                    <option value="miercoles">Miércoles</option>
                    <option value="jueves">Jueves</option>
                    <option value="viernes">Viernes</option>
                </select>
            </div>

            <div id="horarios-container">
                <?php
                $dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'];
                foreach($dias as $dia):
                    $materias_dias = array_filter($horario, function($item) use ($dia){
                        return strcasecmp($item['dia_semana'], $dia) === 0;
                    });
                    $id_dia = str_replace(['é', 'í'], ['e', 'i'], $dia);
                ?>
                    <div class="form-container dia-content" id="content-<?php echo $id_dia; ?>">
                        <h3><?php echo ucfirst($dia);?></h3>
                        <table class="history-table">
                            <thead>
                                <tr>
                                    <th>Materia / Docente</th>
                                    <th>Hora</th>
                                    <th>Aula</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($materias_dias) > 0): ?>
                                    <?php foreach($materias_dias as $row): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($row['nombre_materia']); ?></strong><br>
                                                <small><?php echo htmlspecialchars($row['nombre_completo']); ?></small>
                                            </td>
                                            <td><?php echo date('H:i A', strtotime($row['hora_inicio'])) . " a " . date('H:i A', strtotime($row['hora_fin'])); ?></td>
                                            <td><?php echo htmlspecialchars($row['aula']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" style="text-align: center;">No tienes un horario asignado.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <br>
                <?php endforeach; ?>
            </div>    
        </div>
    </main>

    <script>
        function filtrar() {
            const selector = document.getElementById('select-filter');
            const valor = selector.value;
            const todosLosDias = document.querySelectorAll('.dia-content');

            todosLosDias.forEach(div => {
                const diaId = div.id.replace('content-', '');
                if (valor === 'todos') {
                    div.style.display = 'block';
                } else if (diaId === valor) {
                    div.style.display = 'block';
                } else {
                    div.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>