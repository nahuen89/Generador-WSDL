<?php
	include './config/bd.php';
	//client side test to use for wsdl.class.php
	$url = "http://localhost/FI/TP_1/wsdl_test.php?wsdl";
    $client = new SoapClient($url);
    $client->decode_utf8 = false;
    $user = "Ramon";
    $result = $client->testurl($user);
    echo($result);
	
	echo "<br>";
	
	// probando la segunda funcion
	$url = "http://localhost/FI/TP_1/wsdl_test.php?wsdl";
    $client = new SoapClient($url);
    $client->decode_utf8 = false;
    $user = "martin";
	$edad = 15;
    $result = $client->test2($user,$edad);
    echo($result);

	echo "<br>";
	
	echo '<br><HR width=50% align="center">';
	
	// *** Probando la funcion de creada por nosotros para verificar si podemos enviar arrays
	$url = "http://localhost/FI/TP_1/wsdl_test.php?wsdl";
    $client = new SoapClient($url);
    $client->decode_utf8 = false;
	$nomb = "Intel core I7";
    $result = $client->BuscarProducto($nomb);
	echo '<br><HR width=50% align="center">';
	
    echo file_get_contents($result);
	echo "<br>";
	echo '<br><HR width=50% align="center">';
	print_r(array_values($result))
	
	
?>