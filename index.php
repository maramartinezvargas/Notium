<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Se incluye el archivo de CSS de Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Se incluyen los archivos de JavaScript de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Se incluye el archivo de CSS de Google Fonts para poder usar la fuente Inter-->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Se incluye el archivo de estilos personalizado -->
    <link rel="stylesheet" href="styles.css">
    <title>Notium</title>
</head>
<body>
    <?php
    // Se incluyen los archivos de la cabecera y la conexión a la base de datos
    include_once 'navbar.php';
    include_once 'conexion.php';
    $mensajeLogin='';
    // Si no se ha podido establecer la conexión con la base de datos, se muestra un mensaje de error
    if ($conexion->connect_error) {
        die('Error de Conexión (' . $conexion->connect_errno . ')' . $conexion->connect_error);
    }else{
        // Se comprueba si el usuario está logueado
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
            if (!empty($_POST['email']) && !empty($_POST['password'])) {
                $email = $_POST['email'];
                $password = $_POST['password'];
    
                $sql = "SELECT * FROM usuarios WHERE email = '$email'";
                $resultado = $conexion->query($sql);
                // Se comprueba si el correo introducido está registrado en la base de datos
                if ($resultado->num_rows > 0) {
                    $usuario = $resultado->fetch_assoc();
                    $password_bd = $usuario['pass'];
                    // se verifica si la contraseña introducida coincide con la contraseña encriptada de la base de datos
                    if (password_verify($password, $password_bd)) {
                        setcookie('sesion', $email, time() + 3600, "/");
                        header('Location: tareas.php?correo=' .$email);
                        exit();
                    } else {
                      $mensajeLogin='<div class="alertas alert alert-danger text-center" role="alert">La contraseña introducida no es correcta.</div>';
                    }
                } else {
                    $mensajeLogin='<div class="alertas alert alert-danger text-center" role="alert">El correo electrónico introducido no está registrado.</div>';
                }
            }
        }

        // Se comprueba si hay cookies de sesión activas para redirigir al usuario a su página de tareas
        if (isset($_COOKIE['sesion'])) {
        // Si hay cookies de sesión activas, se redirige al usuario a su página de tareas enviando, como dato para su posterior uso, el correo recuperado de la cookie

            $correo = $_COOKIE['sesion'];
            header('Location: tareas.php?correo='. $correo);
        }else{
        // Si no hay cookies de sesión activas, se muestra el contenido principal de la página de inicio
        ?>
        <main class="content"> <!-- Clase content para darle padding al contenido principal y que no lo tape el nav o el footer-->
            <div class="bienvenida container text-center mt-5 pt-5 pb-5 mb-5">
                <h1>Te damos la bienvenida a Notium</h1>
                <p class="descripcion">La mejor plataforma para gestionar tus tareas de forma sencilla, eficiente y organizada</p>
            </div>
            <?php
            // Si se ha enviado elegido la opción de registro o login, se muestra el formulario correspondiente
            if (isset($_POST['registro'])) {
            ?>
<!-- Formulario de registro ------------------------------------------------------------ -->
                <div class="container-registro">
                    <h2 class="text-center">Regístrate</h2>
                    <form class="form-row" action="index.php" method="POST">
                        <div class="form-group col-md-6 offset-md-3">
                            <label for="nombre">Nombre de usuario</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Introduce tu nombre de usuario" required>
                            <label for="email">Correo electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Introduce tu correo electrónico" required>
                            <label for="password">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Introduce tu contraseña" required>
                            <label for="politicas" class="mt-2">Políticas de privacidad</label>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input ms-2 mt-2 politicas-privacidad" id="politicas" required>
                                <label class="politicas-privacidad form-check-label ms-2" for="politicas">Aceptar <a class="politicas-privacidad form-check-label ms-2" href="politicas.php" target="_blank">nuestras políticas de privacidad</a></label>
                            </div>
                            <label for="registro" class="mt-3"></label>
                            <button type="submit" class="btn btn-dark btn-sm mt-3" name="registro">Registrarse</button>
                        </div>
                    </form>
                    <form class="form-centrado" action="index.php" method="POST">
                        <label for="login" class="mt-3"></label>
                        <button type="submit" class="btn-concuenta btn btn-link mt-3" name="login">¿Ya tienes cuenta? Inicia sesión</button>
                    </form>
                    
                <?php

                // Si envía el formulario de registro y los campos no están vacíos, se guardan los datos en la base de datos
                if (!empty($_POST['nombre']) && !empty($_POST['email']) && !empty($_POST['password'])) {
                    // Se guardan los datos introducidos en el formulario
                    $nombre = $_POST['nombre'];
                    $email = $_POST['email'];

                    // Verificar si el correo ya existe
                    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
                    $resultado = $conexion->query($sql);
                    $password= $_POST['password'];
                    if ($resultado->num_rows == 0) {
                         
                            // Encriptar la contraseña antes de verificar si el correo ya existe
                            $password_hashed = password_hash($password, PASSWORD_DEFAULT);

                            // Insertar usuario en la base de datos
                            $sql = "INSERT INTO usuarios (email, nombreUsuario, pass) VALUES ('$email', '$nombre', '$password_hashed')";
                            
                            if ($conexion->query($sql) === TRUE) {
                                echo '<div class="alertas alert alert-success text-center" role="alert">¡Te has registrado correctamente!</div>';
                            } else {
                                echo '<div class="alertas alert alert-danger text-center" role="alert">Error en el registro: ' . $conexion->error . '</div>';
                            }

                    } else {     
                            echo '<div class="alertas alert alert-danger text-center" role="alert">El correo electrónico introducido ya está registrado</div>';
                        
                    }
                }
                ?>
                </div>
            <?php
            }elseif (isset($_POST['login'])) {
            ?>
            <div class="container-login">
<!-- Formulario de login ----------------------------------------------- -->
                <h2 class="text-center">Inicia sesión</h2>
                <form class="form-row" action="index.php" method="POST">
                    <div class="form-group col-md-6 offset-md-3">
                        <label for="email">Correo electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Introduce tu correo electrónico" required >
                        <label for="password">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Introduce tu contraseña" required >
                        <button type="submit" class="btn btn-dark btn-sm mt-3" name="login">Iniciar sesión</button>
                    </div>
                </form>
                <!-- Botón para usuarios que no se han registrado aún que les redirija a la página index.php con la opción de registro -->
                <form class="form-centrado" action="index.php" method="POST">
                        <label for="login" class="mt-3"></label>
                        <button type="submit" class="btn-concuenta btn btn-link mt-3" name="registro" value="registro">
                        ¿No tienes cuenta aún? Regístrate
                    </button>
                </form>

                <!-- Se muestran mensajes de alerta según el resultado del login -->
                <?php
                if ($mensajeLogin) {
                    echo $mensajeLogin;
                }
                ?>

            </div>
            <?php
            }else{ 
            // Si no se ha elegido ninguna opción, se muestra la imagen de bienvenida y los botones de registro y login
            ?>
            <!-- imagen de bienvenida -->
            <div class="bienvenida container text-center mt-5 pt-5 pb-5 mb-5">
                <img class="img-bienvenida" src="img/bienvenidaNotium.png" alt="Notium">
            </div>

            <div class="container-botones text-center">
                <!-- Botones para registro y login -->
                <form action="index.php" method="POST">
                    <button type="submit" class="btn btn-dark btn-sm mt-3" name="registro">Regístrate</button>
                    <button type="submit" class="btn btn-dark btn-sm mt-3" name="login">Inicia sesión</button>
                </form>
            </div>
            <?php
            }
            ?>
        </main>
        <?php
        }
        $conexion->close();
    }
    ?>
    <?php
    include_once 'footer.php';
    ?>
</body>
</html>
