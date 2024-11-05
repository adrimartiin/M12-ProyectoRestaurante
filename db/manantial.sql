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

-- Alter tables para FK
ALTER TABLE tbl_mesa
ADD CONSTRAINT fk_sala_mesa
FOREIGN KEY (id_sala) REFERENCES tbl_sala(id_sala);

ALTER TABLE tbl_ocupacion
ADD CONSTRAINT fk_mesa_ocupacion
FOREIGN KEY (id_mesa) REFERENCES tbl_mesa(id_mesa);

ALTER TABLE tbl_ocupacion
ADD CONSTRAINT fk_camarero_ocupacion
FOREIGN KEY (id_camarero) REFERENCES tbl_camarero(id_camarero);

ALTER TABLE tbl_ocupacion
ADD CONSTRAINT fk_cliente_ocupacion
FOREIGN KEY (id_cliente) REFERENCES tbl_cliente(id_cliente);

-- Inserts
INSERT INTO tbl_cliente (nombre, num_personas) VALUES 
('Juan Pérez', 4),
('María López', 2),
('Carlos Sánchez', 3);


INSERT INTO tbl_sala (id_sala, nombre_sala, tipo_sala, capacidad_total) VALUES 
(1, 'Terraza Principal', 'terraza', 50),
(2, 'Comedor Interior', 'comedor', 40),
(3, 'Sala Privada', 'privada', 10);


INSERT INTO tbl_mesa (id_sala, num_sillas_mesa, estado_mesa) VALUES 
(1, 4, 'libre'),
(1, 2, 'libre'),
(2, 4, 'ocupada'),
(3, 6, 'libre');


INSERT INTO tbl_camarero (nombre_camarero, codigo_camarero, password_camarero) VALUES 
('Ana González', 'C001', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'), -- qweQWE123
('Luis Martínez', 'C002', '$2a$12$DB3.O4aga98EH./zW9P9beKfklJkTcXMY0AnL3T6nheQhpM3usreO'), -- asdASD456
('Sara García', 'C003', '$2a$12$b509yhiIiUsHDKfE8HdNnea.1OEVhd4ukrnc54axOg5TDuDE2MNgC'); -- zxcZXC789

INSERT INTO tbl_ocupacion (id_mesa, id_camarero, id_cliente, fecha_hora_ocupacion, fecha_hora_desocupacion) VALUES 
(3, 2, 1, '2023-11-05 12:00:00', NULL),
(4, 1, 2, '2023-11-05 13:30:00', '2023-11-05 14:30:00')