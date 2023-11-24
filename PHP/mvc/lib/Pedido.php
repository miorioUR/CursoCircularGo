<?php

//date_default_timezone_set("Europe/Madrid");
/*
 *      $dt = new DateTime();
        $dt->setTimestamp(strtotime($row_pedido["HORA_RECOGIDA"]));
        $pedido->setHoraRecogida($dt);*/
class Pedido
{
    //Campos
    private $id;
    private $referencia;
    private $estado = "disponible";
    private $direccion_recogida;
    private $hora_recogida;
    private $direccion_entrega;
    private $hora_entrega;
    private $distancia;
    private $rider;

    //Constructor

    /*
    function __construct($referencia,$direccion_recogida,$direccion_entrega)
    {
        $this->referencia=$referencia;
        $this->direccion_recogida=$direccion_recogida;
        $this->direccion_entrega=$direccion_entrega;
    }*/

    //Get/Set
    function getId(){
        return $this->id;
    }
    function setId(int $id){
        $this->id=$id;
    }
    function getReferencia(){
        return $this->referencia;
    }
    function setReferencia(string $referencia){
        $this->referencia=$referencia;
    }
    function getDireccionRecogida(){
        return $this->direccion_recogida;
    }
    function setDireccionRecogida(string $direccion_recogida){
        $this->direccion_recogida=$direccion_recogida;
    }
    function getHoraRecogida(){
        return $this->hora_recogida;
    }
    function setHoraRecogida($hora_recogida){
        if ($hora_recogida instanceof DateTime || $hora_recogida === null) {
            $this->hora_recogida=$hora_recogida;
        }
    }
    function getDireccionEntrega(){
        return $this->direccion_entrega;
    }
    function setDireccionEntrega(string $direccion_entrega){
        $this->direccion_entrega=$direccion_entrega;
    }
    function getHoraEntrega(){
        return $this->hora_entrega;
    }
    function setHoraEntrega(DateTime $hora_entrega){
        $this->hora_entrega=$hora_entrega;
    }
    function getDistancia(){
        return $this->distancia;
    }
    function setDistancia(float $distancia){
        $this->distancia=$distancia;
    }
    function getRider(){
        return $this->rider;
    }
    function setRider(string $rider){
        $this->rider=$rider;
    }

    //*********************************************************
    //Cambios de estado

    function getEstado(){
        return $this->estado;
    }
    function setEstado(string $estado){
        $this->estado=$estado;
    }
    function AsignarPedido(String $rider){
        $this->estado = "asignado";
        $this->rider = $rider;
    }
    function RecogerPedido(){
        $this->estado = "recogido";
        $this->hora_recogida = date('Y/m/d H:i:s');
    }
    /*function RecogerPedidoHora(DateTime $d){
        $this->estado = "recogido";
        $this->hora_recogida = $d;
    }*/
    function EntregarPedido(){
        $this->estado = "entregado";
        $this->hora_entrega = date('Y/m/d H:i:s');
    }
    /*function EntregarPedidoHora(DateTime $d){
        $this->estado = "entregado";
        $this->hora_entrega = $d;
    }*/

    //***************************************
    //Calcular distancia

    function calcularDistancia(){
        /*NO FUNCIONA, CON HTTPS NECESITA UN PLAN DE PAGO, CON HTTP DEVUELVE 400 BAD REQUEST*/

        $buildQueryRecogida = http_build_query([
            'access_key' => '5c88b3dd58032f0a4f7dff2c88802c8d',
            'query' => $this->direccion_recogida
        ]);
        $buildQueryEntrega = http_build_query([
            'access_key' => '5c88b3dd58032f0a4f7dff2c88802c8d',
            'query' => $this->direccion_entrega
        ]);

        $chRecogida = curl_init(sprintf('%s?%s', 'https://api.positionstack.com/v1/forward', $buildQueryRecogida));
        curl_setopt($chRecogida, CURLOPT_RETURNTRANSFER, true);

        $responseRecogida = curl_exec($chRecogida);
        curl_close($chRecogida);
        $resultRecogida = json_decode($responseRecogida, true);

        $chEntrega = curl_init(sprintf('%s?%s', 'https://api.positionstack.com/v1/forward', $buildQueryEntrega));
        curl_setopt($chEntrega, CURLOPT_RETURNTRANSFER, true);

        $responseEntrega = curl_exec($chEntrega);
        curl_close($chEntrega);
        $resultEntrega = json_decode($responseEntrega, true);

        $latitudDiff= abs($resultEntrega['data']['results'][0] - $resultEntrega['data']['results'][0]);
        $longitudDiff = abs($resultEntrega['data']['results'][1] - $resultEntrega['data']['results'][1]);

        //La distancia en km es la diagonal de las diferencias convertidas a km.
        //No es exacta por la diferencia de longitudes. Practicamente imperceptible.
        //Si quisieramos hacer repartos a escala global es mejor importar una funcion matematica
        $distancia = sqrt(($latitudDiff*$latitudDiff*110.574)+($longitudDiff*$longitudDiff*111.320*cos($resultEntrega['data']['results'][0])));
    }
}
?>