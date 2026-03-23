<?php
session_start();
// Seguridad: Validar que solo el administrador entre aquí
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrativo') {
    header("Location: ../index.php");
    exit;
}

// Incluimos la conexión a la base de datos
include '../../conexion.php';

$mensaje = '';

// Si el formulario fue enviado por el botón de "Guardar"
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre_completo'] ?? '';

    if (!empty($nombre)) {
        // Generamos una referencia bancaria aleatoria (Ej. FCH-2026-4829)
        $referencia = "FCH-" . date("Y") . "-" . rand(1000, 9999);

        try {
            // Insertamos al aspirante en la base de datos de forma segura
            $sql = "INSERT INTO aspirantes (nombre_completo, ficha_referencia, pago_ficha_realizada, docs_entregados, pago_inscripcion_realizado, aceptado) 
                    VALUES (?, ?, 0, 0, 0, 0)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $referencia]);

            // Mensaje de éxito usando tus estilos CSS
            $mensaje = "<div class='message-box success'>
                            ¡Aspirante registrado correctamente! <br>
                            Su referencia de pago es: <strong>$referencia</strong>
                        </div>";
        } catch (PDOException $e) {
            // Si algo falla en la base de datos, mostramos el error
            $mensaje = "<div class='message-box error'>Error al registrar: " . $e->getMessage() . "</div>";
        }
    } else {
        $mensaje = "<div class='message-box error'>Por favor, ingresa el nombre del aspirante.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Ficha - Administración</title>
    <link rel="stylesheet" href="../../style.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #c4c2c2; }
        img.logo { width: 100px; }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <img src="../../img/logotec.png" alt="Tec San Pedro" class="logo">
            <h1>Panel Administrativo</h1>
            <nav>
                <ul>
                    <li><a href="../admin.php" class="active">Admisión</a></li>
                    <li><a href="#">Alumnos</a></li>
                    <li><a href="#">Docentes</a></li>
                    <li><a href="../../logout.php">Salir</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="form-container">
            <h2>Registrar Nuevo Aspirante</h2>
            
            <?php echo $mensaje; ?>

            <form action="nueva_ficha.php" method="POST">
                <div class="form-group">
                    <label for="nombre_completo">Nombre Completo del Aspirante</label>
                    <input type="text" id="nombre_completo" name="nombre_completo" placeholder="Ej. Juan Pérez García" required>
                </div>
                
                <div class="form-actions">
                    <a href="../admin.php" class="btn-dashboard btn-historial" style="text-decoration:none; padding:11px 20px;">Volver al Panel</a>
                    <button type="submit" class="btn-primary">Generar Ficha de Pago</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>