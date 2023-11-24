<?php
//defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Ejercicio 1 tema 3</title>
</head>
<body>

<h2>Resultados ejercicios t3</h2>
<h3>Ejercicio 1</h3>
<h4>Todos los productos</h4>
<ul>
	<?php foreach ($all_productos as $producto){?>
		<li><?= $producto["NOMBRE"] ?></li>
	<?php }?>
</ul>
<h4>Todas las categorias</h4>
<ul>
	<?php foreach ($all_categorias as $categoria){?>
	<li><?= $categoria["NOMBRE"] ?></li>
	<?php }?>
</ul>
<h4>Todos los productos ordenados por nombre</h4>
<ul>
	<?php foreach ($all_productos_by_nombre as $producto){?>
	<li><?= $producto["MARCA"] ?></li>
	<?php }?>
</ul>
<h4>Todas las zapatillas</h4>
<ul>
	<?php foreach ($all_zapatillas as $zapatilla){?>
	<li><?= $zapatilla["NOMBRE"] ?></li>
	<?php }?>
</ul>
<h4>Todos los productos que empiecen por zapa</h4>
<ul>
	<?php foreach ($zapa as $row_zapa){?>
	<li><?= $row_zapa["NOMBRE"] ?></li>
	<?php }?>
</ul>
<h4>Precio medio de los productos</h4>
<p><?= $avg_precio[0]["PRECIO"] ?></p>
<h4>Numero de productos en cada categoria</h4>
<ul>
	<?php foreach ($num_productos_by_categoria as $quantity){?>
	<li> La categoria <?= $quantity["FK_ID_CATEGORIA"] ?> tiene <?= $quantity["num"] ?> producto(s)</li>
	<?php }?>
</ul>
<h4>Categorias con mas de 10 productos</h4>
<ul>
	<?php foreach ($categorias_comunes as $categoria){?>
	<li><?= $categoria["FK_ID_CATEGORIA"] ?></li>
	<?php } ?>
</ul>
</body>
</html>
