<?php	


	$server_name = $_SERVER["SERVER_NAME"];
	

	if(strrpos($server_name, "globaldigital.cl")!==FALSE){
		
		///PRODUCCION

		define('SITE_URL','https://lab.globaldigital.cl');	
		define('DB_USER','globallab'); 
		define('DB_PASSWORD','lY3gyoaKhWwEz9A'); 
		define('DB_NAME','globallab'); 


	}
	
	
	define('DB_HOST','localhost'); 		
	define('PREFIX','');	
	define('WP_DEBUG','true');



	
if (!function_exists('is_multisite')) {
	function is_multisite(){
		return false;
	} 
}

if (!function_exists('wp_die')) {
	function wp_die(){
		return true;
	}	
}


?>