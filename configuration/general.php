<?php
/*
Configuration File
Rogers Vizcaino - rvizcaino@gmail.com
*/

date_default_timezone_set("America/Bogota");
define("SITE_NAME", "amoree");
define("SITE_DESC", "Amoreé");

if ( $server = $_SERVER['SERVER_NAME'] == 'localhost' ) {
    define("ABS_PATH", "http://localhost/amoree/");
    define("REAL_PATH", "d:/rogers/amoree/");
    //define("REAL_PATH", "/Volumes/Data/Users/rogers/Sites/amoree/");
    define("REL_PATH", "/amoree/");
}else{
    define("ABS_PATH", "http://amoree.com.co/");
    define("REAL_PATH", "/var/www/");
    define("REL_PATH", "/");
}