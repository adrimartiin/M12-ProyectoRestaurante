CREATE DATABASE db_restaurante;

USE db_restaurante;

-- Crear tablas
CREATE TABLE tbl_cliente (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    num_personas INT NOT NULL
);

CREATE TABLE tbl_sala (
    id_sala INT PRIMARY KEY,
    nombre_sala VARCHAR(25) NOT NULL,
    tipo_sala ENUM('terraza', 'comedor', 'privada') NOT NULL,
    capacidad_total INT NOT NULL
);

CREATE TABLE tbl_mesa (
    id_mesa INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    id_sala INT NOT NULL,
    num_sillas_mesa INT NOT NULL,
    estado_mesa ENUM('libre', 'ocupada') NOT NULL DEFAULT 'libre',
    FOREIGN KEY (id_sala) REFERENCES tbl_sala(id_sala)
);

CREATE TABLE tbl_camarero (
    id_camarero INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    nombre_camarero VARCHAR(30) NOT NULL,
    codigo_camarero CHAR(4) NOT NULL UNIQUE,
    password_camarero VARCHAR(255) NOT NULL
);

CREATE TABLE tbl_ocupacion (
    id_ocupacion INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    id_mesa INT NOT NULL,
    id_camarero INT NOT NULL,
    id_cliente INT NOT NULL,
    fecha_hora_ocupacion DATETIME NOT NULL,
    fecha_hora_desocupacion DATETIME,
    FOREIGN KEY (id_mesa) REFERENCES tbl_mesa(id_mesa),
    FOREIGN KEY (id_camarero) REFERENCES tbl_camarero(id_camarero),
    FOREIGN KEY (id_cliente) REFERENCES tbl_cliente(id_cliente)
);

-- Crear salas (terrazas, comedores, salas privadas)
INSERT INTO tbl_sala (id_sala, nombre_sala, tipo_sala, capacidad_total) VALUES
(1, 'Terraza Principal', 'terraza', 50),
(2, 'Terraza Secundaria', 'terraza', 60),
(3, 'Terraza Externa', 'terraza', 70),
(4, 'Comedor Interior', 'comedor', 40),
(5, 'Comedor Pequeño', 'comedor', 30),
(6, 'Sala Privada 1', 'privada', 10),
(7, 'Sala Privada 2', 'privada', 10),
(8, 'Sala Privada 3', 'privada', 10),
(9, 'Sala Privada 4', 'privada', 10);

-- Insertar mesas en las salas con sillas entre 2 y 6
INSERT INTO tbl_mesa (id_sala, num_sillas_mesa, estado_mesa) VALUES
-- Terraza 1 (4 mesas)
(1, 4, 'libre'),
(1, 4, 'libre'),
(1, 4, 'libre'),
(1, 4, 'libre'),
-- Terraza 2 (4 mesas)
(2, 4, 'libre'),
(2, 4, 'libre'),
(2, 4, 'libre'),
(2, 4, 'libre'),
-- Terraza 3 (4 mesas)
(3, 4, 'libre'),
(3, 4, 'libre'),
(3, 4, 'libre'),
(3, 4, 'libre'),
-- Comedor 1 (7 mesas)
(4, 4, 'libre'),
(4, 5, 'libre'),
(4, 6, 'libre'),
(4, 4, 'libre'),
(4, 3, 'libre'),
(4, 5, 'libre'),
(4, 6, 'libre'),
-- Comedor 2 (6 mesas)
(5, 4, 'libre'),
(5, 5, 'libre'),
(5, 3, 'libre'),
(5, 6, 'libre'),
(5, 4, 'libre'),
(5, 5, 'libre'),
-- Sala Privada 1 (1 mesa de 10 sillas)
(6, 10, 'libre'),
-- Sala Privada 2 (1 mesa de 10 sillas)
(7, 10, 'libre'),
-- Sala Privada 3 (1 mesa de 10 sillas)
(8, 10, 'libre'),
-- Sala Privada 4 (1 mesa de 10 sillas)
(9, 10, 'libre');

-- Insertar camareros
INSERT INTO tbl_camarero (nombre_camarero, codigo_camarero, password_camarero) VALUES
('Christian Monrabal', 'C001', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'), -- qweQWE123
('Adrian Martin', 'C002', '$2a$12$DB3.O4aga98EH./zW9P9beKfklJkTcXMY0AnL3T6nheQhpM3usreO'), -- asdASD456
('Alejandro González ', 'C003', '$2a$12$b509yhiIiUsHDKfE8HdNnea.1OEVhd4ukrnc54axOg5TDuDE2MNgC'); -- zxcZXC789