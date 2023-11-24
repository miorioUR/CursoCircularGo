<?php

//Deberia rol ser una enumeracion?
class Usuario
{
    //Campos

    private $id;
    private $rol;
    private $username;
    private $password;
    private $nombre;
    private $apellido1;
    private $apellido2;
    private $ciudad;

    //Get/set

    function getId(){
        return $this->id;
    }
    function setId(string $id){
        $this->id=$id;
    }
    function getRol(){
        return $this->rol;
    }
    function setRol(string $rol){
        $this->rol=$rol;
    }
    function getUsername(){
        return $this->username;
    }
    function setUsername(string $username){
        $this->username=$username;
    }
    function setPassword(string $password){
        $this->password=$password;
    }
    function getNombre(){
        return $this->nombre;
    }
    function setNombre(string $nombre){
        $this->nombre=$nombre;
    }
    function getApellido1(){
        return $this->apellido1;
    }
    function setApellido1(string $apellido1){
        $this->apellido1=$apellido1;
    }
    function getApellido2(){
        return $this->apellido2;
    }
    function setApellido2(string $apellido2){
        $this->apellido2=$apellido2;
    }
    function getCiudad(){
        return $this->ciudad;
    }
    function setCiudad(string $ciudad){
        $this->ciudad=$ciudad;
    }

    //Otros metodos

    function getNombreCompleto(){
        return $this->nombre . " " . $this->apellido1 . " " . $this->apellido2;
    }
}
?>
