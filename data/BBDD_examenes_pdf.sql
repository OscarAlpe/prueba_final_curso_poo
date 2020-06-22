-- INICIO

SET NAMES 'utf8';

DROP DATABASE IF EXISTS examenes_pdf;

CREATE DATABASE examenes_pdf
  CHARACTER SET latin1
	COLLATE latin1_spanish_ci;

USE examenes_pdf;

CREATE TABLE preguntas (
  id              INT PRIMARY KEY AUTO_INCREMENT,
  pregunta        VARCHAR(255) DEFAULT NULL,
  imagen_id       INT DEFAULT NULL
)
ENGINE = INNODB,
CHARACTER SET latin1,
COLLATE latin1_spanish_ci;

CREATE TABLE imagenes (
  id              INT PRIMARY KEY AUTO_INCREMENT,
  nombre          VARCHAR(255) DEFAULT NULL
)
ENGINE = INNODB,
CHARACTER SET latin1,
COLLATE latin1_spanish_ci;

CREATE TABLE respuestas (
  id              INT PRIMARY KEY AUTO_INCREMENT,
  pregunta_id     INT NOT NULL,
  respuesta       VARCHAR(255) DEFAULT NULL,
  correcta        BOOLEAN DEFAULT NULL
)
ENGINE = INNODB,
CHARACTER SET latin1,
COLLATE latin1_spanish_ci;

CREATE TABLE categorias (
  id              INT PRIMARY KEY AUTO_INCREMENT,
  categoria       VARCHAR(255) DEFAULT NULL
)
ENGINE = INNODB,
CHARACTER SET latin1,
COLLATE latin1_spanish_ci;

CREATE TABLE tests (
  id              INT PRIMARY KEY AUTO_INCREMENT,
  descripcion     VARCHAR(255) DEFAULT NULL,
  materia         VARCHAR(255) DEFAULT NULL,
  fecha           DATETIME DEFAULT NULL,
  titulo          VARCHAR(255) DEFAULT NULL,
  titulo_impreso  VARCHAR(255) DEFAULT NULL
)
ENGINE = INNODB,
CHARACTER SET latin1,
COLLATE latin1_spanish_ci;

CREATE TABLE categoriaspregunta (
  id              INT PRIMARY KEY AUTO_INCREMENT,
  pregunta_id     INT NOT NULL,
  categoria_id    INT NOT NULL
)
ENGINE = INNODB,
CHARACTER SET latin1,
COLLATE latin1_spanish_ci;

CREATE TABLE preguntastest (
  id              INT PRIMARY KEY AUTO_INCREMENT,
  test_id         INT NOT NULL,
  pregunta_id     INT NOT NULL
)
ENGINE = INNODB,
CHARACTER SET latin1,
COLLATE latin1_spanish_ci;

CREATE TABLE respuestaspregunta (
  id              INT PRIMARY KEY AUTO_INCREMENT,
  pregunta_id     INT NOT NULL,
  respuesta_id    INT NOT NULL
)
ENGINE = INNODB,
CHARACTER SET latin1,
COLLATE latin1_spanish_ci;

ALTER TABLE preguntas ADD FOREIGN KEY fk_imagen (imagen_id) REFERENCES imagenes(id)
  ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE categoriaspregunta ADD FOREIGN KEY fk_pregunta (pregunta_id) REFERENCES preguntas(id)
  ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE categoriaspregunta ADD FOREIGN KEY fk_categoria (categoria_id) REFERENCES categorias(id)
  ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE categoriaspregunta ADD CONSTRAINT pk_pregunta_categoria UNIQUE KEY (pregunta_id, categoria_id);

ALTER TABLE preguntastest ADD FOREIGN KEY fk_test (test_id) REFERENCES tests(id)
  ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE preguntastest ADD FOREIGN KEY fk_pregunta (pregunta_id) REFERENCES preguntas(id)
  ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE preguntastest ADD CONSTRAINT pk_test_pregunta UNIQUE KEY (test_id, pregunta_id);

ALTER TABLE respuestaspregunta ADD FOREIGN KEY fk_pregunta (pregunta_id) REFERENCES preguntas(id)
  ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE respuestaspregunta ADD FOREIGN KEY fk_respuesta (respuesta_id) REFERENCES respuestas(id)
  ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE respuestaspregunta ADD CONSTRAINT pk_pregunta_respuesta UNIQUE KEY (pregunta_id, respuesta_id);

--FIN
