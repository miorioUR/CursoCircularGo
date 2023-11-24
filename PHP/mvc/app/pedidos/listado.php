<?php

include_once '../../lib/BaseDatos.php';
include_once '../../lib/Pedido.php';

session_start();

$bd = new BaseDatos();
$queries = array();
parse_str($_SERVER['QUERY_STRING'], $queries);
$pagina = $queries['p'];


if(empty($queries['ord'])){
    $_SESSION['orden'] = "REFERENCIA";
}
else{
    $_SESSION['orden'] = $queries['ord'];
}


$filtros = array();
//Filtros
if(!empty($_POST)){

    $referencia = $_POST['referencia'];
    $estado = $_POST['estado'];
    $direccion_recogida = $_POST['direccion_recogida'];
    $direccion_entrega = $_POST['direccion_entrega'];
    $rider = $_POST['rider'];

    //es mejor poner if en el metodo getpedido para poner el p. pero esto no hace comprobaciones de ifs
    $filtros['P.REFERENCIA'] = $referencia;
    $filtros['P.ESTADO'] = $estado;
    $filtros['P.DIRECCION_RECOGIDA'] = $direccion_recogida;
    $filtros['P.DIRECCION_ENTREGA'] = $direccion_entrega;
    $filtros['RIDER'] = $rider;

    $_SESSION['filtros']=$filtros;

}
else if(!empty($_SESSION['filtros'])){
    $filtros = $_SESSION['filtros'];
}

$_SESSION['pagina']=$pagina;

$pedidos = $bd->getPedidos($pagina,$filtros,$_SESSION['orden']);
foreach ($pedidos as $pedido){
    if($pedido->getEstado() == null){
        $pedido->setEstado("-");
    }
    if($pedido->getDireccionRecogida() == null){
        $pedido->setDireccionRecogida("-");
    }
    if($pedido->getDireccionEntrega() == null){
        $pedido->setDireccionEntrega("-");
    }
    if($pedido->getRider() == null){
        $pedido->setRider("-");
    }
}
$_SESSION['pedidos']=$pedidos;

$numpedidos = $bd->getNumPedidos($filtros);
$_SESSION['numpedidos'] = $numpedidos;
$numpaginas = intdiv($numpedidos,3);
if($numpedidos%3 > 0){$numpaginas++;}
$_SESSION['numpaginas'] = $numpaginas;

require_once './views/listadoVista.php';

/*
 * NULLCHECK CONTROLADOR
if($pedido->getHoraEntrega() == null){
    $pedido->setHoraEntregaText("-");
} else{
    $horaEntrega = $pedido->getHoraRecogida();
    $tsEntrega=$horaEntrega->getTimestamp();
    $pedido->setHoraRecogidaText(date('Y-m-d H:i:s', $tsEntrega));
}
*/

?>


