Station Gym – API REST (TPE Parte 3)

Este repositorio contiene la API REST pública correspondiente a la tercera parte del Trabajo Práctico Especial de Web 2.

La API permite integrar datos del proyecto "Station Gym" con aplicaciones de terceros, ofreciendo endpoints para:

Listar actividades del gimnasio

Ver detalles de actividades

Consultar reservas

Crear y modificar reservas mediante POST y PUT

Está diseñada siguiendo los principios de una API RESTful, utilizando PHP + PDO, y compartiendo la misma base de datos del TPE 1–2.


Instalación:

Clonar o descargar este repositorio en la carpeta de XAMPP:

C:\xampp\htdocs\station-gym-api\


Crear la base de datos en MySQL usando el archivo incluido:

database/gymdb.sql


Este archivo genera:

Todas las tablas del TPE Parte 1 y 2

La tabla adicional reservas, necesaria para esta API

Iniciar Apache y MySQL desde XAMPP.

Acceder a la API mediante:

http://localhost/station-gym-api/

Base de datos:

La API utiliza la misma base de datos gymdb que el proyecto web del TPE 1–2.

Además, la Parte 3 agrega la tabla:

CREATE TABLE IF NOT EXISTS reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    actividad_id INT NOT NULL,
    nombre_cliente VARCHAR(100) NOT NULL,
    email_cliente VARCHAR(100) NULL,
    fecha_reserva DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (actividad_id) REFERENCES actividades(id) ON DELETE CASCADE
);

Documentación de Endpoints:

Todos los endpoints devuelven JSON y pueden ser probados con Postman.

Base URL:

http://localhost/station-gym-api/

🔵 1. GET /api/actividades

Devuelve todas las actividades.

Parámetros opcionales
Parámetro	Valores	Default
sort	id, nombre	id
order	asc, desc	asc
Ejemplos:
GET /api/actividades
GET /api/actividades?sort=nombre&order=asc

Respuesta (200)
[
  {
    "id": 1,
    "nombre": "Press banca",
    "categoria_id": 1,
    "imagen": null,
    "categoria": "Fuerza"
  }
]

🔵 2. GET /api/actividades/{id}

Devuelve una actividad por ID.

Ejemplo:
GET /api/actividades/1

Respuesta (200)
{
  "id": 1,
  "nombre": "Press banca",
  "categoria_id": 1,
  "imagen": null,
  "categoria": "Fuerza"
}

Error (404)
{ "error": "Actividad no encontrada" }

🔵 3. GET /api/actividades/{id}/reservas

Devuelve todas las reservas asociadas a una actividad.

Ejemplo:
GET /api/actividades/1/reservas

Respuesta (200)
[]


(Vacío si no hay reservas)

🟢 4. GET /api/reservas/{id}

Obtiene una reserva específica.

Ejemplo:
GET /api/reservas/1

Respuesta exitosa (200)
{
  "id": 1,
  "actividad_id": 1,
  "nombre_cliente": "Facundo Uriel Rivarola",
  "email_cliente": "facu@mail.com",
  "fecha_reserva": "2025-11-20 18:00:00"
}

Error (404)
{ "error": "Reserva no encontrada" }

🟣 5. POST /api/reservas

Crea una reserva.

Body (JSON)
{
  "actividad_id": 1,
  "nombre_cliente": "Facundo Uriel Rivarola",
  "email_cliente": "facu@mail.com",
  "fecha_reserva": "2025-11-20 18:00:00"
}

Respuesta (201 Created)
{
  "id": 1,
  "actividad_id": 1,
  "nombre_cliente": "Facundo Uriel Rivarola",
  "email_cliente": "facu@mail.com",
  "fecha_reserva": "2025-11-20 18:00:00"
}

Error (400)
{
  "error": "actividad_id, nombre_cliente y fecha_reserva son obligatorios"
}

🟠 6. PUT /api/reservas/{id}

Modifica una reserva existente.

Body:
{
  "nombre_cliente": "Facu R.",
  "email_cliente": "nuevo@mail.com",
  "fecha_reserva": "2025-11-20 19:00:00"
}

Respuesta (200)
{
  "id": 1,
  "actividad_id": 1,
  "nombre_cliente": "Facu R.",
  "email_cliente": "nuevo@mail.com",
  "fecha_reserva": "2025-11-20 19:00:00"
}

Error (404)
{ "error": "Reserva no encontrada" }

✔️ Códigos de estado utilizados
Código	Significado
200	OK – operación correcta
201	Created – recurso creado correctamente
400	Bad Request – datos inválidos
404	Not Found – recurso no encontrado
