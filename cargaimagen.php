<?php
require 'db.php';

if(isset($_GET['id']) && isset($_GET['type'])){
    $idarch = $_GET['id']; 
	$typearch = $_GET['type'];
	header("Content-type: ".$typearch);
	
	$sql = "SELECT * FROM res_restaurante WHERE id = ?";
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->bindParam( 1, $idarch);
	$consulta->execute();
	
	$resultados = $consulta->fetchAll();
	
	echo $resultados[0]['imagen'];
}	
?>