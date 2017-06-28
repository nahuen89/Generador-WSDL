<?php

	//tested using SoapUI version 4.5.2
	//tested eith php wsdl_client.php
	//tested eith c# visual studio 2010
	//complex data type not supported yet.
	//only support integer string and float data type.
	
	// ***   Incluimos la clase wsdl.class 
	include './config/bd.php';
	require_once "./wsdl.class.php";	//just include service class to use it
	
	// ***  creamos nuestras funciones
	
	//implement function body 
	function testurl($data){	
		return 'Como te va '.$data.'! Todo bien jaja? ';
	}
	//implement function body 
	function test2($name,$age){
		if(!isset($age)){
			return 'falta la edad';
		}
		if(is_numeric($age)){
			return 'Hola ' . $name . " me dijeron que tenes $age años"; 
		}else{
			return 'datos erroneos en edad';
		}
	}
	// prueba de array agregada por nosotros 
	
	function BuscarProducto($nomb) {
    try {
    	$base = new BaseDatos;
    	$base ->Iniciar();
        $query = "SELECT id_producto, nombre, precio, descuento, stock FROM producto WHERE nombre = '$nomb'";
        $base->Select($query);
        $result = $base->registro();
        $id = $result['id_producto'];
        if ($id > 0) {
        	$nombre = $result['nombre'];
        	$precio = $result['precio'];
        	$descuento = $result['descuento'];
			$stock = $result['stock'];
        	
        	$respuesta = "$id, $nombre, $precio, $descuento, $stock";
            
        } 
        else {
            $respuesta = "El producto $nombre no existe en nuestra base de datos verifique si coloco bien el nombre";
        }
        
        //$respuesta = 'si, es un string';
        return $respuesta;
    } 
    catch (Exception $catch) {
        return $catch->getMessage();
    }
}
	
	
	
	$e=new SSoap('Sourena');	//your service name here as argument
	
	$e->register(
				'testurl',	//function name of the service
				array(		//input arguments of the function as name=>type 
					'username'=>'string',
				),
				array(		//output arguments of the function as name=>type
					'return'=>'str'
				),
				'this function suppose to be a test'
	);
		//define another service
	$e->register(
				'test2',
				array(
					'username'=>'string',
					'edad'=>'float'
				),
				array(
					'return'=>'str'
				),
				'this is another test'
	);
	
	// *** definiendo el servicio creado por nosotros
	$e->register(
				'BuscarProducto', // aca va el nombre de la funcion a la cual le queremos agregar el WSDL
				array(
					'nomb'=>'string' // lugo colocamos los datos de estrada para la funcion
				),
				array(
					'return'=>'str' // y vamos a recibir
				),
				'enviamos el nombre del producto y recivimos toda la informacion de este si existe'  // podemos poner una descripcion de lo que vamos a realisar en la funcion
	);
	
	
	
	
	$e->handle();		//call this method to start service handle
?>