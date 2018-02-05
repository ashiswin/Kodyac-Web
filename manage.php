<?php
	session_start();
	
	if(isset($_POST['companyId'])) {
		$_SESSION['companyId'] = $_POST['companyId'];
	}
	if(!isset($_SESSION['companyId'])) {
		header("Location: /");
	}
	
	require_once 'scripts/utils/database.php';
	require_once 'scripts/connectors/CompanyConnector.php';
	
	$CompanyConnector = new CompanyConnector($conn);
	$company = $CompanyConnector->select($_SESSION['companyId']);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>KodYaC - Dashboard</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
		<style type="text/css">
			html, body {
				margin: 0;
				padding: 0;
			}
			@font-face {
				font-family: "Martel";
				src: url("font/martel-regular.otf") format("opentype");
			}
			@font-face {
				font-family: "Ubuntu Bold";
				src: url("font/Ubuntu-B.ttf") format("truetype");
			}
		</style>
	</head>
	<body>
		<nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse">
			<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<a class="navbar-brand" href="/"><img src="img/logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
 <span style="font-family: 'Ubuntu', Arial, sans-serif">KodYaC</span></a>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav">
					<li class="nav-item"><span class="nav-link">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Welcome <?php echo $company["name"]; ?></span></li>
				</ul>
		
				<ul class="navbar-nav ml-auto">
					<li id="navAircrafts" class="nav-item"><a href="aircrafts.php" class="nav-link">Aircrafts</a></li>
					<li id="navDefects" class="nav-item"><a href="defects.php" class="nav-link">Defects</a></li>
					<li id="navMaintenance" class="nav-item"><a href="maintenance.php" class="nav-link">Maintenance</a></li>
					<li id="navStatistics" class="nav-item"><a href="statistics.php" class="nav-link">Statistics</a></li>
					<li id="navTechnicians" class="nav-item"><a href="technicians.php" class="nav-link">Technicians</a></li>
					<li id="navLogout" class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>
				</ul>
			</div>
		</nav>
		<div class="bg-inverse">
			<h1 style="font-family: 'Martel', Times New Roman, serif; font-weight: bold; text-align: center; padding: 5vh; color: #FFFFFF; font-size: 3em">Dashboard</h1>
		</div>
	</body>
</html>
