<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();


if(isset($_SESSION['token']))
{ 

	require_once('conf.php');
	require_once('wp-db_php5_4.php');
	
	global $wpdb;  


	$endpoint = isset($_POST['campos']['endpoints'])? $wpdb->_real_escape($_POST['campos']['endpoints']):'';
	$token = isset($_POST['campos']['num'])? $wpdb->_real_escape($_POST['campos']['num']):'';


	if($_SESSION['token']==$token){
	
		$consultaExiste= $wpdb->prepare("SELECT * FROM `registro` WHERE `endPoint` = %s limit 1",$endpoint);
		$resultadoExiste=$wpdb->query($consultaExiste);

		if($resultadoExiste){
			//$resultado['result'] = "existe";
			$consultaUpdateRegistro= $wpdb->prepare("UPDATE `registro` SET  `subscrito`=%s  WHERE `endPoint` = %s limit 1","false", $endpoint);
			$resultUpdateRegistro=$wpdb->query($consultaUpdateRegistro);
			if($resultUpdateRegistro){
				$resultado['result'] = "ok";
			}else{
				$resultado['result'] = "no";
			}

		}else{
			$resultado['result'] = "no";
		}
	}else{
		$resultado['result'] = "no";
	}

}
else{
	$resultado['result'] = "no";
}

echo json_encode($resultado);
die();

?>	