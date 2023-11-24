//Ejercicio 1
//No termina de funcionar :(
CREATE FUNCTION getStringDatos (jugador int)
    RETURNS varchar(255)
    DETERMINISTIC
	BEGIN
		DECLARE stringDatos varchar(255);
         SET stringDatos =
         (SELECT CONCAT(j.NOMBRE , " " , j.APELLIDO_1 , " " , j.APELLIDO_2 , ", " , COALESCE (e.NOMBRE,"-") , ", " , COALESCE (g.NOMBRE,"-"))
         FROM JUGADOR j left join EQUIPO e on j.FK_ID_EQUIPO = e.PK_ID_EQUIPO 
			   			left join EQUIPO_GRUPO eg on e.PK_ID_EQUIPO = eg.FK_ID_EQUIPO 
			   			left join GRUPO g on eg.FK_ID_GRUPO = g.PK_ID_GRUPO 
         WHERE j.PK_ID_JUGADOR = jugador);
         
        RETURN stringDatos;
       END

//Esto funciona
SELECT j.PK_ID_JUGADOR, getStringDatos(PK_ID_JUGADOR) FROM JUGADOR j;
//Esto no funciona
ALTER TABLE JUGADOR ADD datos_completos as getStringDatos(PK_ID_JUGADOR);


//Ejercicio 2
SELECT getStringDatos(j.PK_ID_JUGADOR) as "Datos jugador", COALESCE (e.NOMBRE,"Sin equipo"), g.nombre
FROM JUGADOR j left join EQUIPO e on j.FK_ID_EQUIPO = e.PK_ID_EQUIPO 
			   left join EQUIPO_GRUPO eg on e.PK_ID_EQUIPO = eg.FK_ID_EQUIPO 
			   left join GRUPO g on eg.FK_ID_GRUPO = g.PK_ID_GRUPO 

//Ejercicio 3
CREATE TABLE IF NOT EXISTS USUARIO(
	PK_ID_USUARIO int AUTO_INCREMENT PRIMARY KEY,
    USER_NAME varchar(255) UNIQUE,
    USER_PASSWORD varchar(255),
    DATOS_TARJETA blob,
    FK_ID_JUGADOR int,
    FOREIGN KEY (FK_ID_JUGADOR) REFERENCES JUGADOR(PK_ID_JUGADOR)
)
INSERT INTO USUARIO(USER_NAME,USER_PASSWORD,DATOS_TARJETA,FK_ID_JUGADOR) 
VALUES ("Davidxd95",SHA2("david1234",256),AES_ENCRYPT("ES21 0000 0000 00 0000000000",md5("xdpasswordxd")),1)

//Ejercicio 4
SHOW VARIABLES LIKE "secure_file_priv"

SELECT * FROM JUGADOR
INTO OUTFILE '/var/lib/mysql-files/tabla_jugador.csv'
FIELDS TERMINATED BY ','
ENCLOSED BY "'"
LINES TERMINATED BY '\n';

//Esta carpeta esta en el contenedor del docker? En ese caso tengo que estudiar como acceder a ello(problema) (no encuentro el dockerfile)

 
