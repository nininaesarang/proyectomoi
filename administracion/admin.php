<?php
session_start();
// Validación de seguridad: Si no hay sesión o no es admin, lo pateamos al login
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrativo') {
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrativo - Tec San Pedro</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        img.logo {width: 100px;}
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
            <img src="../img/logotec.png" alt="Instituto Tecnológico Superior de San Pedro" class="logo">
            <h1>Panel Administrativo</h1>
            <nav>
                <ul>
                    <li><a href="admin.php" class="active">Admisión</a></li>
                    <li><a href="alumnos/lista_alumnos.php">Alumnos</a></li>
                    <li><a href="docentes/docentes.php">Docentes</a></li>
                    <li><a href="academica/academica.php">Académica</a></li>
                    <li><a href="pagos.php">Pagos</a></li>
                    <li><a href="reportes.php">Reportes</a></li>
                    <li><a href="mensajes.php">Mensajes</a></li>
                    <li><a href="../logout.php">Salir</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <h2>Proceso de Admisión y Preinscripción</h2>
        
        <div class="dashboard-container">
            <div class="dashboard-item">
                <h3>Gestión de Fichas</h3>
                <p>Registra a los nuevos aspirantes y genera su referencia de pago para el examen de admisión.</p>
                <br>
                <a class="btn-dashboard btn-opcion" href="admision/nueva_ficha.php">Generar Ficha</a>
            </div>

            <div class="dashboard-item">
                <h3>Control de Documentos</h3>
                <p>Valida la entrega de papelería física (Acta, CURP, Certificado) de los aspirantes registrados.</p>
                <br>
                <a class="btn-dashboard btn-aceptar" href="admision/lista_aspirantes.php">Revisar Papeles</a>
            </div>
            
            <div class="dashboard-item">
                <h3>Resultados y Cobros</h3>
                <p>Captura los resultados del examen de admisión y registra los pagos formales de inscripción.</p>
                <br>
                <a class="btn-dashboard btn-historial" href="admision/lista_aspirantes.php">Registrar Resultados</a>
            </div>
        </div>
    </main>
</body>
</html>