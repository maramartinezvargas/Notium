<header>
    <!-- Navbar fijada al margen de arriba -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
        <a class="navbar-brand nameNotium" href="index.php">
            <!-- Logo de Notium que redirige a la página de inicio -->
            <img src="img/logo350px.png" class="logo" alt="Logo Notium">
            <span class="nameNotium">Notium</span>
        </a>
        <!-- Botón del menú hamburguesa para tamaño de ventana pequenito-->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" 
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Contenedor de los elementos del navbar -->
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav ml-auto"> <!-- ml-auto alinea a la derecha los siguientes botones-->

                <?php
                // Si el usuario está logueado, se muestra los botones ver tareas, añadir tarea y cerrar sesión
                if (isset($_COOKIE['sesion'])){
                ?>
                    <!-- para redirigir a Tareas -->
                    <form action="tareas.php" method="POST" style="display: inline;">
                        <button type="submit" name="tareas" class="nav-item nav-link btn btn-link">Ver tareas</button>
                    </form>

                    <!-- para redirigir a Añadir tarea -->
                    <form action="tareas.php" method="POST" style="display: inline;">
                        <button type="submit" name="add" class="nav-item nav-link btn btn-link">Añadir tarea</button>
                    </form>

                    <!-- para cerrar sesión -->
                    <form action="tareas.php" method="POST" style="display: inline;">
                        <button type="submit" name="logout" class="nav-item nav-link btn btn-link">Cerrar sesión</button>
                    </form>
                <?php
                }else{
                ?>
                   
                    <?php
                     // Eliminar cookie PHPSESSID para cerrar sesión
                    setcookie("PHPSESSID", "", time() - 3600);
                    ?>
                    <!-- para redirigir a Login -->
                    <form action="index.php" method="POST" style="display: inline;">
                        <button type="submit" name="login" class="nav-item nav-link btn btn-link">Login</button>
                    </form>

                    <!-- para redirigir a Registrarse -->
                    <form action="index.php" method="POST" style="display: inline;">
                        <button type="submit" name="registro" class="nav-item nav-link btn btn-link">Registrarse</button>
                    </form>
                <?php
                }
                ?>

            </div>
        </div>
    </nav>
</header>




