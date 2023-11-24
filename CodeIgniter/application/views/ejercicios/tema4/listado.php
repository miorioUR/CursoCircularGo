<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<title>Listado de Productos</title>
</head>
<body>
<h1>Listado de Productos</h1>
<?php
$attributes = array('id' => 'formlistado', 'name' => 'newproducto', 'class' => 'forms', 'method' => 'POST');
$btNuevo = array('name' => 'btSubmit' , 'id' => 'btSubmit','class'=>'button' , 'value'=>'Nuevo Producto');

echo form_open("/tema4/Productos/newProducto",$attributes);
echo form_submit($btNuevo);
echo form_close();
?>
<?= $tabla_productos ?>
<?php echo "Mostrando productos " . (($pagina-1)*$tampagina+1) . "-" . ($pagina)*$tampagina .  " de " . $numproductos;	?>
<br>
<?php echo $links; ?>
</body>
</html>
