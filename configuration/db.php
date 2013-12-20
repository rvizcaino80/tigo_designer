<?php
/*
Configuration File
Rogers Vizcaino - rvizcaino@gmail.com
*/

//FREDYS MERCADO / ROGERS VIZCAINO

//DB CONFIGURATION ARRAY
if ($_SERVER['SERVER_NAME'] == 'localhost'){	
	$dbMysql = array("server"=>"127.0.0.1", "user"=>"root", "password"=>"", "name"=>"designer");
}else{	
	$dbMysql = array("server"=>"tunnel.pagodabox.com", "user"=>"lasandra", "password"=>"JQnpTFZi", "name"=>"zeus");
}