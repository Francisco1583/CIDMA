CREATE TABLE MDP_tipo_maquina (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  tipo_maquina VARCHAR(100),
  img VARCHAR(1000)
);

CREATE TABLE MDP_estado (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  estado VARCHAR(100)
);

CREATE TABLE MDP_estado_usuario (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  estado VARCHAR(100)
);

CREATE TABLE MDP_Maquina (
  id VARCHAR(25) NOT NULL PRIMARY KEY,
  id_tipo_maquina INT,
  nombre VARCHAR(100),
  id_estado INT,
  imgMaquina VARCHAR(1000),
  FOREIGN KEY (id_estado) REFERENCES MDP_estado(id)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  FOREIGN KEY (id_tipo_maquina) REFERENCES MDP_tipo_maquina(id)
);

CREATE TABLE MDP_Usuario (
  matricula VARCHAR(15) NOT NULL PRIMARY KEY,
  nombre VARCHAR(100),
  Apellido_paterno VARCHAR(100),
  Apellido_materno VARCHAR(200),
  id_estado INT,
  FOREIGN KEY (id_estado) REFERENCES MDP_estado_usuario(id)
  ON DELETE CASCADE
  ON UPDATE CASCADE
);

CREATE TABLE MDP_Administrador (
  Matricula_usuario VARCHAR(15),
  password VARCHAR(255),
  FOREIGN KEY (Matricula_usuario) REFERENCES MDP_Usuario(matricula)
  ON DELETE CASCADE
  ON UPDATE CASCADE
);

CREATE TABLE MDP_Maquina_uso_usuario (
  id_maquina VARCHAR(25),
  Matricula_usuario VARCHAR(15),
  estado_maquina INT,
  ts timestamp default NOW(),
  FOREIGN KEY (id_maquina) REFERENCES MDP_Maquina(id)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  FOREIGN KEY (Matricula_usuario) REFERENCES MDP_Usuario(matricula)
  ON DELETE CASCADE
  ON UPDATE CASCADE
);

CREATE VIEW MDP_Maquina_uso_usuario_detail AS
SELECT MDP_Maquina_uso_usuario.id_maquina, MDP_Maquina_uso_usuario.Matricula_usuario AS matricula, MDP_Usuario.nombre AS nombre, MDP_estado.estado AS estado,MDP_Maquina_uso_usuario.ts AS fecha FROM MDP_Maquina_uso_usuario
JOIN MDP_Usuario ON MDP_Maquina_uso_usuario.Matricula_usuario = MDP_Usuario.matricula
JOIN MDP_estado ON MDP_Maquina_uso_usuario.estado_maquina = MDP_estado.id;

CREATE VIEW MDP_users_detail AS SELECT MDP_Usuario.matricula, MDP_Usuario.nombre, MDP_Usuario.Apellido_paterno, MDP_Usuario.Apellido_materno, MDP_estado_usuario.estado FROM MDP_Usuario LEFT JOIN MDP_estado_usuario ON MDP_Usuario.id_estado = MDP_estado_usuario.id;

CREATE VIEW MDP_maquina_detalles AS select MDP_Maquina.id AS id, MDP_Maquina.nombre AS nombre, MDP_estado.estado AS estado, MDP_estado.id AS id_estado, MDP_tipo_maquina.tipo_maquina AS tipo_maquina FROM MDP_Maquina JOIN MDP_estado ON id_estado = MDP_estado.id JOIN MDP_tipo_maquina ON id_tipo_maquina = MDP_tipo_maquina.id;

create VIEW MDP_historial_opcion AS select MDP_Maquina_uso_usuario_detail.matricula, concat(MDP_Maquina_uso_usuario_detail.nombre, ' ', MDP_Usuario.Apellido_paterno,' ', MDP_Usuario.Apellido_materno) AS nombre_usuario, id_maquina, MDP_Maquina.nombre, estado, fecha
FROM MDP_Maquina_uso_usuario_detail
JOIN MDP_Maquina on MDP_Maquina_uso_usuario_detail.id_maquina = MDP_Maquina.id
JOIN MDP_Usuario on MDP_Maquina_uso_usuario_detail.matricula = MDP_Usuario.matricula;

CREATE VIEW MDP_maquinas_opcion AS select MDP_Maquina.id as id, id_tipo_maquina, img, nombre, id_estado from MDP_Maquina
JOIN MDP_tipo_maquina ON MDP_Maquina.id_tipo_maquina = MDP_tipo_maquina.id;

INSERT INTO MDP_tipo_maquina (tipo_maquina) values
('impresora 3d'),
('cortadora laser');

INSERT INTO MDP_estado (estado) values
('disponible'),
('en uso'),
('fuera de servicio');

INSERT INTO MDP_estado_usuario(estado) values
('de alta'),
('de baja');

INSERT INTO MDP_Maquina (id,id_tipo_maquina,nombre,id_estado,imgMaquina) VALUES
(1,1,'impresora izquierda',1,'https://th.bing.com/th/id/R.54c60d5ed07bb714199312d133216d50?rik=uilAo%2bvGgbj8xQ&riu=http%3a%2f%2fformizable.com%2fwp-content%2fuploads%2f2016%2f01%2fMB05_REP_03.png&ehk=E9t%2b7q87WQn12AnlJtlzJnbZhvJJtDYiDEA%2fL6Umi0c%3d&risl=&pid=ImgRaw&r=0'),
(2,2,'cortadora 1',1,'https://tse2.mm.bing.net/th/id/OIP.oKcpiqHwXHm8_nx1BIfn_gHaFj?rs=1&pid=ImgDetMain'),
(3,2,'cortadora 2',1,'https://th.bing.com/th/id/R.be7cf501de7c8096b02ad21f91de9d8e?rik=ZJcg%2bfUbmpWIJA&pid=ImgRaw&r=0&sres=1&sresct=1');

INSERT INTO MDP_Usuario (matricula, nombre, Apellido_paterno, Apellido_materno, id_estado) VALUES
('A01737275','Francisco','Ricardez','López',1),
('A01733717','Alejandro','Moreno','Santana',1),
('A01737438','Pablo André','Murillo','Coca',1);

INSERT INTO MDP_Administrador (Matricula_usuario,password) VALUES
('A01733717',SHA2('hola123@',256));