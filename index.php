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
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Tigo Designer</title>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	 <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	<link href='http://fonts.googleapis.com/css?family=Roboto:400,300,500,700' rel='stylesheet' type='text/css'>
	<link href='screen.css' rel='stylesheet' type='text/css' media="screen">

	<script type="text/javascript">
		$(document).ready(function(){
			$( ".row" ).sortable({
				items: ".element",
				start: function (e, ui) {
	    				//alert("started");
	    			},
	    			update: function (e, ui) {
	    				//alert("updated");
	    				save();
	    			}
	    	});
		    $( ".row" ).disableSelection();

			function updateInspectorHeight(){
				var documentHeight = $(document).height();
				$('#inspector').css('height', documentHeight+'px');
			}

			updateInspectorHeight();

			var containerCount = 0;
			var rowCount = 0;
			var textFieldCount = 0;

			function resetSelection(){
				$('.element').removeClass("selected");
				$('.container').removeClass("selected");
			}

			$('#content').click(function(){
				//resetSelection();
				//$('#inspector').hide();
			})

			$('body').on("click", ".add", function(e){
				resetSelection();
				var text = $(this).find('.text');
				if($(this).find(".menu").is(':visible')){
					$('.addRow').removeClass("disabled");
					$(this).find(".menu").slideUp(100);
					text.text('Elemento');
				}else{
					$('.addRow').addClass("disabled");
					$(this).find(".menu").slideDown(200);
					text.text('Cancelar');
				}
				e.stopPropagation();

			})

			$('body').on("click", ".elTexfield", function(e){
				var id = makeid();
				resetSelection();
				$(this).parent().parent().before('<div class="element textfield'+id+'" data-label="Sin Titulo" data-type="textfield" data-key="textfield'+id+'"><div>Text Field</div><div class="key">textfield'+id+'</div><div class="label">Sin Titulo</div></div>');
				$(this).parent().parent().find(".menu").slideUp(100);
				$(this).parent().parent().find('.text').text('Elemento');
				$('.addRow').removeClass("disabled");
				save();
				textFieldCount++;
				e.stopPropagation();
			})

			//AGREGAR CONTENEDOR
			$('.addContainer').click(function(){
				var id=makeid();
				resetSelection();
				$(this).parent().before('<div class="container container'+id+'"  data-type="container" data-key="container'+id+'" data-label="Sin Titulo"></div>');
				$(this).parent().prev().html('<div class="title">Sin Titulo</div><div class="addRow"><div class="sign">+</div><div class="text">Fila</div></div><label class="containerLabel">container'+id+'</label><div class="clear"></div>');
				containerCount++;
				updateInspectorHeight();
				save();
			})

			//AGREGAR FILA
			$('body').on("click", ".addRow", function(e){
				var id=makeid();
				resetSelection();
				$(this).before('<div class="row" data-key="row'+id+'"></div>');
				$(this).prev().html('<div class="add"><div class="sign">+</div><div class="text">Elemento</div><ul class="menu"><li class="elTexfield">Text Field</li><li class="elTextarea">Text Area</li></ul></div><label class="rowLabel">row'+id+'</label><div class="clear"></div>');
				rowCount++;
				e.stopPropagation();
				updateInspectorHeight();
				save();
			})

			function hideInspectors(){
				$('#inspector > div').hide();
			}

			$('body').on("click", ".element", function(e){
				if($(this).hasClass("selected")){
					$(this).removeClass("selected");
					$('#inspector .msg').show();
					hideInspectors();
					e.stopPropagation();

				}else{
					hideInspectors();
					resetSelection();
					$(this).addClass("selected");
					
					//GET DATA
					var type = $(this).data("type");
					var key = $(this).data("key");
					var label = $(this).data("label");

					//SHOW INSPECTOR ---- MOVE
					$('#inspector').data("key", key);
					$('#inspector .msg').hide();
					$('#inspector_'+type).show();

					//SHOW DATA
					$('.inspector_key').val(key);
					$('.inspector_label').val(label);
					e.stopPropagation();

				}
			})

			$('body').on("click", ".container", function(){
				if($(this).hasClass("selected")){
					$(this).removeClass("selected");
					$('#inspector .msg').show();
					hideInspectors();
				}else{
					hideInspectors();
					resetSelection();
					$(this).addClass("selected");

					//GET DATA
					var type = $(this).data("type");
					var key = $(this).data("key");
					var label = $(this).data("label");

					//SHOW INSPECTOR ---- MOVE
					$('#inspector').data("key", key);
					$('#inspector .msg').hide();
					$('#inspector_'+type).show();

					//SHOW DATA
					$('.inspector_key').val(key);
					$('.inspector_label').val(label);
				}
			})

			$('.updateButton_container').click(function(){

				var inspector = $(this).closest("div");
				var type = inspector.data("type");
				var id = $('#inspector').data("key");

				//UPDATE LABEL
				var label = inspector.find('.inspector_label').val();
				$('.'+id).data("label", label);
				$('.'+id).find(".title").text(label);

				save();
			});

			$('.updateButton_textfield').click(function(){

				var inspector = $(this).closest("div");
				var type = inspector.data("type");
				var id = $('#inspector').data("key");

				//UPDATE LABEL
				var label = inspector.find('.inspector_label').val();
				$('.'+id).data("label", label);
				$('.'+id).find(".label").text(label);

				save();
			});

			function save(){

				$.ajax({
					type: "POST",
				  	url: "reset.php",
				  	data: { form: <?php echo $_GET['form']; ?> }
				}).done(function( msg ) {
					$('.container').each(function(){

						var container = $(this);
						var container_position = container.index();
						var container_key = $(this).data("key");
						var container_label = $(this).data("label");

						$.ajax({
							type: "POST",
						  	url: "save.php",
						  	data: { form: <?php echo $_GET['form']; ?>, type: "container", key: container_key, label: container_label, position: container_position }
						}).done(function( msg ) {
							container.find('.row').each(function(){
								var row = $(this);
								var row_position = row.index();
								var row_key = $(this).data("key");

								$.ajax({
									type: "POST",
								  	url: "save.php",
								  	data: { form: <?php echo $_GET['form']; ?>, parent: container_key, type: "row", key: row_key, position: row_position }
								}).done(function( msg ) {
									
									row.find('.element').each(function(){
										var element = $(this);
										var element_position = element.index();
										var element_key = $(this).data("key");
										var element_type = $(this).data("type");
										var element_label = $(this).data("label");

										$.ajax({
											type: "POST",
										  	url: "save.php",
										  	data: { form: <?php echo $_GET['form']; ?>, parent: row_key, type: element_type, key: element_key, position: element_position, label: element_label }
										}).done(function( msg ) {
											//alert(msg);
										});
									})

								});
							})
						});
						
					})
				})


			}

			function makeid()
			{
			    var text = "";
			    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

			    for( var i=0; i < 5; i++ )
			        text += possible.charAt(Math.floor(Math.random() * possible.length));

			    return text;
			}

			$('#saveButton').click(function(){
				save();
			});
		})
	</script>
</head>
<body>

	<div id="inspector">
		<h1>Inspector de Propiedades</h1>

		<p class="msg">Seleccione un elemento para ver sus propiedades.</p>

		<div data-type="container" class="inspector_content" id="inspector_container">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td>Tipo</td>
					<td style="padding: 5px">Contenedor</td>
				</tr>
				<tr>
					<td>ID (Key)</td>
					<td>
						<input type="text" name="inspector_key" class="inspector_key">
					</td>
				</tr>
				<tr>
					<td>Titulo</td>
					<td>
						<input type="text" name="inspector_label" class="inspector_label">
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<input type="button" value="Actualizar" class="button updateButton_container">
					</td>
				</tr>
			</table>
		</div>

		<div data-type="textfield" class="inspector_content" id="inspector_textfield">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td>Tipo</td>
					<td style="padding: 5px">Text Field</td>
				</tr>
				<tr>
					<td>ID (Key)</td>
					<td>
						<input type="text" name="inspector_key" class="inspector_key">
					</td>
				</tr>
				<tr>
					<td>Label</td>
					<td>
						<input type="text" name="inspector_label" class="inspector_label">
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<input type="button" value="Actualizar" class="button updateButton_textfield">
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div id="content">

		<h1><?php echo $actualForm->name; ?></h1>

		<?php 
		while($container = $sth->fetch(PDO::FETCH_OBJ)){?>
			<div class="container <?php echo $container->key; ?>" data-type="container" data-key="<?php echo $container->key; ?>" data-label="<?php echo $container->label; ?>">
				<div class="title"><?php echo $container->label; ?></div>

				<?php 
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

				while($row = $sth2->fetch(PDO::FETCH_OBJ)){
				?>
					<div class="row" data-key="<?php echo $row->key; ?>">

						<?php 
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

						while($element = $sth3->fetch(PDO::FETCH_OBJ)){
						?>
						<div class="element <?php echo $element->key; ?>" data-label="<?php echo $element->label; ?>" data-type="textfield" data-key="<?php echo $element->key; ?>"><div>Text Field</div><div class="key"><?php echo $element->key; ?></div><div class="label"><?php echo $element->label; ?></div></div>
						<?php 
						}
						?>

						<div class="add">
							<div class="sign">+</div>
							<div class="text">Elemento</div>
							<ul class="menu">
								<li class="elTexfield">Text Field</li>
								<li class="elTextarea">Text Area</li>
							</ul>
						</div>
						<label class="rowLabel"><?php echo $row->key; ?></label>
						<div class="clear"></div>
					</div>

				<?php
				}
				?>
				<div class="addRow">
					<div class="sign">+</div>
					<div class="text">Fila</div>
				</div>
				<label class="containerLabel"><?php echo $container->key; ?></label>
				<div class="clear"></div>
			</div>

		<?php
		}
		?>

		<div class="lastRow">
			<div class="addContainer">
				<div class="sign">+</div>
				<div class="text">Contenedor</div>
			</div>	

			<div class="clear"></div>
		</div>
	</div>

	
	
</body>
</html>