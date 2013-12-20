<?php
require 'configuration/db.php';

try {
    # MySQL with PDO_MYSQL
    $dbh = new PDO("mysql:host=".$dbMysql['server'].";dbname=".$dbMysql['name'], $dbMysql['user'], $dbMysql['password']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}
catch(PDOException $e) {
    echo $e->getMessage();
}

//RECOVER DATA
$form = $_POST['form']; 
$type = $_POST['type']; 
$key = $_POST['key']; 

if(!isset($_POST['parent'])){
	$parent = "root"; 
}else{
	$parent = $_POST['parent']; 
}

$label = $_POST['label']; 
$position = $_POST['position']; 

$sql = "INSERT INTO elements
	(form, type, `key`, parent, label, position) 
	values (?, ?, ?, ?, ?, ?)";

try {
	$sth = $dbh->prepare($sql);
	$sth->execute(array($form, $type, $key, $parent, $label, $position));
}catch(PDOException $e) {
    echo $e->getMessage();
}