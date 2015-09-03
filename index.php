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

		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/bootstrap.min.js"></script>
		
		<!-- Bootstrap core CSS -->
		<link href="assets/css/bootstrap.min.css" rel="stylesheet">

		<style>
			body {
				padding-top: 20px;
				padding-bottom: 20px;
			}
			.navbar {
				margin-bottom: 20px;
			}

			.file_folder_size{
				display:inline-block;
				margin-left:15px;
				width:75px;
			}
			.file_folder_perm{
				display:inline-block;
				margin-left:15px;
				width:38px;
			}
			.file_folder_mtime{
				display:inline-block;
				margin-left:15px;
				width:132px;
			}
		
			.sugar{
				display: inline-block;
				margin-right:50px;
			}
			.sugar .version{
				margin-left:15px;
				display:inline-block;
				text-align:center;
			    width: 98px;
			}
			.sugar .flavor{
				margin-left:15px;
			    width: 94px;
				text-align:center;
			    display: inline-block;
			}
			.sugar .build{
				margin-left:15px;
				text-align:center;
			    display: inline-block;
			    width: 84px;
			}

			.glyphicon{
				margin-right:8px;
			}
			.list-group-header{
				font-weight: bold;
				z-index:9999;
			}
			.list-group-header.affix{
				top:60px;
			}
			.button-group-wrapper{
				margin-bottom:10px;
				display:inline-block;
				background-color: #fff;
				z-index:9998;
			}
			.button-group-label{
				font-size: 15px;
			}
			.button-group-wrapper.affix{
				top:0px;
				padding:10px 0 50px;
				border-bottom:1px solid #ccc;
			}
			.button-group-wrapper .btn .glyphicon{
				display:none;
			}
			.button-group-wrapper .btn.active .glyphicon{
				display:inline-block;
			}
			.sugar_logo{
				width: 18px;
				height: 18px;
				margin-left: -2px;
				margin-right: 5px;
			}

			@media (min-width: 768px) {
				.list-group-header.affix,
				.button-group-wrapper.affix {
					width: 720px;
				}
			}
			@media (min-width: 992px) {
				.list-group-header.affix,
				.button-group-wrapper.affix {
					width: 940px;
				}
			}
			@media (min-width: 1200px) {
				.list-group-header.affix,
				.button-group-wrapper.affix {
					width: 1140px;
				}
			}
		</style>
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<div class="container">
			<?php require_once("body.php") ?>
		</div> <!-- /container -->
		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		
	</body>
</html>