DROP DATABASE IF EXISTS notium;
CREATE DATABASE notium;

USE notium;

-- Tabla de usuarios
CREATE TABLE usuarios (
	email VARCHAR(150) UNIQUE PRIMARY KEY,
    nombreUsuario VARCHAR(100),
    pass VARCHAR(255));

-- Tabla de tags
CREATE TABLE tags (
    tag VARCHAR(100) UNIQUE PRIMARY KEY,
    colorRGB VARCHAR(100));

-- Tabla de tareas
CREATE TABLE tarea (
    idTarea INT UNIQUE PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(100),
    descripcion TEXT,
    fechaInicio DATETIME,
    fechaFin DATETIME,
    lugar VARCHAR(150),
    estado VARCHAR(50),
    email VARCHAR(150),
    tag VARCHAR(100),
    CONSTRAINT fk_tarea_usuario FOREIGN KEY (email) REFERENCES usuarios(email),
    CONSTRAINT fk_tarea_tag FOREIGN KEY (tag) REFERENCES tags(tag),
    CONSTRAINT chk_estado CHECK (estado IN ('Pendiente', 'En progreso', 'Finalizada')));

-- ¡Datos necesarios! Deben ser insertados para el funcionamiento de la aplicación.
INSERT INTO tags (tag, colorRGB) VALUES
('Trabajo', '#779b99'),
('Personal', '#c1b4cf'),
('Urgente', '#d3ab87'),
('Ocio', '#9bbda4'),
('Formación','#b2d7e2');


-- DATOS DE PRUEBA para la cuenta victor@gmail.com con contraseña campusfp2025
INSERT INTO usuarios (email, nombreUsuario, pass) VALUES
	('victor@gmail.com','Victor Colomo','$2y$10$9uy1VP3kV30T9bamO6I.TueDlunuPBtH7/QltdRbBqJ8r6s5LnqYu');
    
INSERT INTO tarea (titulo, descripcion, fechaInicio, fechaFin, lugar, estado, email, tag) VALUES
('Revisar proyectos finales de los alumnos', 'Corregir entregas de proyectos en Python y enviar feedback.', '2025-03-10', '2025-03-15', 'CampusFP Alcalá', 'Pendiente', 'victor@gmail.com', 'Formación'),
('Preparar material para la clase de POO en Java', 'Crear ejemplos prácticos sobre herencia y polimorfismo.', '2025-02-18', '2025-02-19', 'Casa', 'En progreso', 'victor@gmail.com', 'Formación'),
('Actualizar ejercicios de SQL para Bases de Datos', 'Incluir prácticas sobre procedimientos almacenados y triggers.', '2025-02-12', '2025-02-14', 'Remoto', 'Pendiente', 'victor@gmail.com', 'Formación'),
('Organizar taller sobre Git y GitHub', 'Preparar presentación y ejercicios sobre control de versiones.', '2025-02-05', '2025-02-07', 'CampusFP Alcalá', 'Pendiente', 'victor@gmail.com', 'Formación'),
('Redactar exámenes del segundo trimestre', 'Diseñar preguntas y ejercicios prácticos para evaluación.', '2025-01-28', '2025-01-30', 'Casa', 'En progreso', 'victor@gmail.com', 'Formación'),
('Reunión con el equipo docente', 'Planificar próximos módulos y actividades interactivas.', '2025-01-22', '2025-01-22', 'CampusFP Alcalá', 'Finalizada', 'victor@gmail.com', 'Trabajo'),
('Actualizar documentación de prácticas en Classroom', 'Subir materiales revisados y añadir enlaces útiles.', '2025-01-15', '2025-01-16', 'Remoto', 'Finalizada', 'victor@gmail.com', 'Trabajo'),
('Asistir a congreso sobre enseñanza de la programación', 'Ponencias sobre nuevas metodologías y herramientas didácticas.', '2025-03-01', '2025-03-02', 'Madrid', 'Pendiente', 'victor@gmail.com', 'Formación'),
('Organizar jornada de puertas abiertas', 'Presentar el programa de estudios y resolver dudas de futuros alumnos.', '2025-02-20', '2025-02-20', 'CampusFP Alcalá', 'Pendiente', 'victor@gmail.com', 'Trabajo'),
('Mentoría con alumnos de último curso', 'Revisar proyectos individuales y orientar sobre salidas profesionales.', '2025-02-15', '2025-02-15', 'CampusFP Alcalá', 'Pendiente', 'victor@gmail.com', 'Trabajo');
