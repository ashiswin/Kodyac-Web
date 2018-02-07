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
	require_once 'scripts/connectors/LinkConnector.php';
	
	$CompanyConnector = new CompanyConnector($conn);
	$LinkConnector = new LinkConnector($conn);
	
	$company = $CompanyConnector->select($_SESSION['companyId']);
	$noRequested = count($LinkConnector->selectByCompany($_SESSION['companyId']));
	$noCompleted = count($LinkConnector->selectByStatus("completed"));
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
		<?php require_once 'nav.php' ?>
		<div class="bg-inverse">
			<h1 style="font-family: 'Martel', Times New Roman, serif; font-weight: bold; text-align: center; padding: 5vh; color: #FFFFFF; font-size: 3em">Dashboard</h1>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-md-12 text-center">
					<canvas id="chrtKYCInMonth"></canvas>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6 offset-md-3">
					<canvas id="chrtStatuses" width="100%"></canvas>
				</div>
			</div>
		</div>
	</body>
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
	<script type="text/javascript">
		$("#navDashboard").addClass('active');
		
		var getDaysArray = function(year, month) {
			var names = [ 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ];
			var date = new Date(year, month - 1, 1);
			var result = [];
			while (date.getMonth() == month - 1) {
				result.push(date.getDate() + " " + names[month - 1]);
				date.setDate(date.getDate() + 1);
			}
			
			return result;
		}
		var days = getDaysArray(2018, 2);
		console.log(days);
		
		new Chart(document.getElementById("chrtKYCInMonth"), {
			type: 'line',
			data: {
				labels: [1500,1600,1700,1750,1800,1850,1900,1950,1999,2050],
				datasets: [{ 
						data: [86,114,106,106,107,111,133,221,783,2478],
						label: "Africa",
						borderColor: "#3e95cd",
						fill: false
					}, { 
						data: [282,350,411,502,635,809,947,1402,3700,5267],
						label: "Asia",
						borderColor: "#8e5ea2",
						fill: false
					}, { 
						data: [168,170,178,190,203,276,408,547,675,734],
						label: "Europe",
						borderColor: "#3cba9f",
						fill: false
					}, { 
						data: [40,20,10,16,24,38,74,167,508,784],
						label: "Latin America",
						borderColor: "#e8c3b9",
						fill: false
					}, { 
						data: [6,3,2,2,7,26,82,172,312,433],
						label: "North America",
						borderColor: "#c45850",
						fill: false
					}
				]
			},
			options: {
				title: {
					display: true,
					text: 'World population per region (in millions)'
				}
			}
		});
	</script>
</html>
