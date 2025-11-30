<h1 align="center">Notium</h1>

<p align="center">Aplicación web de gestión de tareas desarrollada para la asignatura de <br><strong>Programación</strong> del <strong>Grado Superior en Desarrollo de Aplicaciones Multiplataforma</strong>. <br>Permite <strong>registrar usuarios, iniciar sesión y gestionar tareas</strong> mediante un sistema CRUD completo:<br>creación, edición (con modal), filtrado y eliminación.
</p>
<p align="center">
  <img src="./screenshoots/inicio.png" width="650">
</p>

## Tecnologías utilizadas

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.0+-777BB4?logo=php&logoColor=white" />
  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?logo=mysql&logoColor=white" />
  <img src="https://img.shields.io/badge/Bootstrap-4.5-7952B3?logo=bootstrap&logoColor=white" />
  <img src="https://img.shields.io/badge/HTML5-Frontend-E34F26?logo=html5&logoColor=white" />
  <img src="https://img.shields.io/badge/CSS3-Styling-1572B6?logo=css3&logoColor=white" />
  <img src="https://img.shields.io/badge/XAMPP-Localhost-FB7A24?logo=xampp&logoColor=white" />
  <img src="https://img.shields.io/badge/Apache-Server-D22128?logo=apache&logoColor=white" />
</p>

## Funcionalidades principales

### Autenticación

El sistema permite registrar usuarios y acceder mediante sesión persistente con cookies.

<p align="center">
  <img src="./screenshoots/registro.png" width="650">
</p>

<p align="center">
  <img src="./screenshoots/login.png" width="650">
</p>

Características:

* Registro con contraseña encriptada.
* Inicio de sesión mediante cookie.
* Redirecciones según sesión activa.
* Cierre de sesión con borrado de cookies.

---

### Gestión de tareas

Pantalla principal del usuario con las tareas listadas dinámicamente.

<p align="center">
  <img src="./screenshoots/tareas_limpio.png" width="650">
</p>

<p align="center">
  <img src="./screenshoots/tareas.png" width="650">
</p>

Permite:

* Crear nuevas tareas con:

  * Título, descripción, fechas, lugar
  * Tag (color asociado)
  * Estado (Pendiente / En progreso / Finalizada)
* Listado con:

  * Tags coloreados desde BD
  * Badges por estado
  * Fechas DD-MM-YYYY
* Edición mediante modal de Bootstrap
* Eliminación de tareas

#### Añadir tarea

<p align="center">
  <img src="./screenshoots/add.png" width="650">
</p>

#### Editar tarea

<p align="center">
  <img src="./screenshoots/editar.png" width="650">
</p>

---

### Filtro avanzado

Incluye filtrado por:

* Estado
* Tag
* Rango de fechas

Además incorpora un botón de **limpiar filtros** para restaurar la vista general.

(No incluyes captura específica de filtros; si quieres, la añadimos.)

---

## Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/maramartinezvargas/Notium
```

### 2. Configurar la base de datos

Importar `notium.sql` (incluido en el repositorio).

Crear la base de datos:

```sql
CREATE DATABASE notium;
```

Importar desde consola:

```bash
mysql -u root -p notium < notium.sql
```

Incluye:

* Tabla `usuarios`
* Tabla `tags`
* Tabla `tarea`
* Tags por defecto y datos de prueba

### 3. Configurar la conexión

Editar `conexion.php` si es necesario:

```php
$conexion = new mysqli("127.0.0.1", "USER", "PASS", "DATABASE_NAME");
```

### 4. Ejecutar el proyecto

Colocar el repositorio en `htdocs` y acceder a:

```
http://localhost/notium/
```

---

## Estructura del proyecto

```
/
├── index.php            # Registro y login
├── tareas.php           # CRUD completo de tareas
├── conexion.php         # Conexión MySQL
├── navbar.php           # Barra de navegación
├── footer.php           # Footer fijo
├── politicas.php        # Políticas de privacidad
├── styles.css           # Estilos personalizados
└── notium.sql           # Base de datos completa
```

---

## Seguridad (nivel académico)

Incluye:

* Contraseñas hasheadas (`password_hash`)
* Validación básica
* Gestión de sesión mediante cookies
* Restricción de acceso a páginas internas
