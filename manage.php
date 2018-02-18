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
			.chart-card {
				background: #fff;
				border-radius: 2px;
				display: inline-block;
				position: relative;
				box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
			}
		</style>
	</head>
	<body>
		<?php require_once 'nav.php' ?>
		<div class="bg-inverse">
			<h1 style="font-family: 'Martel', Times New Roman, serif; font-weight: bold; text-align: center; padding: 5vh; color: #FFFFFF; font-size: 3em">Dashboard</h1>
		</div>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-4">
					Links Generated
					<br>
					<h1>1024</h1>
				</div>
				<div class="col-md-4">
					In Progress
					<br>
					<h1>94</h1>
				</div>
				<div class="col-md-4">
					Completed
					<br>
					<h1>109</h1>
				</div>
			</div>
			<div class="row">
				<canvas id="chrtKYCInMonth" height="75vh" class="chart-card"></canvas>
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
		
		var color = Chart.helpers.color;
		var config = {
			type: 'line',
			data: {
				labels: days,
				datasets: [{
					label: "My First dataset",
					backgroundColor: color("#ff0000").alpha(0.5).rgbString(),
					borderColor: "#ff0000",
					fill: false,
					data: [
						20,9,22,7,4,12
					],
				}, {
					label: "My Second dataset",
					backgroundColor: color("#0000ff").alpha(0.5).rgbString(),
					borderColor: "#0000ff",
					fill: false,
					data: [
						2,8,20,6,4,12
					],
				}]
			},
			options: {
				title:{
				    text: "Chart.js Time Scale"
				}
			}
				
		};
		
		var ctx = document.getElementById("chrtKYCInMonth").getContext("2d");
		window.myLine = new Chart(ctx, config);
	</script>
</html>
