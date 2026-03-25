<?php
session_start();
// Seguridad
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrativo') {
    header("Location: ../../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión Académica - Administración</title>
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
                    <li><a href="../admin.php">Admisión</a></li>
                    <li><a href="../alumnos/lista_alumnos.php">Alumnos</a></li>
                    <li><a href="../docentes/docentes.php">Docentes</a></li>
                    <li><a href="../horarios.php">Horarios</a></li>
                    <li><a href="academica.php" class="active">Académica</a></li>
                    <li><a href="../pagos.php">Pagos</a></li>
                    <li><a href="../reportes.php">Reportes</a></li>
                    <li><a href="../mensajes.php">Mensajes</a></li>
                    <li><a href="../../logout.php">Salir</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <h2>Gestión Académica</h2>
        <p style="text-align: center; margin-bottom: 30px; color: #555;">Administra los periodos, grupos y el catálogo de materias de la carrera.</p>
        
        <div class="dashboard-container">
            <div class="dashboard-item">
                <h3>1. Ciclos Escolares</h3>
                <p>Abre nuevos periodos escolares (ej. Ago-Dic 2026) para poder asignar grupos y cargas académicas.</p>
                <br>
                <a class="btn-dashboard btn-opcion" href="ciclos.php">Gestionar Ciclos</a>
            </div>

            <div class="dashboard-item">
                <h3>2. Grupos Escolares</h3>
                <p>Crea los salones (ej. 8A, 8C) y asígnalos a un ciclo escolar activo para que los alumnos puedan inscribirse.</p>
                <br>
                <a class="btn-dashboard btn-aceptar" href="grupos.php">Gestionar Grupos</a>
            </div>
            
            <div class="dashboard-item">
                <h3>3. Catálogo de Materias</h3>
                <p>Registra las asignaturas del retícula oficial de Ingeniería en Sistemas y sus créditos.</p>
                <br>
                <a class="btn-dashboard btn-historial" href="materias.php">Gestionar Materias</a>
            </div>

            <div class="dashboard-item">
                <h3>4. Control de Retículas/Kárdex</h3>
                <p>Identifica materias seriadas, alumnos repitentes y gestiona el historial académico completo.</p>
                <br>
                <a class="btn-dashboard btn-aceptar" href="control_kardex.php" style="background-color: #28a745;">Revisar Kárdex</a>
            </div>
        </div>
    </main>
</body>
</html>