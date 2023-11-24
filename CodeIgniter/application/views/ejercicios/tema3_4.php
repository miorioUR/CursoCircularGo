<!DOCTYPE html>
<html>
<head>
	<title>Ejercicio 4 tema 3</title>
</head>
<body>

<h2>EL IMPRESIONANTE   * F O R M U L A R I O *</h2>
<?php
echo form_open("form/",$attributes,$hidden);
echo form_label('Texto I:', 'text1');
echo form_input("text1","texto_primero",$text1) . "<br>";
echo form_label('Texto II:', 'text2');
echo form_input("text2","texto-segundo",$text2) . "<br><br>";
echo form_label('Texto III:', 'text3');
echo form_textarea($textarea_atts) . "<br><br>";
echo form_label('Opciones: ', 'opciones');
echo form_dropdown("letras", $dropdown_opts, "b","'id' => 'opciones','class->'dropdown'"). "<br><br>";
echo form_checkbox("alto", "bicho_alto", TRUE,$bicho1);
echo form_label('El bicho es alto', 'bicho1');
echo form_checkbox("guapo", "bicho_guapo", TRUE,$bicho2);
echo form_label('El bicho es guapo', 'bicho2');
echo form_checkbox("jogador", "bicho_jogador", TRUE,$bicho3);
echo form_label('El bicho es buen jogador', 'bicho3'). "<br><br>";
echo form_label('Sentir la felicidad', 'status1');
echo form_radio("status","feliz",FALSE,$status1) . "<br>";
echo form_label('Jugar al LoL', 'status2');
echo form_radio("status","lol",FALSE, $status2) . "<br>";
echo form_label('Cotizar a la Seguridad Social', 'status3');
echo form_radio("status","cotizador",FALSE, $status3). "<br>";
echo form_button('el_motherfucking_boton', 'MIRAME SOY UN BOTON SIVENGA', $boton_js);
echo form_close();
?>
</body>
</html>

