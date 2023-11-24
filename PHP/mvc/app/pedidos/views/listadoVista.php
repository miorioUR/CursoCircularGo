<?php session_start()?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Listado de Pedidos</title>
</head>
<body>
    <form action="listado.php?p=<?= $_SESSION['pagina']?>&ord=<?= $_SESSION['orden']?>" method="post">
        <label for="referencia">Referencia:</label>
        <input type="text" id="referencia" name="referencia" value="<?= $_SESSION['filtros']['P.REFERENCIA'] ?? ""?>" placeholder="Referencia">
        <label for="estado">Estado:</label>
        <select id="estado" name="estado">
            <<option value="">Cualquiera</option>
            <?php if(is_null($_SESSION['filtros']['P.ESTADO'])){ ?>
            <option value="pendiente">pendiente</option>
            <option value="asignado">asignado</option>
            <option value="recogido">recogido</option>
            <option value="entregado">entregado</option>
            <?php }else{?>
            <option value="pendiente" <?= $_SESSION['filtros']['P.ESTADO']=="pendiente" ? "selected" : "" ?> >pendiente</option>
            <option value="asignado" <?= $_SESSION['filtros']['P.ESTADO']=="asignado" ? "selected" : "" ?> >asignado</option>
            <option value="recogido" <?= $_SESSION['filtros']['P.ESTADO']=="recogido" ? "selected" : "" ?> >recogido</option>
            <option value="entregado" <?= $_SESSION['filtros']['P.ESTADO']=="entregado" ? "selected" : "" ?> >entregado</option>
            <?php }?>
        </select>
        <label for="direccion_recogida">Dirección de recogida:</label>
        <input type="text" id="direccion_recogida" name="direccion_recogida" value="<?= $_SESSION['filtros']['P.DIRECCION_RECOGIDA'] ?? ""?>" placeholder="Dirección de recogida">
        <label for="direccion_entrega">Dirección de entrega:</label>
        <input type="text" id="direccion_entrega" name="direccion_entrega" value="<?= $_SESSION['filtros']['P.DIRECCION_ENTREGA'] ?? ""?>" placeholder="Dirección de entrega">
        <label for="rider">Rider:</label>
        <input type="text" id="rider" name="rider" value="<?= $_SESSION['filtros']['RIDER'] ?? ""?>" placeholder="Rider">
        <input type="submit" value="Filtrar">
    </form>
    <br><br>
    <form action="ficha.php?ref=" method="get">
        <input type="submit" value="Nuevo pedido">
    </form>
    <h1>Listado de pedidos</h1>
    <table>
        <tr>
            <th><a href="listado.php?p=<?=$_SESSION['pagina']?>&ord=REFERENCIA">Referencia</a></th>
            <th><a href="listado.php?p=<?=$_SESSION['pagina']?>&ord=ESTADO">Estado</a></th>
            <th><a href="listado.php?p=<?=$_SESSION['pagina']?>&ord=DIRECCION_RECOGIDA">Direccion recogida</a></th>
            <th><a href="listado.php?p=<?=$_SESSION['pagina']?>&ord=HORA_RECOGIDA">Hora recogida</a></th>
            <th><a href="listado.php?p=<?=$_SESSION['pagina']?>&ord=DIRECCION_ENTREGA">Direccion entrega</a></th>
            <th><a href="listado.php?p=<?=$_SESSION['pagina']?>&ord=HORA_ENTREGA">Hora entrega</a></th>
            <th><a href="listado.php?p=<?=$_SESSION['pagina']?>&ord=DISTANCIA">Distancia</a></th>
            <th><a href="listado.php?p=<?=$_SESSION['pagina']?>&ord=RIDER">Rider</a></th>
        </tr>
        <?php foreach( $_SESSION['pedidos'] as $pedido ) : ?>
        <tr>
            <td><a href="ficha.php?ref=<?= $pedido->getReferencia()?>"><?= $pedido->getReferencia()?></a></td>
            <td><?= $pedido->getEstado()?></td>
            <td><?= $pedido->getDireccionRecogida()?></td>
            <td><?= !is_null($pedido->getHoraRecogida()) ? $pedido->getHoraRecogida()->format('Y-m-d H:i:s') : "-"?></td>
            <td><?= $pedido->getDireccionEntrega()?></td>
            <td><?= !is_null($pedido->getHoraEntrega()) ? $pedido->getHoraEntrega()->format('Y-m-d H:i:s') : "-"?></td>
            <td><?= $pedido->getDistancia() ?? "-"?></td>
            <td><?= $pedido->getRider()?></td>
        </tr>
        <?php  endforeach;?>
    </table>
    <p>Mostrando artículos <?=($_SESSION['pagina']-1)*3 +1?>-<?=($_SESSION['pagina'])*3 ?> de <?=$_SESSION['numpedidos']?></p>
    <?php if($_SESSION['pagina'] > 1){ ?>
        <a href="?p=1&ord=<?=$_SESSION['orden']?>">Primera -</a>
    <?php }
    $rango=2;
    for($i = max(1,$_SESSION['pagina'] - $rango);$i <= min($_SESSION['pagina'] + $rango, $_SESSION['numpaginas']); $i++){ ?>
        <a href="?p=<?=$i?>&ord=<?=$_SESSION['orden']?>"> <?=$i?> </a>
    <?php } if($_SESSION['pagina'] < $_SESSION['numpaginas']){?>
    <a href="?p=<?=$_SESSION['numpaginas']?>&ord=<?=$_SESSION['orden']?>">- Ultima</a>
    <?php } ?>
</body>
</html>