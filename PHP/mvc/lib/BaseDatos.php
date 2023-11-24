<?php

include 'Pedido.php';
include 'Usuario.php';
class BaseDatos
{
    private $conexion_bd;
    function __construct()
    {
        $host = "172.17.0.2";
        $port = 3306;
        $user = "root";
        $password = "test1234";

        mysqli_report(MYSQLI_REPORT_ALL ^ MYSQLI_REPORT_STRICT);

        $this->conexion_bd = mysqli_connect($host, $user, $password, 'GESTION_PEDIDOS');
        if(!$this->conexion_bd){
            echo 'Error conectando a base de datos: ' . mysqli_connect_error();
            exit;
        }
    }

    function getPedidos(int $pagina,array $filtros, string $orden){
        $tam_Pagina = 3;
        $array_pedidos = array();
        $sql='SELECT P.*, CONCAT(COALESCE(U.NOMBRE,"")," ",COALESCE(U.APELLIDO_1,"")," ",COALESCE(U.APELLIDO_2,"")) as "RIDER" FROM PEDIDO P LEFT JOIN USUARIO U ON P.FK_ID_RIDER = U.PK_ID_USUARIO';

        //Aplicar filtros
        if(!empty($filtros)){
            $sql.= ' WHERE 1=1';
            foreach ($filtros as $campo => $valor) {
                if ($campo == 'RIDER'){
                    $sql .= ' AND CONCAT(COALESCE(U.NOMBRE,"")," ",COALESCE(U.APELLIDO_1,"")," ",COALESCE(U.APELLIDO_2,"")) LIKE "%' . $valor . '%"';
                }
                else{
                    $sql .= ' AND ' . $campo . ' LIKE "%' . $valor . '%"';
                }
            }
        }
        //Aplicar ordenación
        $sql.= ' ORDER BY ' . $orden;

        //Aplicar paginacion
        $sql.= ' LIMIT ' . $tam_Pagina . ' OFFSET ' . $tam_Pagina*($pagina-1);

        if($res_pedidos = mysqli_query($this->conexion_bd,$sql)){
            while($row_pedido = $res_pedidos->fetch_assoc()){

                $pedido = new Pedido();

                if($row_pedido["PK_ID_PEDIDO"]!=null){
                    $pedido->setId($row_pedido["PK_ID_PEDIDO"]);
                }
                if($row_pedido["REFERENCIA"]!=null){
                    $pedido->setReferencia($row_pedido["REFERENCIA"]);
                }
                if($row_pedido["ESTADO"]!=null){
                    $pedido->setEstado($row_pedido["ESTADO"]);
                }
                if($row_pedido["DIRECCION_RECOGIDA"]!=null){
                    $pedido->setDireccionRecogida($row_pedido["DIRECCION_RECOGIDA"]);
                }
                if($row_pedido["HORA_RECOGIDA"]!=null){
                    $dt = new DateTime();
                    $dt->setTimestamp(strtotime($row_pedido["HORA_RECOGIDA"]));
                    $pedido->setHoraRecogida($dt);
                }
                if($row_pedido["DIRECCION_ENTREGA"]!=null){
                    $pedido->setDireccionEntrega($row_pedido["DIRECCION_ENTREGA"]);
                }
                if($row_pedido["HORA_ENTREGA"]!=null){
                    $dt = new DateTime();
                    $dt->setTimestamp(strtotime($row_pedido["HORA_ENTREGA"]));
                    $pedido->setHoraEntrega($dt);
                }
                if($row_pedido["DISTANCIA"]!=null){
                    $pedido->setDistancia($row_pedido["DISTANCIA"]);
                }
                if($row_pedido["RIDER"]!=null){
                    $pedido->setRider($row_pedido["RIDER"]);
                }

                $array_pedidos[] = $pedido;
            }
        }else{
            printf("Error: " . PHP_EOL . $this->conexion_bd->error);
        }

        return $array_pedidos;
    }

    function getNumPedidos(array $filtros){
        $sql='SELECT COUNT(*) AS "NP" FROM PEDIDO P LEFT JOIN USUARIO U ON P.FK_ID_RIDER = U.PK_ID_USUARIO';
        if(!empty($filtros)){
            $sql.= ' WHERE 1=1';
            foreach ($filtros as $campo => $valor) {
                if ($campo == 'RIDER'){
                    $sql .= ' AND CONCAT(COALESCE(U.NOMBRE,"")," ",COALESCE(U.APELLIDO_1,"")," ",COALESCE(U.APELLIDO_2,"")) LIKE "%' . $valor . '%"';
                }
                else{
                    $sql .= ' AND ' . $campo . ' LIKE "%' . $valor . '%"';
                }
            }
        }
        if($res_pedidos = mysqli_query($this->conexion_bd,$sql)) {
            if ($row = $res_pedidos->fetch_assoc()) {
                return $row['NP'];
            }
        }
    }
    //Prueba primero con un filtro por referencia
    function getPedidoByRef(string $referencia){
        $sql = 'SELECT P.*, CONCAT(COALESCE(U.NOMBRE,"")," ",COALESCE(U.APELLIDO_1,"")," ",COALESCE(U.APELLIDO_2,"")) as "RIDER" FROM PEDIDO P LEFT JOIN USUARIO U ON P.FK_ID_RIDER = U.PK_ID_USUARIO WHERE P.REFERENCIA = "' . $referencia .'"';
        $pedido = new Pedido();
        if($res_pedido = mysqli_query($this->conexion_bd,$sql)){
            if($row_pedido = $res_pedido->fetch_assoc()){

                if($row_pedido["PK_ID_PEDIDO"]!=null){
                    $pedido->setId($row_pedido["PK_ID_PEDIDO"]);
                }
                if($row_pedido["REFERENCIA"]!=null){
                    $pedido->setReferencia($row_pedido["REFERENCIA"]);
                }
                if($row_pedido["ESTADO"]!=null){
                    $pedido->setEstado($row_pedido["ESTADO"]);
                }
                if($row_pedido["DIRECCION_RECOGIDA"]!=null){
                    $pedido->setDireccionRecogida($row_pedido["DIRECCION_RECOGIDA"]);
                }
                if($row_pedido["HORA_RECOGIDA"]!=null){
                    $dt = new DateTime();
                    $dt->setTimestamp(strtotime($row_pedido["HORA_RECOGIDA"]));
                    $pedido->setHoraRecogida($dt);
                }
                if($row_pedido["DIRECCION_ENTREGA"]!=null){
                    $pedido->setDireccionEntrega($row_pedido["DIRECCION_ENTREGA"]);
                }
                if($row_pedido["HORA_ENTREGA"]!=null){
                    $dt = new DateTime();
                    $dt->setTimestamp(strtotime($row_pedido["HORA_ENTREGA"]));
                    $pedido->setHoraEntrega($dt);
                }
                if($row_pedido["DISTANCIA"]!=null){
                    $pedido->setDistancia($row_pedido["DISTANCIA"]);
                }
                if($row_pedido["RIDER"]!=null){
                    $pedido->setRider($row_pedido["RIDER"]);
                }
            }
        }
        return $pedido;
    }

    function getRiders(){
        $array_riders = array();
        $sql='SELECT * FROM USUARIO U WHERE U.ROL = "rider"';

        if($res_riders = mysqli_query($this->conexion_bd,$sql)){
            while($row_rider = $res_riders->fetch_assoc()){
                $usuario = new Usuario();
                if($row_rider["PK_ID_USUARIO"]!=null){
                    $usuario->setId($row_rider["PK_ID_USUARIO"]);
                }
                if($row_rider["ROL"]!=null){
                    $usuario->setRol($row_rider["ROL"]);
                }
                if($row_rider["USER_NAME"]!=null){
                    $usuario->setUsername($row_rider["USER_NAME"]);
                }
                if($row_rider["USER_PASSWORD"]!=null){
                    $usuario->setPassword($row_rider["USER_PASSWORD"]);
                }
                if($row_rider["NOMBRE"]!=null){
                    $usuario->setNombre($row_rider["NOMBRE"]);
                }
                if($row_rider["APELLIDO_1"]!=null){
                    $usuario->setApellido1($row_rider["APELLIDO_1"]);
                }
                if($row_rider["APELLIDO_2"]!=null){
                    $usuario->setApellido2($row_rider["APELLIDO_2"]);
                }
                if($row_rider["CIUDAD"]!=null){
                    $usuario->setCiudad($row_rider["CIUDAD"]);
                }

                $array_riders[] = $usuario;
            }
        }
        return $array_riders;
    }
    function getRiderNameById(string $id){
        $sql='SELECT CONCAT(COALESCE(U.NOMBRE,"")," ",COALESCE(U.APELLIDO_1,"")," ",COALESCE(U.APELLIDO_2,"")) as "RIDER" FROM USUARIO U WHERE U.PK_ID_USUARIO = ' . $id;
        if($res_usuarios = mysqli_query($this->conexion_bd,$sql)) {
            if ($row = $res_usuarios->fetch_assoc()) {
                return $row['RIDER'];
            }
        }
    }
    function modificarPedido(string $id, Pedido $p, string $id_rider){
        $sql = "UPDATE PEDIDO SET";
        if(!empty($p->getReferencia())){
            $sql.= " REFERENCIA = '" . $p->getReferencia() . "'";
        }
            $sql.= " , ESTADO = '" . $p->getEstado() . "'";
        if(!empty($p->getDireccionRecogida())){
            $sql.= " , DIRECCION_RECOGIDA = '" . $p->getDireccionRecogida() . "'";
        }
        if(!empty($p->getHoraRecogida())){
            $sql.= " , HORA_RECOGIDA = CAST('" . $p->getHoraRecogida()->format('Y-m-d H:i:s') . "' AS DATETIME)";
        }
        else{
            $sql.= " , HORA_RECOGIDA = NULL";
        }
        if(!empty($p->getDireccionEntrega())){
            $sql.= " , DIRECCION_ENTREGA = '" . $p->getDireccionEntrega() . "'";
        }
        if(!empty($p->getHoraEntrega())){
            $sql.= " , HORA_ENTREGA = CAST('" . $p->getHoraEntrega()->format('Y-m-d H:i:s') . "' AS DATETIME)";
        }
        else{
            $sql.= " , HORA_ENTREGA = NULL";
        }
            $sql.= " , DISTANCIA = '" . floatval($p->getDistancia()) . "'";
        if($id_rider!="-1"){
            $sql.= " , FK_ID_RIDER = " . $id_rider ;
        }
        else{
            $sql.= " , FK_ID_RIDER = NULL";
        }

        $sql.= " WHERE PK_ID_PEDIDO = " . $id;

        $result = mysqli_query($this->conexion_bd,$sql);

        if($result->num_rows > 0){
            return "registro actualizado";
        }
        else{
            return "ningun registro modificado";
        }
    }
    function addPedido(Pedido $p, int $idrider){
        $pstmt = mysqli_prepare($this->conexion_bd, "INSERT INTO PEDIDO (REFERENCIA,ESTADO,DIRECCION_RECOGIDA,HORA_RECOGIDA,DIRECCION_ENTREGA,HORA_ENTREGA,DISTANCIA,FK_ID_RIDER) VALUES (?,?,?,?,?,?,?,?)");
        $referencia = $p->getReferencia();
        $estado = $p->getEstado() ?? "pendiente";
        $direccionRecogida = $p->getDireccionRecogida() ?? "Sin direccion de recogida";
        $horaRecogida = $p->getHoraRecogida() ?? null;
        $direccionEntrega = $p->getDireccionEntrega() ?? "Sin direccion de entrega";
        $horaEntrega = $p->getHoraEntrega() ?? null;
        $distancia = $p->getDistancia() ?? null;
        $idrider==-1 ? $rider = null : $rider = $idrider;
        $pstmt->bind_param('sssssssi', $referencia,$estado,$direccionRecogida,$horaRecogida, $direccionEntrega,$horaEntrega,$distancia,$rider);
        $pstmt->execute();
    }

    function deletePedido(int $idpedido){
        $pstmt = mysqli_prepare($this->conexion_bd, "DELETE FROM PEDIDO WHERE PK_ID_PEDIDO = ?");
        $pstmt->bind_param('i', $idpedido);
        $pstmt->execute();
    }

}

//Falta unificar las comprobaciones de nulo en una funcion nullcheckPedido