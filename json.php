<?php
function prettyPrint( $json )
{
    $result = '';
    $level = 0;
    $prev_char = '';
    $in_quotes = false;
    $ends_line_level = NULL;
    $json_length = strlen( $json );

    for( $i = 0; $i < $json_length; $i++ ) {
        $char = $json[$i];
        $new_line_level = NULL;
        $post = "";
        if( $ends_line_level !== NULL ) {
            $new_line_level = $ends_line_level;
            $ends_line_level = NULL;
        }
        if( $char === '"' && $prev_char != '\\' ) {
            $in_quotes = !$in_quotes;
        } else if( ! $in_quotes ) {
            switch( $char ) {
                case '}': case ']':
                    $level--;
                    $ends_line_level = NULL;
                    $new_line_level = $level;
                    break;

                case '{': case '[':
                    $level++;
                case ',':
                    $ends_line_level = $level;
                    break;

                case ':':
                    $post = " ";
                    break;

                case " ": case "\t": case "\n": case "\r":
                    $char = "";
                    $ends_line_level = $new_line_level;
                    $new_line_level = NULL;
                    break;
            }
        }
        if( $new_line_level !== NULL ) {
            $result .= "\n".str_repeat( "\t", $new_line_level );
        }
        $result .= $char.$post;
        $prev_char = $char;
    }

    return $result;
}

require 'configuration/db.php';

try {
    # MySQL with PDO_MYSQL
    $dbh = new PDO("mysql:host=".$dbMysql['server'].";dbname=".$dbMysql['name'], $dbMysql['user'], $dbMysql['password']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}
catch(PDOException $e) {
    echo $e->getMessage();
}

$form = $_GET['form'];
$sql = "SELECT *
	FROM forms
	WHERE id = ?";

try {
	$sth = $dbh->prepare($sql);
	$sth->execute(array($form));
}catch(PDOException $e) {
    echo $e->getMessage();
}

$actualForm = $sth->fetch(PDO::FETCH_OBJ);

$form = $_GET['form'];
$sql = "SELECT *
	FROM elements
	WHERE form = ?
	AND parent = 'root'
	ORDER BY position";

try {
	$sth = $dbh->prepare($sql);
	$sth->execute(array($form));
}catch(PDOException $e) {
    echo $e->getMessage();
}

$rows = array();
$json = array();
$json["version"] = ("1");
$json["containers"] = array();

while ($container = $sth->fetch(PDO::FETCH_OBJ)) {
	$sql = "SELECT *
		FROM elements
		WHERE form = ?
		AND parent = ?
		ORDER BY position";

	try {
		$sth2 = $dbh->prepare($sql);
		$sth2->execute(array($form, $container->key));
	}catch(PDOException $e) {
	    echo $e->getMessage();
	}


	while ($row = $sth2->fetch(PDO::FETCH_OBJ)) {

        $sql = "SELECT *
        FROM elements
        WHERE form = ?
        AND parent = ?
        ORDER BY position";

        try {
            $sth3 = $dbh->prepare($sql);
            $sth3->execute(array($form, $row->key));
        }catch(PDOException $e) {
            echo $e->getMessage();
        }

        while ($element = $sth3->fetch(PDO::FETCH_OBJ)) {
            $elements[] = array(
                "type"=>$element->type,
                "key"=>$element->key,
                "label"=>$element->label
            );
        }

        $rows[]["items"] = $elements;
        $elements = array();
	}


	$json["containers"][] = array("name"=>$container->label, "rows"=>$rows);
	$rows = array();
}

$finalJson = json_encode($json);
echo prettyPrint("<pre>".$finalJson."</pre>");