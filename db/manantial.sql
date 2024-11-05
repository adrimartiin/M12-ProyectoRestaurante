-- Crear base de datos
CREATE DATABASE IF NOT EXISTS elmanantial;
USE elmanantial;

-- Tabla roles
CREATE TABLE rol (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre_rol VARCHAR(50) NOT NULL
);

-- Tabla empleados
CREATE TABLE empleado (
    id_empleado INT AUTO_INCREMENT PRIMARY KEY,
    codigo_empleado CHAR(4) NOT NULL UNIQUE,
    nombre VARCHAR(50) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    pwd VARCHAR(255) NOT NULL,
    id_rol INT,
    FOREIGN KEY (id_rol) REFERENCES rol(id_rol)
);

-- Tabla salas
CREATE TABLE sala (
    id_sala INT AUTO_INCREMENT PRIMARY KEY,
    nombre_sala VARCHAR(50) NOT NULL,
    ubicacion VARCHAR(50) NOT NULL,
    capacidad INT NOT NULL
);

-- Tabla taules
CREATE TABLE taula (
    id_taula INT AUTO_INCREMENT PRIMARY KEY,
    id_sala INT,
    numero_taula INT NOT NULL,
    capacitat INT NOT NULL,
    estat VARCHAR(10) DEFAULT 'lliure',
    FOREIGN KEY (id_sala) REFERENCES sala(id_sala)
);

-- Tabla ocupacions
CREATE TABLE ocupacio (
    id_ocupacio INT AUTO_INCREMENT PRIMARY KEY,
    id_empleado INT,
    id_taula INT,
    hora_inici DATETIME NOT NULL,
    hora_final DATETIME,
    FOREIGN KEY (id_empleado) REFERENCES empleado(id_empleado),
    FOREIGN KEY (id_taula) REFERENCES taula(id_taula)
);

-- Insertar roles
INSERT INTO rol (nombre_rol) VALUES
('Cambrer'),
('Administrador');

-- Insertar empleados con la contraseña encriptada "qweQWE123"
INSERT INTO empleado (codigo_empleado, nombre, apellidos, pwd, id_rol) VALUES
('5512', 'Martin', 'Calvet', '$2y$10$5kdQvD9/nJgYHoG5USv8Iu5RvmQOz5n2f4gLT7Yf8Z8m5MxglxAva', 1), -- qweQWE123 encriptado
('7746', 'Christian', 'Monrabal Donis', '$2y$10$5kdQvD9/nJgYHoG5USv8Iu5RvmQOz5n2f4gLT7Yf8Z8m5MxglxAva', 2), -- qweQWE123 encriptado
('1594', 'Alejandro', 'González Fernández', '$2y$10$5kdQvD9/nJgYHoG5USv8Iu5RvmQOz5n2f4gLT7Yf8Z8m5MxglxAva', 1), -- qweQWE123 encriptado
('9073', 'Oriol', 'Godoy Morote', '$2y$10$5kdQvD9/nJgYHoG5USv8Iu5RvmQOz5n2f4gLT7Yf8Z8m5MxglxAva', 1); -- qweQWE123 encriptado

-- Insertar salas
INSERT INTO sala (nombre_sala, ubicacion, capacidad) VALUES
('Terrassa 1', 'Exterior', 20),
('Terrassa 2', 'Exterior', 15),
('Terrassa 3', 'Exterior', 10),
('Menjador Principal', 'Interior', 30),
('Menjador Secundari', 'Interior', 20),
('Sala Privada 1', 'Interior', 8),
('Sala Privada 2', 'Interior', 8),
('Sala Privada 3', 'Interior', 6),
('Sala Privada 4', 'Interior', 6);

-- Insertar taules (ejemplo de asignación de mesas a salas)
INSERT INTO taula (id_sala, numero_taula, capacitat) VALUES
(1, 1, 4),
(1, 2, 4),
(2, 1, 4),
(3, 1, 2),
(4, 1, 6),
(5, 1, 4),
(6, 1, 8),
(7, 1, 8),
(8, 1, 6),
(9, 1, 6);

-- Insertar ocupacions de ejemplo
INSERT INTO ocupacio (id_empleado, id_taula, hora_inici, hora_final) VALUES
(1, 1, '2024-11-01 12:00:00', '2024-11-01 14:00:00'),
(2, 3, '2024-11-01 13:00:00', NULL),
(3, 5, '2024-11-01 13:30:00', NULL),
(4, 2, '2024-11-01 14:00:00', '2024-11-01 16:00:00');
