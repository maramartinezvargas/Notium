<?php
// Conexion a la base de datos
include_once 'conexion.php';

// Verificar si la conexión es correcta
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// PROCESAR MODIFICACIÓN DE TAREA
$mensajeErrorFecha="";
$mensajeModificacion="";
$mensajeEliminar="";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modificar'])) {

        // Se actualiza la tarea en la base de datos
        if (!empty($_POST['idTarea']) && !empty($_POST['titulo']) && !empty($_POST['descripcion']) 
            && !empty($_POST['fecha_inicio']) && !empty($_POST['fecha_fin']) && !empty($_POST['lugar']) 
            && !empty($_POST['tag']) && !empty($_POST['estado'])) {

            // Se comprueba fecha de fin no sea anterior a la fecha de inicio
            if ($_POST['fecha_inicio'] > $_POST['fecha_fin']) {
                $mensajeErrorFecha="<div class='alert alert-danger text-center mt-4 mb-4' role='alert'>
                        Error al editar: La fecha de fin no puede ser anterior a la fecha de inicio.
                    </div>";
            }else{
                // Obtener los datos del formulario
                $idTarea = $_POST['idTarea'];
                $titulo = $_POST['titulo'];
                $descripcion = $_POST['descripcion'];
                $fechaInicio = $_POST['fecha_inicio'];
                $fechaFin = $_POST['fecha_fin'];
                $lugar = $_POST['lugar'];
                $tag = $_POST['tag'];
                $estado = $_POST['estado'];

                $sql = "UPDATE tarea SET 
                            titulo = '$titulo',
                            descripcion = '$descripcion',
                            fechaInicio = '$fechaInicio',
                            fechaFin = '$fechaFin',
                            lugar = '$lugar',
                            tag = '$tag',
                            estado = '$estado'
                        WHERE idTarea = $idTarea";

                if ($conexion->query($sql)) {
                    $mensajeModificacion = "<div class='alert alert-success mt-4 mb-4'>Tarea actualizada correctamente</div>";
                } else {
                    $mensajeModificacion = "<div class='alert alert-danger mt-4 mb-4'>Error al actualizar la tarea</div>";
                }
            }
        } else {
            $mensajeModificacion = "<div class='alert alert-warning mt-4 mb-4'>ERROR</div>";
        }// Fin de comprobación de campos vacíos
}

// Si no hay cookie de sesión o el tiempo de la cookie ha expirado, se redirige a index.php
if (!isset($_COOKIE['sesion'])) {
    // Eliminar la cookie de PHPSESSID si existe
    if (isset($_COOKIE['PHPSESSID'])) {
        setcookie('PHPSESSID', '', time() - 3600, "/");
    }
    // Redirigir a index.php con un código de redirección 303 (See Other) para evitar el reenvío del formulario al pulsar el botón de retroceso en el navegador
    header("Location: index.php", true, 303);
    exit();
} 

// Si se ha enviado el formulario de cierre de sesión se elimina cookie de sesión y se redirige a index.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {

    // Eliminar la cookie de PHPSESSID si existe
    if (isset($_COOKIE['PHPSESSID'])) {
        setcookie('PHPSESSID', '', time() - 3600, "/");
    }
    // Eliminar la cookie de sesión
    setcookie('sesion', '', time() - 3600, "/");
    // Borrar la cookie de sesión de PHP
    unset($_COOKIE['sesion']);
    // Redirigir a index.php con un código de redirección 303 (See Other) para evitar el reenvío del formulario al pulsar el botón de retroceso en el navegador
    header("Location: index.php", true, 303);
    exit();

}

// Si la cookie de sesión sigue activa, se muestra la página
if (isset($_COOKIE['sesion'])){
        ?>

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
            <header>
                <?php
                // Se incluye el archivo de la cabecera
                include_once 'conexion.php';
                include_once 'navbar.php';
                // Si se ha pulsado en eliminar tarea se elimina la tarea seleccionada antes de cargar la tabla de tareas
                if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['eliminar']) && !empty($_POST['eliminar'])) {
                    $idTarea = intval($_POST['eliminar']); // Asegurar que sea un número entero
                
                    // Consulta SQL corregida
                    $sql = "DELETE FROM tarea WHERE idTarea = $idTarea";
                    $resultado = $conexion->query($sql);
                
                    if ($resultado) {
                        $mensajeEliminar = "<div class='alert alert-success mt-4 mb-4'>Se ha eliminado la tarea</div>";
                    } else {
                        $mensajeEliminar = "<div class='alert alert-danger mt-4 mb-4'>No se ha podido eliminar la tarea</div>";
                    }
                }                
                ?>
            </header>
            <main>
                <?php
                // Si se ha pulsado en añadir tarea se muestra un formulario con los campos descripcion, fechaInicio, fechaFin, lugar, tag, estado, comentarios.
                if (isset($_POST['add'])) {

                    ?>
                    <div class="container-add-tarea">
                    <h1 class="text-center mt-5">Añadir tarea</h1>
                    <form action="tareas.php" method="POST" class="container p-4 bg-light rounded shadow-sm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="titulo" class="form-label">Título</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" required>
                            </div>
                            <div class="col-md-6">
                                <label for="lugar" class="form-label">Lugar</label>
                                <input type="text" class="form-control" id="lugar" name="lugar" required>
                            </div>
                            <div class="col-md-12">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="2" required></textarea>
                            </div>
                            <div class="col-md-6 fechas">
                                <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                            </div>
                            <div class="col-md-6">
                                <label for="fecha_fin" class="form-label">Fecha de fin</label>
                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label d-block">Elige una categoría</label>
                                <div class="btn-group" role="group">
                                    <?php
                                    // Obtener los tags y sus colores RGB de la base de datos
                                    $sqlTags = "SELECT tag, colorRGB FROM tags";
                                    $resultadoTags = $conexion->query($sqlTags);
                                    if ($resultadoTags->num_rows == 0) {
                                        echo "<div class='alert alert-warning text-center mt-3' role='alert'>
                                                No hay tags
                                            </div>";
                                    }else{
                                        // Mostrar los tags en forma de radio buttons con el color de fondo correspondiente a cada tag desde la BD
                                        while ($tag = $resultadoTags->fetch_assoc()) {
                                            $tagNombre = $tag['tag'];
                                            $colorTag = $tag['colorRGB'];
                                            echo "<input type='radio' id='tag_$tagNombre' name='tag' value='$tagNombre' required hidden>
                                                <label for='tag_$tagNombre' class='badge badge-tag' style='background-color: $colorTag; color: #ffffff;'>$tagNombre</label>
                                            ";
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label d-block">Estado</label>
                                    <div class="btn-group" role="group">
                                        <input type="radio" id="estado_pendiente" name="estado" value="Pendiente" required hidden>
                                        <label for="estado_pendiente" class="badge badge-estado pendiente">Pendiente</label>

                                        <input type="radio" id="estado_en_progreso" name="estado" value="En progreso" required hidden>
                                        <label for="estado_en_progreso" class="badge badge-estado en-progreso">En Proceso</label>

                                        <input type="radio" id="estado_finalizada" name="estado" value="Finalizada" required hidden>
                                        <label for="estado_finalizada" class="badge badge-estado finalizada">Finalizada</label>
                                    </div>
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-dark btn-sm mt-3" name="add">Añadir tarea</button>
                            </div>
                        </div>
                    </form>

                    <?php
                    // Si se ha enviado el formulario de añadir tarea
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titulo']) && isset($_POST['descripcion']) && isset($_POST['fecha_inicio']) && isset($_POST['fecha_fin']) && isset($_POST['lugar']) && isset($_POST['tag']) && isset($_POST['estado'])) {
                        // Se obtienen los datos del formulario
                        $titulo = $_POST['titulo'];
                        $descripcion = $_POST['descripcion'];
                        $fechaInicio = $_POST['fecha_inicio'];
                        $fechaFin = $_POST['fecha_fin'];
                        $lugar = $_POST['lugar'];
                        $tag = $_POST['tag'];
                        $estado = $_POST['estado'];
                        // Se obtiene el correo del usuario almacenado en la cookie de sesión
                        $correo=$_COOKIE['sesion'];

                        // Se comprueba que la fecha de fin no sea anterior a la fecha de inicio    
                        if (strtotime($fechaFin) < strtotime($fechaInicio)) {
                            echo "<div class='alert alert-danger text-center mt-3' role='alert'>
                                    Error: La fecha de fin no puede ser anterior a la fecha de inicio.
                                </div>";
                        } else {
                            // Se insertan los datos en la tabla tarea
                            $sql = "INSERT INTO tarea (titulo, descripcion, fechaInicio, fechaFin, lugar, tag, estado, email) 
                                        VALUES ('$titulo', '$descripcion', '$fechaInicio', '$fechaFin', '$lugar', '$tag', '$estado', '$correo')";

                            $resultado = $conexion->query($sql);
                            // Se muestra un mensaje de éxito o error
                            if ($resultado) {
                                ?>
                                <div class='alert alert-success aling-text-center mt-3' role='alert'>
                                    Tarea añadida correctamente
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class='alert alert-danger aling-text-center mt-3' role='alert'>
                                    Error al añadir la tarea
                                </div>
                                <?php
                            }
                        }
                    }
                } else {
                    // Si no se ha pulsado en añadir tarea o se ha enviado el formulario de añadir tarea, se muestra un listado de tareas
                    ?>
                    <div class="container-ver-tareas">
                    <div class="row">
                        <div class="izquierda col-md-2 d-flex flex-column">
                            <!-- Columna izquierda vacía para mantener el diseño -->
                        </div>                            
                        <div class ="derecha col-md-9">
                            <?php
//-- SALUDO A USUARIO  ------------------------------------------------------------------- 
                            // Mostrar nombre del usuario con correo almacenado en la cookie de sesión
                            $correo = $_COOKIE['sesion'];
                            $sql = "SELECT nombreUsuario FROM usuarios WHERE email = '$correo'";
                            $resultado = $conexion->query($sql);
                            $usuario = $resultado->fetch_assoc();
                            if ($resultado->num_rows == 0) {
                                echo "<div class='alert alert-warning text-center mt-3' role='alert'>
                                        No se ha encontrado el usuario
                                    </div>";
                            }else{
                                $nombreUsuario = $usuario['nombreUsuario'];
                            }
                            ?>
                            <h1 class="align-text-left mt-5">¡Hey, <?php echo $nombreUsuario ?>!</h1>
                            <p class="align-text-left descripcion">Aquí tienes tus tareas</p>
                            <?php
                            echo $mensajeErrorFecha;
                            echo $mensajeModificacion;
                            echo $mensajeEliminar;
                            ?>
                        </div>
                    </div>
                        <div class="row">
<!-- FILTRO DE TAREAS ------------------------------------------------------------------- -->
                            <!-- Columna izquierda para el filtro -->
                            <div class="izquierda col-md-3 d-flex flex-column">
                            <form action="tareas.php" method="POST" class="container-filtro">
                                <div class="row g-3 ">
                                    <div class="col-md-12 ">
                                        <label class="form-label d-block">Estados</label>
                                        <div class="btn-group" role="group">
                                            <input type="radio" id="estado_pendiente" name="filtro_estado" value="Pendiente" hidden>
                                            <label for="estado_pendiente" class="badge badge-estado filtro pendiente">Pendiente</label>

                                            <input type="radio" id="estado_en_progreso" name="filtro_estado" value="En progreso" hidden>
                                            <label for="estado_en_progreso" class="badge badge-estado filtro en-progreso">En Progreso</label>

                                            <input type="radio" id="estado_finalizada" name="filtro_estado" value="Finalizada" hidden>
                                            <label for="estado_finalizada" class="badge badge-estado filtro finalizada">Finalizada</label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <label class="form-label d-block mb-2">Tags</label>
                                        <div class="btn-group" role="group">
                                        <?php
                                        // Obtener los tags de la base de datos
                                        $sqlTagsFiltro = "SELECT tag, colorRGB FROM tags";
                                        $resultadoTagsFiltro = $conexion->query($sqlTagsFiltro);
                                        
                                        if ($resultadoTagsFiltro->num_rows > 0) {
                                            while ($tag = $resultadoTagsFiltro->fetch_assoc()) {
                                                $tagNombre = $tag['tag'];
                                                $colorTag = $tag['colorRGB'];
                                                echo "<input type='radio' id='tag_$tagNombre' name='filtro_tag' value='$tagNombre' hidden>
                                                        <label for='tag_$tagNombre' class='badge badge-tag filtro' style='--tag-color: $colorTag; color: #ffffff;'>$tagNombre</label>";
                                            }
                                        } else {
                                            echo "<p>No hay tags disponibles</p>";
                                        }
                                        ?>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <label for="fechaInicio" class="form-label ">Desde</label>
                                        <input type="date" class="form-control" id="fechaInicio" name="fechaInicio">
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <label for="fechaFin" class="form-label ">Hasta</label>
                                        <input type="date" class="form-control" id="fechaFin" name="fechaFin">
                                    </div>
                                    
                                    <div class="col-12 text-start">
                                        <button type="submit" class="btn btn-dark btn-sm mt-3" name="filtrar">Filtrar</button>
                                        <button type="submit" class="btn btn-dark btn-sm mt-3" name="limpiar">Limpiar filtro</button>
                                    </div>
                                </div>
                            </form>

                            </div>
<!-- TAREAS DEL USUARIO ------------------------------------------------------------------- -->
                            <!-- Columna derecha para mostrar las tareas -->
                            <div class="derecha col-md-10">
                                
                                <?php
                                // Se obtiene el correo del usuario almacenado en la cookie de sesión y se hace una consulta a la base de datos para obtener las tareas del usuario
                                $correo = $_COOKIE['sesion'];
                                $sql = "SELECT tarea.idTarea, tarea.titulo, tarea.descripcion, tarea.fechaInicio, tarea.fechaFin, 
                                                tarea.lugar, tarea.estado, tarea.tag, tags.colorRGB 
                                                FROM tarea 
                                                LEFT JOIN tags ON tarea.tag = tags.tag
                                                WHERE tarea.email = '$correo' 
                                                ORDER BY tarea.fechaInicio ASC";

                                $resultado = $conexion->query($sql);
                                // Si se ha enviado el formulario de filtro
                                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filtrar'])) {
                                        // Se obtienen los datos del formulario y si no se ha seleccionado un filtro,
                                        // se asigna un valor por defecto vacío para que no se aplique el filtro
                                        $filtro_estado = $_POST['filtro_estado'] ?? "";
                                        $filtro_tag = $_POST['filtro_tag'] ?? "";
                                        $fechaInicio = $_POST['fechaInicio'] ?? "";
                                        $fechaFin = $_POST['fechaFin'] ?? "";
                                    
                                        $sql = "SELECT tarea.idTarea, tarea.titulo, tarea.descripcion, tarea.fechaInicio, tarea.fechaFin, 
                                                    tarea.lugar, tarea.estado, tarea.tag, tags.colorRGB 
                                                FROM tarea 
                                                LEFT JOIN tags ON tarea.tag = tags.tag
                                                WHERE tarea.email = '$correo'";

                                        // concatenar la condición al WHERE si se ha seleccionado un filtro
                                        if ($filtro_estado != "") {
                                            $sql .= " AND tarea.estado = '$filtro_estado'";
                                        }
                                        if ($filtro_tag != "") {
                                            $sql .= " AND tarea.tag = '$filtro_tag'";
                                        }
                                        if ($fechaInicio != "") {
                                            $sql .= " AND tarea.fechaInicio >= '$fechaInicio'";
                                        }
                                        if ($fechaFin != "") {
                                            $sql .= " AND tarea.fechaFin <= '$fechaFin'";
                                        }
                                        

                                        $resultado = $conexion->query($sql);                                        
                                }

                                if ($resultado->num_rows > 0) {
                                    ?>
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">Tarea</th>
                                                <th scope="col">Descripción</th>
                                                <th scope="col">Tag</th>
                                                <th scope="col">Fecha de inicio</th>
                                                <th scope="col">Fecha de fin</th>
                                                <th scope="col">Lugar</th>
                                                <th scope="col">Estado</th>
                                                <th scope="col" class="text-center">Editar</th>
                                                <th scope="col" class="text-center">Eliminar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        while ($tarea = $resultado->fetch_assoc()) {
                                            $titulo = $tarea['titulo'];
                                            $descripcion = $tarea['descripcion'];
                                            $fechaInicio = date("d-m-Y", strtotime($tarea['fechaInicio']));
                                            $fechaFin = date("d-m-Y", strtotime($tarea['fechaFin']));
                                            $lugar = $tarea['lugar'];
                                            $tag = $tarea['tag'];
                                            $estado = $tarea['estado'];
                                            $tag = $tarea['tag'];
                                            $colorTag = $tarea['colorRGB']; // Color desde la BD

                                            // Asignar colores a los estados
                                            $estadoColor = [
                                                'Pendiente' => "<span class='badge pendiente'>Pendiente</span>",
                                                'En progreso' => "<span class='badge en-progreso'>En progreso</span>",
                                                'Finalizada' => "<span class='badge finalizada'>Finalizada</span>"
                                            ];
                                            $estado = $estadoColor[$estado];

                                            echo "<tr>
                                                    <td>$titulo</td>
                                                    <td class='td-descripcion'>$descripcion</td>
                                                    <td><span class='badge' style='background-color: $colorTag; color: #ffffff;'>$tag</span></td>                                                   
                                                    <td>$fechaInicio</td>
                                                    <td>$fechaFin</td>
                                                    <td>$lugar</td>
                                                    <td>$estado</td>"?>
<!-- BOTONES EDITAR Y ELIMINAR TAREA ------------------------------------------------------------ -->                                                    
                                                    <td class="text-center">
                                                        <form method="POST">
                                                            <input type="hidden" name="editar_id" value="<?php echo $tarea['idTarea']; ?>">
                                                            <button type="submit" name="abrir_modal" class="btn btn-link p-0 border-0">
                                                                <img src="img/editar.png" alt="Editar" width="15" height="15">
                                                            </button>
                                                        </form>
                                                    </td>


                                                    <td class="text-center">
                                                    <form class="button-eliminar" method="POST">
                                                        <input type="hidden" name="eliminar" value="<?php echo $tarea['idTarea']; ?>">
                                                        <button type="submit" class="btn btn-link p-0 border-0">
                                                            <img src="img/eliminar.png" alt="Eliminar" width="15" height="15">
                                                        </button>
                                                    </form>
                                                    </td>
                                                    <?php
                                                    echo "</tr>";
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                    <?php
                                
                                } else {
                                    ?>
                                    <div class='alert alert-warning text-center mt-3' role='alert'>
                                        No hay tareas
                                    </div>
                                    <div class="text-center mt-3">
                                        <h3>¡Comienza ya a añadir tareas en Notium!</h3>
                                        <form action="tareas.php" method="post">
                                            <button type="submit" class="btn btn-dark btn-sm mt-3" name="add">Añadir tarea</button>
                                        </form>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div> <!-- Fin de la columna derecha -->
                        </div> <!-- Fin del row -->
                    </div> <!-- Fin del contenedor -->
            
                <?php
                }
                ?>
            </main>
            <?php
        include_once 'footer.php';
        ?>

<!-- MODAL PARA EDITAR TAREA ------------------------------------------------------------ -->
            <!-- Primero se comprueba si se ha pulsado en editar y se obtienen los datos de la tarea a editar -->
            <?php
            // Si se ha pulsado en editar, obtener los datos de la tarea a editar
            if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['abrir_modal']) && !empty($_POST['editar_id'])) {
                $idTareaEditar = ($_POST['editar_id']);
                $sql = "SELECT * FROM tarea WHERE idTarea = $idTareaEditar";
                $resultado = $conexion->query($sql);

                if ($resultado->num_rows > 0) {
                    // Se utilizan variables auxiliares para almacenar los datos de la tarea a editar
                    $tarea = $resultado->fetch_assoc();
                    $tituloModal = ($tarea['titulo']);
                    $lugarModal = ($tarea['lugar']);
                    $descripcionModal = ($tarea['descripcion']);
                    $fechaInicioModal = $tarea['fechaInicio'];
                    $fechaFinModal = $tarea['fechaFin'];
                    $tagModal = ($tarea['tag']);
                    $estadoModal = ($tarea['estado']);
                }
            }
            ?>
        
            <?php 
            // Se muestra el modal con los datos de la tarea a editar
            if (isset($_POST['abrir_modal'])){?>
            <div class="modal show" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel" aria-hidden="false" style="display: block;">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="modalEditarLabel">Editar tarea</h3>
                        </div>
                        <div class="modal-body">
                        <form action="tareas.php" method="POST" class="container p-4">
                            <input type="hidden" name="idTarea" value="<?php echo $idTareaEditar; ?>">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="titulo" class="form-label">Título</label>
                                    <input type="text" class="form-control" name="titulo" value="<?php echo $tituloModal; ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="lugar" class="form-label">Lugar</label>
                                    <input type="text" class="form-control" name="lugar" value="<?php echo $lugarModal; ?>" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="descripcion" class="form-label">Descripción</label>
                                    <textarea class="form-control" name="descripcion" rows="2" required><?php echo $descripcionModal; ?></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
                                    <input type="date" class="form-control" name="fecha_inicio" value="<?php echo date('Y-m-d', strtotime($fechaInicioModal)); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="fecha_fin" class="form-label">Fecha de fin</label>
                                    <input type="date" class="form-control" name="fecha_fin" value="<?php echo date('Y-m-d', strtotime($fechaFinModal)); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label d-block mb-2">Tags</label>
                                    <div class="btn-group" role="group">
                                        <?php
                                        // Obtener los tags de la base de datos
                                        $sqlTagsFiltro = "SELECT tag, colorRGB FROM tags";
                                        $resultadoTagsFiltro = $conexion->query($sqlTagsFiltro);
                                        
                                        if ($resultadoTagsFiltro->num_rows > 0) {
                                            while ($tag = $resultadoTagsFiltro->fetch_assoc()) {
                                                $tagNombre = $tag['tag'];
                                                $colorTag = $tag['colorRGB'];
                                                $checked = ($tagNombre == $tagModal) ? "checked" : "";
                                                echo "<input type='radio' id='modal_tag_$tagNombre' name='tag' value='$tagNombre' $checked hidden>
                                                        <label for='modal_tag_$tagNombre' class='badge badge-tag filtro' style='background-color: $colorTag; color: #ffffff;'>$tagNombre</label>";
                                            }
                                        } else {
                                            echo "<p>No hay tags disponibles</p>";
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label d-block">Estados</label>
                                    <div class="btn-group" role="group">
                                        <input type="radio" id="modal_estado_pendiente" name="estado" value="Pendiente" <?php echo ($estadoModal == "Pendiente") ? "checked" : ""; ?> hidden>
                                        <label for="modal_estado_pendiente" class="badge badge-estado filtro pendiente">Pendiente</label>

                                        <input type="radio" id="modal_estado_en_progreso" name="estado" value="En progreso" <?php echo ($estadoModal == "En progreso") ? "checked" : ""; ?> hidden>
                                        <label for="modal_estado_en_progreso" class="badge badge-estado filtro en-progreso">En Progreso</label>

                                        <input type="radio" id="modal_estado_finalizada" name="estado" value="Finalizada" <?php echo ($estadoModal == "Finalizada") ? "checked" : ""; ?> hidden>
                                        <label for="modal_estado_finalizada" class="badge badge-estado filtro finalizada">Finalizada</label>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-dark" name="cerrar_modal">Cancelar</button>
                                <button type="submit" class="btn btn-dark" name="modificar">Modificar</button>
                            </div>
                        </form>

                        </div>
                    </div>
                </div>
            </div>
            <?php } 
}else{
    $conexion->close();
    // Si no hay cookie de sesión, se redirige a index.php
    header("Location: index.php", true, 303);
    exit();
}
    include_once 'footer.php';
    $conexion->close();
    ?>
    </body>
</html>

