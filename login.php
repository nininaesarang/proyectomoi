<?php
    $error = null;
    if (isset($_GET['error'])) {
        $error = "Usuario o contraseña incorrectos. Intenta de nuevo.";
    }

    $tipo_acceso = $_GET['acceso'] ?? 'alumno';

    switch($tipo_acceso){
        case 'administrativo':
            $titulo = "Portal Administrativo";
            break;
        case 'docente':
            $titulo = "Acceso a docentes";
            break;
        default:
            $titulo = "Portal de alumnos";
            break;
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body{
            background-image: url('img/tec.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            width: 100%;
            max-width: 420px;
            margin: 0 3%;
        }

        .header {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #2d3748;
            font-size: 28px;
            margin-bottom: 8px;
        }

        .header p {
            color: #718096;
            font-size: 14px;
        }

        .form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group label {
            color: #2d3748;
            font-weight: 600;
            font-size: 14px;
        }

        .form-group input {
            padding: 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-group input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-submit {
            background: linear-gradient(135deg, #141414 0%, #463e3e 100%);
            color: white;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            margin-top: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(66, 59, 59, 0.3);
        }

        .btn-submit:active {
            transform: translateY(0);
        }
        img {width: 300px; display: flex;}

        .message-box {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            line-height: 1.5;
        }
        
        .error {
            background-color: #fff5f5;
            border: 1px solid #fc8181;
            color: #c53030;
        }
        
        .error ul { list-style-position: inside; }

        .success {
            background-color: #f0fff4;
            border: 1px solid #68d391;
            color: #2f855a;
            text-align: center;
        }
        
    </style>
</head>
<body>
    <div class="container header">
        <h1><?php echo $titulo;?></h1> <br>
        <img src="img/logotec.png" alt="Instituto Tecnológico Superior de San Pedro">
    </div>
    <div class="container">

        <div class="header">
            <h1>Iniciar sesión</h1>
            <p>Completa el formulario para iniciar sesión</p>
        </div>

        <form id="loginForm" class="form" action="validar.php" method="POST">
            <?php if ($error): ?>
                <p style="color: red; text-align: center;"><?php echo $error; ?></p>
            <?php endif; ?>
            <input type="hidden" name="rol" value="<?php echo $tipo_acceso;?>">
            <div class="form-group">
                <label for="correo">Correo Electrónico</label>
                <input 
                    type="text" 
                    id="correo" 
                    name="correo" 
                    placeholder="ejemplo@correo.com"
                >
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Escribe tu contraseña"
                >
            </div>

            <button type="submit" class="btn-submit">Iniciar sesión</button>
        </form>
    </div>
</body>
</html>