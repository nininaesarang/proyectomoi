<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <header>
        <div class="header-container">
            <img src="img/logotec.png" alt="Instituto Tecnológico Superior de San Pedro">
            <h1>Gestión Académica</h1>
        </div>
    </header>
<meta charset="UTF-8">
<title>Gestión Académica</title>
<link rel="stylesheet" href="../style.css">

<style>

/* Haber q tal disenito*/


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


/* Botón volver */
li{
    list-style: none;
    text-align: center;
    margin-top: 30px;
}

li a{
    text-decoration: none;
    background-color: #6c757d;
    color: white;
    padding: 10px 18px;
    border-radius: 5px;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

li a:hover{
    background-color: #5a6268;
}

</style>

</head>

<body>

<h2 style="text-align:center;">Gestión Académica</h2>

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

<li><a href="docentes.php">Volver</a></li>

</body>
</html>