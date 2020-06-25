-- INICIO

SET NAMES 'utf8';

DROP DATABASE IF EXISTS examenes_pdf;

CREATE DATABASE examenes_pdf
  CHARACTER SET utf8
	COLLATE utf8_unicode_ci;

USE examenes_pdf;

CREATE TABLE preguntas (
  id              INT PRIMARY KEY AUTO_INCREMENT,
  pregunta        VARCHAR(255) DEFAULT NULL,
  test_id         INT NOT NULL,
  imagen_id       INT DEFAULT NULL
)
ENGINE = INNODB,
CHARACTER SET utf8,
COLLATE utf8_unicode_ci;

CREATE TABLE imagenes (
  id              INT PRIMARY KEY AUTO_INCREMENT,
  nombre          VARCHAR(255) DEFAULT NULL
)
ENGINE = INNODB,
CHARACTER SET utf8,
COLLATE utf8_unicode_ci;

CREATE TABLE respuestas (
  id              INT PRIMARY KEY AUTO_INCREMENT,
  pregunta_id     INT NOT NULL,
  respuesta       VARCHAR(255) DEFAULT NULL,
  correcta        INT(1) DEFAULT 0
)
ENGINE = INNODB,
CHARACTER SET utf8,
COLLATE utf8_unicode_ci;

CREATE TABLE categorias (
  id              INT PRIMARY KEY AUTO_INCREMENT,
  categoria       VARCHAR(255) UNIQUE
)
ENGINE = INNODB,
CHARACTER SET utf8,
COLLATE utf8_unicode_ci;

CREATE TABLE tests (
  id              INT PRIMARY KEY AUTO_INCREMENT,
  descripcion     VARCHAR(255) DEFAULT NULL,
  materia         VARCHAR(255) DEFAULT NULL,
  fecha           DATETIME DEFAULT NULL,
  titulo          VARCHAR(255) DEFAULT NULL,
  titulo_impreso  VARCHAR(255) DEFAULT NULL
)
ENGINE = INNODB,
CHARACTER SET utf8,
COLLATE utf8_unicode_ci;

CREATE TABLE categoriaspregunta (
  id              INT PRIMARY KEY AUTO_INCREMENT,
  pregunta_id     INT NOT NULL,
  categoria_id    INT NOT NULL
)
ENGINE = INNODB,
CHARACTER SET utf8,
COLLATE utf8_unicode_ci;

CREATE TABLE preguntastest (
  id              INT PRIMARY KEY AUTO_INCREMENT,
  test_id         INT NOT NULL,
  pregunta_id     INT NOT NULL
)
ENGINE = INNODB,
CHARACTER SET utf8,
COLLATE utf8_unicode_ci;

CREATE TABLE respuestaspregunta (
  id              INT PRIMARY KEY AUTO_INCREMENT,
  pregunta_id     INT NOT NULL,
  respuesta_id    INT NOT NULL
)
ENGINE = INNODB,
CHARACTER SET utf8,
COLLATE utf8_unicode_ci;

CREATE TABLE categoriastest (
  id              INT PRIMARY KEY AUTO_INCREMENT,
  test_id         INT NOT NULL,
  categoria_id    INT NOT NULL
)
ENGINE = INNODB,
CHARACTER SET utf8,
COLLATE utf8_unicode_ci;


ALTER TABLE preguntas ADD FOREIGN KEY fk_preguntas_imagen (imagen_id) REFERENCES imagenes(id)
  ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE preguntas ADD FOREIGN KEY fk_preguntas_test (test_id) REFERENCES tests(id)
  ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE respuestas ADD FOREIGN KEY fk_respuestas_pregunta (pregunta_id) REFERENCES preguntas(id)
  ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE categoriaspregunta ADD FOREIGN KEY fk_categoriaspregunta_pregunta (pregunta_id) REFERENCES preguntas(id)
  ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE categoriaspregunta ADD FOREIGN KEY fk_categoriaspregunta_categoria (categoria_id) REFERENCES categorias(id)
  ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE categoriaspregunta ADD CONSTRAINT pk_categoriaspregunta_pregunta_categoria
  UNIQUE KEY (pregunta_id, categoria_id);

ALTER TABLE preguntastest ADD FOREIGN KEY fk_preguntastest_test (test_id) REFERENCES tests(id)
  ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE preguntastest ADD FOREIGN KEY fk_preguntastest_pregunta (pregunta_id) REFERENCES preguntas(id)
  ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE preguntastest ADD CONSTRAINT pk_preguntastest_test_pregunta
  UNIQUE KEY (test_id, pregunta_id);

ALTER TABLE respuestaspregunta ADD FOREIGN KEY fk_respuestaspregunta_pregunta (pregunta_id) REFERENCES preguntas(id)
  ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE respuestaspregunta ADD FOREIGN KEY fk_respuestaspregunta_respuesta (respuesta_id) REFERENCES respuestas(id)
  ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE respuestaspregunta ADD CONSTRAINT pk_respuestaspregunta_pregunta_respuesta
  UNIQUE KEY (pregunta_id, respuesta_id);

ALTER TABLE categoriastest ADD FOREIGN KEY fk_categoriastest_test (test_id) REFERENCES tests(id)
  ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE categoriastest ADD FOREIGN KEY fk_categoriastest_categoria (categoria_id) REFERENCES categorias(id)
  ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE categoriastest ADD CONSTRAINT pk_categoriastest_test_categoria
  UNIQUE KEY (test_id, categoria_id);

--FIN

