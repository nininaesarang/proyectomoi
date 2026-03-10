<!DOCTYPE html>
<body><html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="style.css">
<style>
img {width: 100px;}
</style>
</head>
    <header>
        <div class="header-container">
            <img src="img/logotec.png" alt="Instituto Tecnológico Superior de San Pedro">
            <h1>Bienvenido</h1>
        </div>
    </header>

    <main class="main-content">
        <div class="table-container">
            <h2>Sistema de Gestión escolar</h2>
            <div class="slide">
                <div class="slide-inner">
                    <input class="slide-open" type="radio" id="slide-1" name="slide" aria-hidden="true" hidden="" checked="checked">
                <div class="slide-item">
                    <img src="img/administrativo.jpg">
                    <div class="caption">
                        <h2>Administración</h2>
                        <a href="login.php?acceso=admin">Ir al sistema</a>
                    </div>
                </div>
                <input class="slide-open" type="radio" id="slide-2" name="slide" aria-hidden="true" hidden="">
                <div class="slide-item">
                    <img src="img/docente.jpg">
                    <div class="caption">
                        <h2>Docentes</h2>
                        <a href="login.php?acceso=docente">Ir al sistema</a>
                    </div>
                </div>
                <input class="slide-open" type="radio" id="slide-3" name="slide" aria-hidden="true" hidden="">
                <div class="slide-item">
                    <img src="img/alumno.jpg">
                    <div class="caption">
                        <h2>Alumnos</h2>
                        <a href="login.php?acceso=alumno">Ir al sistema</a>
                    </div>
                </div>
                <label for="slide-3" class="slide-control prev control-1"> ‹ </label>
                <label for="slide-2" class="slide-control next control-1"> › </label>
                <label for="slide-1" class="slide-control prev control-2"> ‹ </label>
                <label for="slide-3" class="slide-control next control-2"> › </label>
                <label for="slide-2" class="slide-control prev control-3"> ‹ </label>
                <label for="slide-1" class="slide-control next control-3"> › </label>
                <ol class="slide-indicador">
                    <li>
                        <label for="slide-1" class="slide-circulo">•</label>
                    </li>
                    <li>
                        <label for="slide-2" class="slide-circulo">•</label>
                    </li>
                    <li>
                        <label for="slide-3" class="slide-circulo">•</label>
                    </li>
                </ol>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
