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
	$profiles = $LinkConnector->selectByCompany($_SESSION['companyId']);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>KodYaC - Dashboard</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
		<script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
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
			<h1 style="font-family: 'Martel', Times New Roman, serif; font-weight: bold; text-align: center; padding: 5vh; color: #FFFFFF; font-size: 3em">Profiles</h1>
		</div>
		<div class="container">
			<ul class="nav nav-pills">
				<li class="nav-item">
					<a class="nav-link tabletab active" href="all">All</a>
				</li>
				<li class="nav-item">
					<a class="nav-link tabletab" id="#tabRequested" href="requested">Requested</a>
				</li>
				<li class="nav-item">
					<a class="nav-link tabletab" id="#tabInprogress" href="inprogress">In Progress</a>
				</li>
				<li class="nav-item">
					<a class="nav-link tabletab" id="#tabCompleted" href="completed">Completed</a>
				</li>
				<li class="nav-item">
					<a class="nav-link tabletab" id="#tabCancelled" href="cancelled">Cancelled</a>
				</li>
			</ul>
			<table class="table" style="margin-top: 2vh">
				<thead>
					<tr>
						<th>#</th>
						<th>Profile ID</th>
						<th>Profile Name</th>
						<th>Status</th>
						<th><i class="fas fa-eye"></i></th>
					</tr>
				</thead>
				<tbody id="tblProfiles">
				</tbody>
			</table>
		</div>
		<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
		<script type="text/javascript">
			$("#navProfiles").addClass('active');
			
			function prettyStatus(status) {
				if(status == "requested") return "Requested";
				if(status == "inprogress") return "In Progress";
				if(status == "completed") return "Completed";
				if(status == "cancelled") return "Cancelled";
				return "Unknown";
			}
			
			var profiles = JSON.parse("<?php echo addslashes(json_encode($profiles)); ?>");
			
			var requested = Array();
			var inprogress = Array();
			var completed = Array();
			var cancelled = Array();
			
			var tblProfiles = "";
			
			for(var i = 0; i < profiles.length; i++) {
				tblProfiles += "<tr>";
				tblProfiles += "<td>" + (i + 1) + "</td>";
				tblProfiles += "<td>" + profiles[i].id + "</td>";
				tblProfiles += "<td>" + profiles[i].name + "</td>";
				tblProfiles += "<td>" + prettyStatus(profiles[i].status) + "</td>";
				tblProfiles += "<td><a href=\"" + profiles[i].id + "\"><i class=\"fas fa-eye\"></i></a></td>";
				tblProfiles += "</tr>";
				
				if(profiles[i].status == "requested") requested.push(profiles[i]);
				else if(profiles[i].status == "inprogress") inprogress.push(profiles[i]);
				else if(profiles[i].status == "completed") completed.push(profiles[i]);
				else if(profiles[i].status == "cancelled") cancelled.push(profiles[i]);
			}
			
			if(requested.length == 0) {
				$("#tabRequested").addClass('disabled');
			}
			if(inprogress.length == 0) {
				$("#tabInprogress").addClass('disabled');
			}
			if(completed.length == 0) {
				$("#tabCompleted").addClass('disabled');
			}
			if(cancelled.length == 0) {
				$("#tabCancelled").addClass('disabled');
			}
			
			$("#tblProfiles").html(tblProfiles);
			$(".tabletab").click(function(e) {
				e.preventDefault();
				
				$(".tabletab").removeClass('active');
				$(this).addClass('active');
				
				var status = $(this).attr('href');
			});
		</script>
	</body>
</html>
