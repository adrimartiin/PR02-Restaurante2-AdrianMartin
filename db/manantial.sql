
CREATE DATABASE bd_restaurante;


USE bd_restaurante;


CREATE TABLE tbl_tipo_sala (
    id_tipo_sala INT AUTO_INCREMENT PRIMARY KEY,
    tipo_sala VARCHAR(30) NOT NULL UNIQUE
);


CREATE TABLE tbl_sala (
    id_sala INT AUTO_INCREMENT PRIMARY KEY,
    nombre_sala VARCHAR(50) NOT NULL,
    id_tipo_sala INT NOT NULL,
    capacidad_total INT NOT NULL,
    FOREIGN KEY (id_tipo_sala) REFERENCES tbl_tipo_sala(id_tipo_sala)
);


CREATE TABLE tbl_mesa (
    id_mesa INT AUTO_INCREMENT PRIMARY KEY,
    id_sala INT NOT NULL,
    num_sillas_mesa INT NOT NULL,
    estado_mesa VARCHAR(30) NOT NULL DEFAULT 'libre',
    FOREIGN KEY (id_sala) REFERENCES tbl_sala(id_sala)
);


CREATE TABLE tbl_estado_silla (
    id_estado_silla INT AUTO_INCREMENT PRIMARY KEY,
    estado VARCHAR(30) NOT NULL UNIQUE
);


CREATE TABLE tbl_silla (
    id_silla INT AUTO_INCREMENT PRIMARY KEY,
    id_mesa INT NOT NULL,
    id_estado_silla INT NOT NULL,
    FOREIGN KEY (id_mesa) REFERENCES tbl_mesa(id_mesa),
    FOREIGN KEY (id_estado_silla) REFERENCES tbl_estado_silla(id_estado_silla)
);


CREATE TABLE tbl_rol (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre_rol VARCHAR(50) NOT NULL UNIQUE
);


CREATE TABLE tbl_usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50) NOT NULL,
    nombre_real_usuario VARCHAR(50) NOT NULL,
    password_usuario VARCHAR(255) NOT NULL,
    id_rol INT NOT NULL,
    FOREIGN KEY (id_rol) REFERENCES tbl_rol(id_rol)
);


CREATE TABLE tbl_ocupacion (
    id_ocupacion INT AUTO_INCREMENT PRIMARY KEY,
    id_mesa INT NOT NULL,
    id_usuario INT NOT NULL, 
    fecha_hora_ocupacion DATETIME NOT NULL,
    fecha_hora_desocupacion DATETIME,
    FOREIGN KEY (id_mesa) REFERENCES tbl_mesa(id_mesa),
    FOREIGN KEY (id_usuario) REFERENCES tbl_usuario(id_usuario)
);


CREATE TABLE tbl_estado_reserva (
    id_estado_reserva INT AUTO_INCREMENT PRIMARY KEY,
    estado VARCHAR(30) NOT NULL UNIQUE
);


CREATE TABLE tbl_reserva (
    id_reserva INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL, 
    fecha_reserva DATE NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    id_estado_reserva INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES tbl_usuario(id_usuario),
    FOREIGN KEY (id_estado_reserva) REFERENCES tbl_estado_reserva(id_estado_reserva)
);

/* INSERTS */

/* INSERT USUARIOS LOGIN*/
-- PARA TODOS LA PWD ES qweQWE123
-- Administrador
INSERT INTO tbl_usuario (nombre_usuario, nombre_real_usuario, password_usuario, id_rol) VALUES 
('admin', 'Administrador General', '$2a$12$HIM6AtoZ8QuG4kxjfnuBEuDgEAEqKI59w1ZyfEqI3zQtZh8X.AdP2', 1);

-- Camareros
INSERT INTO tbl_usuario (nombre_usuario, nombre_real_usuario, password_usuario, id_rol) VALUES 
('camarero01', 'Juan Pérez', '$2a$12$HIM6AtoZ8QuG4kxjfnuBEuDgEAEqKI59w1ZyfEqI3zQtZh8X.AdP2', 2),
('camarero02', 'María López', '$2a$12$HIM6AtoZ8QuG4kxjfnuBEuDgEAEqKI59w1ZyfEqI3zQtZh8X.AdP2', 2),
('camarero03', 'Pedro Gómez', '$2a$12$HIM6AtoZ8QuG4kxjfnuBEuDgEAEqKI59w1ZyfEqI3zQtZh8X.AdP2', 2),
('camarero04', 'Laura Sánchez', '$2a$12$HIM6AtoZ8QuG4kxjfnuBEuDgEAEqKI59w1ZyfEqI3zQtZh8X.AdP2', 2);

/* TIPOS DE SALA */
INSERT INTO tbl_tipo_sala (tipo_sala) VALUES ('Comedor');
INSERT INTO tbl_tipo_sala (tipo_sala) VALUES ('Terraza');
INSERT INTO tbl_tipo_sala (tipo_sala) VALUES ('Sala Privada');

/* INSERTS PARA SALAS DENTRO DE TIPOS DE SALA */
INSERT INTO tbl_sala (nombre_sala, id_tipo_sala, capacidad_total) VALUES 
-- Terrazas
('Terraza Norte', 2, 30),
('Terraza Sur', 2, 25),
('Terraza Central', 2, 40),
-- Comedores
('Comedor Principal', 1, 50),
('Comedor Secundario', 1, 35),
-- Salas Privadas
('Sala Privada VIP', 3, 10),
('Sala Privada Ejecutivo', 3, 15),
('Sala Privada Familiar', 3, 12),
('Sala Privada Eventos', 3, 20);







    
