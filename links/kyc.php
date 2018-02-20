<?php
	if(!isset($_GET['id'])) {
		die("No link ID provided!");
	}
	
	require_once "../scripts/utils/database.php";
	require_once "../scripts/connectors/CompanyConnector.php";
	require_once "../scripts/connectors/LinkConnector.php";
	
	$CompanyConnector = new CompanyConnector($conn);
	$LinkConnector = new LinkConnector($conn);
	
	$link = $LinkConnector->select($_GET['id']);
	
	if(!$link) {
		die("Invalid link ID provided!");
	}
	
	$company = $CompanyConnector->select($link[LinkConnector::$COLUMN_COMPANYID]);
?>

<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $company[CompanyConnector::$COLUMN_NAME]; ?>'s KYC Procedure</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
		<style type="text/css">
			html, body {
				margin: 0;
				padding: 0;
			}
			@font-face {
				font-family: "Martel";
				src: url("../font/martel-regular.otf") format("opentype");
			}
			@font-face {
				font-family: "Ubuntu Bold";
				src: url("../font/Ubuntu-B.ttf") format("truetype");
			}
		</style>
	</head>
	<body>
		<nav class="navbar navbar-toggleable-md">
			<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<a class="navbar-brand" href="<?php echo $_SERVER['PHP_SELF']; ?>"><img src="../img/logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
			<span style="font-family: 'Ubuntu', Arial, sans-serif">KodYaC</span></a>
		</nav>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-6 offset-md-3 text-center">
					<!--<img src="../uploads/company-<?php echo $company[CompanyConnector::$COLUMN_ID]; ?>" width="100%">-->
					<img src="http://via.placeholder.com/350x150" width="100%">
					<br>
					<h1 style="font-family: 'Ubuntu Bold', Arial, sans-serif; margin-top: 2vh;">Welcome to <?php echo $company[CompanyConnector::$COLUMN_NAME]; ?>'s KYC Process</h1>
					<br>
					<a href="kycmain.php?id=<?php echo $_GET['id']; ?>" class="btn btn-primary" width="50%">Let's Begin!</a>
				</div>
			</div>
		</div>
		<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
	</body>
</html>
