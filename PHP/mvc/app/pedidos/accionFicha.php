<?php

include_once '../../lib/BaseDatos.php';
include_once '../../lib/Pedido.php';
session_start();

$bd = new BaseDatos();
$pedido = $_SESSION['pedido'];
$id = $_POST['id_pedido'];
$errores = array();


if(!empty($_POST)){
    $newReferencia = $_POST['referencia'];
    if($bd->getPedidoByRef($newReferencia)->getId()!=null && $bd->getPedidoByRef($newReferencia)->getId()!=$id){
        $errores[] = "La nueva referencia no es válida" . PHP_EOL;
    }
    else{
        $_SESSION['referencia']=$newReferencia;
    }
    if(empty($_POST['direccion_recogida']) || empty($_POST['direccion_entrega'])){
        $errores[] = "Un pedido requiere direccion de recogida y direccion de entrega" . PHP_EOL;
    }
    switch ($_POST['estado']){
        case "disponible":
            break;
        case "asignado":
            if(empty($_POST['rider'])){
                $errores[] = "Un pedido asignado, recogido o entregado requiere un rider asignado" . PHP_EOL;
            }
            break;
        case "recogido":
            if(empty($_POST['hora_recogida'])){
                $errores[] = "Un pedido recogido o entregado requiere una hora de recogida" . PHP_EOL;
            }
            else if(date($_POST['hora_recogida'])>date('Y-m-d H:i:s')){
                //No detecta bien el concepto de fecha futura
                //$errores[] = "La hora de recogida no puede ser una fecha futura" . PHP_EOL;
            }
            if(empty($_POST['rider'])){
                $errores[] = "Un pedido asignado, recogido o entregado requiere un rider asignado" . PHP_EOL;
            }
            break;
        case "entregado":
            if(empty($_POST['hora_recogida'])){
                $errores[] = "Un pedido recogido o entregado requiere una hora de recogida" . PHP_EOL;
            }
            else if(date($_POST['hora_recogida'])>date('Y-m-d H:i:s')){
                //No detecta bien el concepto de fecha futura
                //$errores[] = "La hora de recogida no puede ser una fecha futura" . PHP_EOL;
            }
            if(empty($_POST['hora_entrega'])){
                $errores[] = "Un pedido entregado requiere una hora de entrega" . PHP_EOL;
            }
            else if(date($_POST['hora_entrega'])>date('Y-m-d H:i:s')){
                //No detecta bien el concepto de fecha futura
                //$errores[] = "La hora de entrega no puede ser una fecha futura" . PHP_EOL;
            }
            if(empty($_POST['rider'])){
                $errores[] = "Un pedido asignado, recogido o entregado requiere un rider asignado" . PHP_EOL;
            }
            break;
        default:
            $errores[] = "El estado del pedido no es válido" . PHP_EOL;
            break;
    }

    $pedido->setReferencia($_POST['referencia']);
    $pedido->setDireccionRecogida($_POST['direccion_recogida']);
    if(!empty($_POST['hora_recogida'])){
        $date_recogida = new DateTime();
        $date_recogida->setTimestamp(strtotime($_POST['hora_recogida']));
        $pedido->setHoraRecogida($date_recogida);
    }
    else{
        $pedido->setHoraRecogida(null);
    }

    $pedido->setDireccionEntrega($_POST['direccion_entrega']);
    if(!empty($_POST['hora_entrega'])){
        $date_entrega = new DateTime();
        $date_entrega->setTimestamp(strtotime($_POST['hora_entrega']));
        $pedido->setHoraEntrega($date_entrega);
    }
    $pedido->setEstado($_POST['estado']);
    if (is_numeric($_POST['distancia'])) {
        $pedido->setDistancia(floatval($_POST['distancia']));
    }
    if($_POST['rider']>0){
        $pedido->setRider($bd->getRiderNameById($_POST['rider']));
    }
}
$_SESSION['errores']=$errores;
!empty($_POST['rider']) ? $id_rider=$_POST['rider'] : $id_rider=-1;
if(empty($errores)){
    if(!empty($_POST['id_pedido'])){
        $id_pedido = $_POST['id_pedido'];
        $bd->modificarPedido($id_pedido,$pedido,$id_rider);
    }
    else{
        $bd->addPedido($pedido,$id_rider);
    }
    //header("Location: ./listado.php?p=" . $_SESSION['pagina'], 301); Por que la variable session[pagina] pierde su valor al hacer clic en un <a>?
    header("Location: ./listado.php?p=1", 301);
    exit();
}else{
    header("Location: ./ficha.php?ref=" . ($_SESSION['referencia'] ?? ""), 301);
    exit();
}


?>