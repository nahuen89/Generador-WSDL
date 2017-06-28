<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of bd
 *
 * 
 */
class bd extends mysqli {
    private $host, $user, $pass, $basedatos;
    
    function __construct() {
        $this->host = "localhost";
        $this->user = "root";
        $this->pass = "";
        $this->basedatos = "Productos";
        
        parent::__construct($this->host, $this->user, $this->pass, $this->basedatos);
        if ($this->connect_error) {
            die('Error de Conexión (' . $this->connect_errno . ') '
                . $this->connect_error);
        }
    }
    
    function filtrar($variable){
        return $this->real_escape_string($variable); 
    }
    
    function myquery($sql){
        return parent::query($sql)->fetch_assoc();
    }
    
    
}

/*$mybd = new bd();
$sql = "select * from personas where nombre LIKE '%?%'";
$bind = array("s", "prueba");
print_r($mybd->filtrar($sql, $bind));*/