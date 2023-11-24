<!DOCTYPE html>
<html>
<head>
	<title>Ficha</title>
</head>
<body>

<?php echo validation_errors('<div class=error style=color:red>','</div>');?>

<h2>Ficha de producto</h2>
<?php
$attributes = array('id' => 'formficha', 'name' => 'ficha', 'class' => 'forms', 'method' => 'POST');
$txNombre = array('name' => 'txNombre' , 'id' => 'txNombre','class'=>'text', 'value' => set_value('txNombre',$producto[0]['NOMBRE'] ?? ''));
$txMarca = array('name' => 'txMarca' , 'id' => 'txMarca','class'=>'text', 'value' => set_value('txMarca',$producto[0]['MARCA'] ?? ''));
$txPrecio = array('name' => 'txPrecio' , 'id' => 'txPrecio','class'=>'text', 'value' => set_value('txPrecio',$producto[0]['PRECIO'] ?? ''));
$txCantidad = array('name' => 'txCantidad' , 'id' => 'txCantidad','class'=>'text', 'value' => set_value('txCantidad',$producto[0]['CANTIDAD'] ?? ''));
$categorias = $this->EjerciciosT3_model->getCategorias();
$optCategoria = array('' => 'Elija una categoria');
foreach($categorias as $cat){
	$optCategoria += array($cat['PK_ID_CATEGORIA'] => $cat['NOMBRE']);
}
$selCategoria = array('name' => 'selCategoria' , 'id' => 'selCategoria', 'class' => 'select');
$btSubmit = array('name' => 'btSubmit' , 'id' => 'btSubmit','class'=>'button' , 'value'=>'Guardar');

echo form_open("/tema4/Productos/guardar",$attributes);
echo form_label('*Nombre:', 'txNombre');
echo form_input($txNombre) . "<br>";
echo form_label('Marca:', 'txMarca');
echo form_input($txMarca) . "<br>";
echo form_label('*Precio:', 'txPrecio');
echo form_input($txPrecio) . "<br>";
echo form_label('*Cantidad:', 'txCantidad');
echo form_input($txCantidad) . "<br>";
echo form_label('*Categoría: ', 'selCategoria');
echo form_dropdown($selCategoria, $optCategoria, set_value('selCategoria',$producto[0]['FK_ID_CATEGORIA'] ?? '')). "<br><br>";
echo form_submit($btSubmit);
echo form_close();

if(!empty($producto)){
	$btEliminar = array('name' => 'btEliminar' , 'id' => 'btEliminar','class'=>'button' , 'value'=>'Eliminar producto');
	echo form_open ("/tema4/Productos/eliminar/".$producto[0]['PK_ID_PRODUCTO']);
	echo form_submit($btEliminar);
	echo form_close();
}

?>
</body>
</html>

