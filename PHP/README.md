He añadido la gran mayoria de las cosas.
Faltan transformaciones del estilo añadir un boton que ejecute JS pero los metodos de modificacion de pedido y BBDD subyacientes ya existen

Observaciones (no las he retesteado pero mayoritariamente siguen existiendo):

1- Hay un ligero caos con la gestion de las fechas.  
Todo seria mas sencillo utilizando strings pero quiero consistencia y eso me hace perder pelo con cada cambio de formato etc...  0
Veras dates, strtodate(), format() y malabares para esto, no se si realmente vale la pena.  
Me voy a dormir de forma no ironica pensando como cuadrar el sistema de tipos para que no den problemas.  

2- El rider en el objeto pedido es una string pero he conseguido adaptarlo para que los updates de BBDD tengan la id del rider (se cosas).  
Podria hacerlo mas consistente haciendolo usuario pero da problemas al imprimir la tabla.  

3- Los filtros funcionan mediante un complejo sistema de poleas donde testeo nulos y hay muchas opciones.  
Hhay alguna forma de hacerlo mas rapido seguro pero ya he tirado demasiadas horas intentando cosas y paso, funcionan.  

4- Accionficha cuando vuelve al listado no es capaz de mantener la pagina porque esta se pierde cuando hago click en el <a> de una referencia. Ni idea de por que.  

5- Me salen los warnings de usar indices ¿Los tengo que hacer desde dbeaver o desde mysqli? me da un poco de miedo tocar por si se cae todo ngl.  

6- Cuando hay errores en una ficha te sale el mensajito rojo precioso alegre bonito pero cuando cambias de url y vuelves sigue ahi porque sigue en sesion.  
Deberia meterle algo para borrarlo a la fuerza cada vez que se entre en una ficha. ¿Cual es la forma mas correcta? Ya probe con reset y nulls y tambien tire alguna hora con esa tonteria.  

7- Incompatibilidad de charset. Algun tip? en el html? en el navegador?  

8- La asignacion de rider se hace desde la propia ficha. Si lo quisiera mas limpio haria otra vista pero no parece necesario.

9- Falta añadir rider. Es igual que ficha.php pero en pequeño. Si te hace mucha ilu te lo hago.
