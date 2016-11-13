<?php
	if (isset($_GET['save']) && $_GET['save'] == "true" ) {
		require_once "save.php";
		exit;
	}
?>
<?php require_once "functions.php"; ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<meta name="description" content="">
		<meta name="author" content="">
		<title>Localhost Indexer</title>

		<!-- Bootstrap core CSS -->
		<link href="assets/css/bootstrap.min.css" rel="stylesheet">
		<link href="assets/css/font-awesome.min.css" rel="stylesheet">
		<link href="assets/css/app.css" rel="stylesheet">

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<div class="container">
			<?php require_once "breadcrumbs.php"; ?>
			<div id="vue-container">
				<?php
					if(isset($_GET['editor'])){
						require_once "editor.php";
					}else{
						require_once "body.php";
					}
				?>
			</div>
		</div> <!-- /container -->
		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script>
			var fileFoldersData = '<?php !isset($ace_editor) and print json_encode( getFilesFolders() ) ?>';
			var parsedData = fileFoldersData == "" ? [] : JSON.parse(fileFoldersData);
		</script>

		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/bootstrap.min.js"></script>
		<script src="assets/js/lodash.js"></script>
		<script src="assets/js/vue.min.js"></script>
		<script src="assets/js/app.js"></script>
	</body>
</html>
