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
	$links = $LinkConnector->selectByCompany($_SESSION['companyId']);
	$noRequested = count($links);
	$noCompleted = 0;
	$noInProgress = 0;
	
	for($i = 0; $i < count($links); $i++) {
		if(strcmp($links[$i][LinkConnector::$COLUMN_STATUS], "inprogress") == 0) {
			$noInProgress++;
		}
		else if(strcmp($links[$i][LinkConnector::$COLUMN_STATUS], "completed") == 0) {
			$noCompleted++;
		}
	}
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
			<div class="row" style="margin: 1vh">
				<div class="col-md-3">
					Links Generated
					<br>
					<h1><?php echo $noRequested; ?></h1>
				</div>
				<div class="col-md-3" style="border-left: 1px solid #CCCCCC;">
					In Progress
					<br>
					<h1><?php echo $noInProgress; ?></h1>
				</div>
				<div class="col-md-3" style="border-left: 1px solid #CCCCCC;">
					Completed
					<br>
					<h1><?php echo $noCompleted; ?></h1>
				</div>
				<div class="col-md-3" style="border-left: 1px solid #CCCCCC;">
					Outstanding
					<br>
					<h1><?php echo ($noRequested - $noCompleted); ?></h1>
				</div>
			</div>
			<div class="row" style="margin: 1vh">
				<div class="col-md-12 chart-card" style="padding: 1vh">
					<h3>Monthly Statistics</h3>
					<hr>
					<canvas id="chrtKYCInMonth" height="75vh"></canvas>
				</div>
			</div>
			<div class="row" style="margin: 1vh">
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
		var date = new Date();
		var days = getDaysArray(date.getFullYear(), date.getMonth());
		
		var links = JSON.parse("<?php echo addslashes(json_encode($links)); ?>");
		var requestCount = new Array(days.length);
		
		for(var i = 0; i < links.length; i++) {
			var l = links[i];
			if(l.createdOn.indexOf(date.getFullYear() + "-" + date.getMonth()) != -1) {
				var date = l.split(" ")[0];
				var day = parseInt(date[date.length - 2] + date[date.length - 1]) - 1;
				
				if(requestCount[day] == undefined) {
					requestCount[day] = 1;
				}
				else {
					requestCount[day]++;
				}
			}
		}
		
		var color = Chart.helpers.color;
		var config = {
			type: 'line',
			data: {
				labels: days,
				datasets: [{
					label: "KYC Requests",
					backgroundColor: color("#ff0000").alpha(0.5).rgbString(),
					borderColor: "#ff0000",
					fill: false,
					data: requestCount,
				}, {
					label: "KYC Completions",
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
				},
				legend:{
					position: 'right'
				}
			}
				
		};
		
		var ctx = document.getElementById("chrtKYCInMonth").getContext("2d");
		window.myLine = new Chart(ctx, config);
	</script>
</html>
