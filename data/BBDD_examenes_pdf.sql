CREATE DATABASE examenes_pdf;

CREATE TABLE preguntas (
  id              INT PRIMARY KEY AUTO_INCREMENT,
  pregunta        VARCHAR(256),
  categoria_id    INT NOT NULL,
  imagen_id       INT,
);

CREATE TABLE imagenes (
  id              INT PRIMARY KEY AUTO_INCREMENT,
  nombre          VARCHAR(255)
);

CREATE TABLE categoriaspregunta (
  id              INT PRIMARY KEY AUTO_INCREMENT,
  categoria_id    INT NOT NULL,
  pregunta_id     INT NOT NULL
);

CREATE TABLE respuestas (
  id              INT PRIMARY KEY AUTO_INCREMENT,
  pregunta_id     INT,
  respuesta       VARCHAR(255),
  correcta        BOOLEAN
);

CREATE TABLE categorias (
  id              INT PRIMARY KEY AUTO_INCREMENT,
  categoria       VARCHAR(255)
);

CREATE TABLE test (
  id              INT PRIMARY KEY AUTO_INCREMENT,
  descripcion     VARCHAR(255),
  materia         VARCHAR(255),
  fecha           DATETIME,
  titulo          VARCHAR(255),
  titulo_imprimir VARCHAR(255)
);

ALTER TABLE preguntas ADD FOREIGN KEY (categoria_id) REFERENCES categorias(id)
  ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE respuestas ADD FOREIGN KEY (pregunta_id) REFERENCES preguntas(id)
  ON DELETE CASCADE ON UPDATE CASCADE;

