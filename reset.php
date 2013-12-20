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

//ELIMINAMOS TODO LO DEL FORM
$sql = "DELETE FROM elements
	WHERE form = ?";

try {
	$sth = $dbh->prepare($sql);
	$sth->execute(array($form));
}catch(PDOException $e) {
    echo $e->getMessage();
}