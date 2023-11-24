<?php

session_start();
include_once '../../lib/BaseDatos.php';
include_once '../../lib/Pedido.php';
include_once '../../lib/Usuario.php';

$bd = new BaseDatos();
$queries = array();
parse_str($_SERVER['QUERY_STRING'], $queries);


if(empty($queries['ref'])){

    $pedido = new Pedido();
    $_SESSION['riders'] = $bd->getRiders();
}
else {

    $referencia = $queries['ref'];
    $pedido = $bd->getPedidoByRef($referencia);
    $_SESSION['referencia'] = $referencia;

}
if(!empty($_POST['BtRecoger'])){
    $pedido->RecogerPedido();
    $horarecogidastring = $pedido->getHoraRecogida();
}
else{
    !is_null($pedido->getHoraRecogida()) ? $horarecogidastring = $pedido->getHoraRecogida()->format('Y-m-d H:i:s') : $horarecogidastring = "";
}
if(!empty($_POST['BtEntregar'])){
    $pedido->EntregarPedido();
    $horaentregastring = $pedido->getHoraEntrega();
}
else{
    !is_null($pedido->getHoraEntrega()) ? $horaentregastring = $pedido->getHoraEntrega()->format('Y-m-d H:i:s') : $horaentregastring = "";
}

if(!empty($_POST['BtEliminar'])){
    $bd->deletePedido($pedido->getId());
    //Falta un flag para un alert javascript
    header("Location: ./listado.php?p=1", 301);
    exit();
}

    $distanciastring = "" . $pedido->getDistancia() ?? "";


    $_SESSION['pedido'] = $pedido;
    $_SESSION['hora_recogida'] = $horarecogidastring;
    $_SESSION['hora_entrega'] = $horaentregastring;
    $_SESSION['distancia'] = $distanciastring;
    $_SESSION['riders'] = $bd->getRiders();

require_once './views/fichaVista.php';

?>
