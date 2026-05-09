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

try {
    $stmt = $pdo->prepare("CALL consultar_pagos(?)");
    $stmt->execute([$id_logueado]);
    $todos_pagos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    $stmt_ss = $pdo->prepare("CALL menu_ss(?)");
    $stmt_ss->execute([$id_logueado]);
    $tiene_registro_ss = (int)$stmt_ss->fetchColumn() > 0;
    $stmt_ss->closeCursor();

    $pagos_realizados = array_filter($todos_pagos, function($pago) {
    return strtolower(trim($pago['pagado'])) === 'pagado'; 
});

$pagos_pendientes = array_filter($todos_pagos, function($pago) {
    return strtolower(trim($pago['pagado'])) === 'pendiente';
});
}
catch(PDOException $e){
    $error_message = "Error al cargar las tareas: " . $e->getMessage();
    $pagos_pendientes = [];
    $pagos_realizados = [];
    $tiene_registro_ss = false;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumnos - Finanzas</title>
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
#section-real {
    display: block;
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
                    <li><a href="calificaciones.php">Calificaciones</a></li>
                    <li><a href="#">Estado Financiero</a></li>
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

            <h1 style="text-align: center;">Estado Financiero</h1>

            <div class="form-group"">
                <label for="select-filter">Ver pagos:</label>
                <select onchange="vista()" id="select-filter">
                    <option value="section-real">Realizados</option>
                    <option value="section-pen">Pendientes</option>
                </select>
                <a onclick="imprimir()" class="btn-dashboard btn-opcion">Generar PDF</a>
            </div>

            <div id="section-real" class="form-container main-view">
                <h2>Pagos Realizados</h2>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Matrícula</th>
                            <th>Concepto de pago</th>
                            <th>Monto</th>
                            <th>Fecha de vencimiento</th>
                            <th>Pagado</th>
                            <th>Referencia Bancaria</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($pagos_realizados) > 0): ?>
                            <?php foreach($pagos_realizados as $row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['matricula']);?></td>
                                    <td><?php echo htmlspecialchars($row['concepto_pago']);?></td>
                                    <td><?php echo "$" . htmlspecialchars($row['monto']);?></td>
                                    <td><?php echo date('d-m-Y', strtotime($row['fecha_vencimiento']));?></td>
                                    <td><?php echo htmlspecialchars($row['pagado']);?></td>
                                    <td><?php echo htmlspecialchars($row['referencia_bancaria']);?></td>
                                </tr>
                            <?php endforeach;?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center;">No has realizado ningun pago.</td>
                            </tr>
                        <?php endif;?>
                    </tbody>
                </table>
            </div>
            <div id="section-pen" class="form-container main-view">
                <h2>Pagos Pendientes</h2>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Matrícula</th>
                            <th>Concepto de pago</th>
                            <th>Monto</th>
                            <th>Fecha de vencimiento</th>
                            <th>Pagado</th>
                            <th>Referencia Bancaria</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($pagos_pendientes) > 0): ?>
                            <?php foreach($pagos_pendientes as $row_p): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row_p['matricula']);?></td>
                                    <td><?php echo htmlspecialchars($row_p['concepto_pago']);?></td>
                                    <td><?php echo "$" . htmlspecialchars($row_p['monto']);?></td>
                                    <td><?php echo date('d-m-Y', strtotime($row_p['fecha_vencimiento']));?></td>
                                    <td><?php echo htmlspecialchars($row_p['pagado']);?></td>
                                    <td><?php echo htmlspecialchars($row_p['referencia_bancaria']);?></td>
                                </tr>
                            <?php endforeach;?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center;">No tienes pagos pendientes.</td>
                            </tr>
                        <?php endif;?>
                    </tbody>
                </table>
                <br>
            <label class="form-container form-group" style="display: block; text-align: center;"><strong>Nota: Debes realizar el pago en tiempo y en forma.</strong></label>
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