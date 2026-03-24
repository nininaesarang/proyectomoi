<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<title>Docentes - Gestión Académica</title>
<link rel="stylesheet" href="../style.css">
<style>
h3, h1 {text-align: center;}
img {width: 100px;}
a {text-align: center;}
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #c4c2c2;
}

/* Haber q tal diseñito*/
.opciones{
    display:flex;
    justify-content:center;
    gap:30px;
    margin-top:50px;
    flex-wrap: wrap;
}

/* parte d arriba */
.seccion{
    background-color: #ffffff;
    border: 1px solid #e0e0e0; 
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    padding: 25px 30px;
    width: 260px;
    text-align: center;
    transition: transform 0.2s ease;
}

.seccion:hover{
    transform: translateY(-5px);
}

/* Títulos */
.seccion h3{
    margin-top: 0;
    font-size: 22px;
    color: #d53333;
}

/* Texto */
.seccion p{
    color: #777;
    font-size: 15px;
    margin-bottom: 20px;
}

/* Botones */
.seccion a{
    display: inline-block;
    text-decoration: none;
    color: white;
    padding: 10px 18px;
    border-radius: 5px;
    font-weight: bold;
    background-color: #d53333;
    transition: background-color 0.3s ease;
}

.seccion a:hover{
    background-color: #771313;
}

</style>
</head>
<body>
    <header>
        <div class="header-container">
            <img src="../img/logotec.png" alt="Instituto Tecnológico Superior de San Pedro">
            <h1>Docentes</h1>
            <nav>
                <ul>
                    <li><a href="docentes.php">Inicio</a></li>
                    <li><a href="#">Gestión Académica</a></li>
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
    <div class="table-container">
        <h1>Gestión Académica</h1>
        <div class="opciones">
            <div class="seccion">
                <h3>Grupos asignados</h3>
                <p>Ver los grupos y materias asignadas.</p>
                <a href="grupos.php">Consultar</a>
            </div>

            <div class="seccion">
                <h3>Horario</h3>
                <p>Consultar el horario semanal.</p>
                <a href="horario.php">Consultar</a>
            </div>

            <div class="seccion">
            <h3>Lista de alumnos</h3>
            <p>Ver alumnos inscritos por grupo.</p>
            <a href="alumnos_grupo.php">Consultar</a>
            </div>

        </div>

        <br>

        <br>
        <div style="text-align: center;">
            <a class="btn-dashboard btn-historial" href="docentes.php">Volver</a>
        </div> 
    </div>
</main>
</body>
</html>