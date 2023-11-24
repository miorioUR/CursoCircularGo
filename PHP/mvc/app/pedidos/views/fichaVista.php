<?php session_start()?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ficha del Pedido <?= $_SESSION['referencia'] ?? ""?></title>
</head>
<body>
<p style="color:red">
<?php if(!empty($_SESSION['errores'])){
    foreach( $_SESSION['errores'] as $error ) : ?>
    <?= $error ?? ""?><br>
<?php  endforeach;};?>
</p>
<h1>Ficha del pedido <?= $_SESSION['referencia'] ?? ""?></h1>
<form action="accionFicha.php" method="post">
    <input type="hidden" id="ID" name="id_pedido" value="<?= $_SESSION['pedido']->getId()?>" />
    <label for="referencia">Referencia:</label>
    <input type="text" id="referencia" name="referencia" value="<?= $_SESSION['pedido']->getReferencia() ?? ""?>" placeholder="Referencia"><br>
    <label for="estado">Estado:</label>
    <select id="estado" name="estado">
        <option value="disponible"<?= $_SESSION['pedido']->getEstado()=="pendiente" ? "selected" : "" ?>>pendiente</option>
        <option value="asignado"<?= $_SESSION['pedido']->getEstado()=="asignado" ? "selected" : "" ?>>asignado</option>
        <option value="recogido"<?= $_SESSION['pedido']->getEstado()=="recogido" ? "selected" : "" ?>>recogido</option>
        <option value="entregado"<?= $_SESSION['pedido']->getEstado()=="entregado" ? "selected" : "" ?>>entregado</option>
    </select><br>
    <label for="direccion_recogida">Dirección de recogida:</label>
    <input type="text" id="direccion_recogida" name="direccion_recogida" value="<?= $_SESSION['pedido']->getDireccionRecogida() ?? ""?>"><br>
    <label for="hora_recogida">Hora de recogida:</label>
    <input type="text" id="hora_recogida" name="hora_recogida" value="<?= $_SESSION['hora_recogida'] ?? ""?>"><br>
    <label for="direccion_entrega">Dirección de entrega:</label>
    <input type="text" id="direccion_entrega" name="direccion_entrega" value="<?= $_SESSION['pedido']->getDireccionEntrega() ?? ""?>"><br>
    <label for="hora_entrega">Hora de entrega:</label>
    <input type="text" id="hora_entrega" name="hora_entrega" value="<?= $_SESSION['hora_entrega'] ?? ""?>"><br>
    <label for="distancia">Distancia:</label>
    <input type="text" id="distancia" name="distancia" value="<?= $_SESSION['distancia'] ?? ""?>"><br>
    <label for="rider">Rider:</label>
    <select id="rider" name="rider">
        <option value="-1" <?= empty($_SESSION['pedido']->getRider()) ? "selected" : "" ?>></option>
        <?php foreach( $_SESSION['riders'] as $rider ) : ?>
        <option value="<?= $rider->getId() ?>" <?= $_SESSION['pedido']->getRider()==$rider->getNombreCompleto() ? "selected" : "" ?>><?= $rider->getNombreCompleto() ?></option>
        <?php  endforeach;
        $_SESSION['errores'] = ""?>
    </select><br>
    <input type="submit" value="Guardar">
</form>
    <?php
        if($_SESSION['pedido']->getEstado()=="asignado")    { ?>
            <form action="ficha.php?ref=<?=$_SESSION['referencia']?>" method="post">
                <input type="submit" name="BtRecoger" value="Recoger pedido">
            </form>
    <?php }if($_SESSION['pedido']->getEstado()=="recogido")    { ?>
        <form action="ficha.php?ref=<?=$_SESSION['referencia']?>" method="post">
            <input type="submit" name="BtEntregar" value="Entregar pedido">
        </form>
    <?php } if(!empty($_SESSION['pedido']->getId())){?>
        <br>
        <form action="ficha.php?ref=<?=$_SESSION['referencia']?>" method="post">
            <input type="submit" name="BtEliminar" value="Eliminar pedido">
        </form>
    <?php } ?>

</body>
</html>