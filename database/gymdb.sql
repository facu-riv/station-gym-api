CREATE DATABASE IF NOT EXISTS gymdb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gymdb;

CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario VARCHAR(100) UNIQUE NOT NULL,
  contrasena VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS categorias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  imagen VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS actividades (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  categoria_id INT NOT NULL,
  imagen VARCHAR(255) DEFAULT NULL,
  CONSTRAINT fk_cat FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO usuarios (usuario, contrasena) VALUES
  ('webadmin', '$2y$10$Qn1T8p3mRrQPu5fQbD0WnO5wCqNf0eX1n9l8bqE3e0wA1Jd9H9M4K');

INSERT INTO categorias (nombre) VALUES
  ('Fuerza'), ('Cardio'), ('Funcional');

INSERT INTO actividades (nombre, categoria_id) VALUES
  ('Press banca', 1), ('Sentadillas', 1),
  ('Cinta', 2), ('Bicicleta fija', 2),
  ('HIIT', 3), ('Circuito funcional', 3);

CREATE TABLE IF NOT EXISTS reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    actividad_id INT NOT NULL,
    nombre_cliente VARCHAR(100) NOT NULL,
    email_cliente VARCHAR(100) NULL,
    fecha_reserva DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (actividad_id) REFERENCES actividades(id) ON DELETE CASCADE
);