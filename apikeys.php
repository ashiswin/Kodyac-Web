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
?>
<!DOCTYPE html>
<html>
	<head>
		<title>KodYaC - API Keys</title>
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
			<h1 style="font-family: 'Martel', Times New Roman, serif; font-weight: bold; text-align: center; padding: 5vh; color: #FFFFFF; font-size: 3em">API Keys</h1>
		</div>
		<div class="container">
			<table class="table" style="margin-top: 2vh">
				<colgroup>
					<col span="1" style="width: 5%;">
					<col span="1" style="width: 20%;">
					<col span="1" style="width: 35%;">
					<col span="1" style="width: 20%;">
					<col span="1" style="width: 15%;">
					<col span="1" style="width: 5%;">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>API Key Name</th>
						<th>Key</th>
						<th>Created On</th>
						<th>Total Requests</th>
						<th><i class="fas fa-trash"></i></th>
					</tr>
				</thead>
				<tbody id="tblKeys">
				</tbody>
			</table>
		</div>
		<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
		<script type="text/javascript">
			$("#navAPIKeys").addClass('active');
			var companyId = <?php echo $_SESSION['companyId']; ?>;
			$.get("scripts/GetAPIKeys.php?companyId=" + companyId, function(data) {
				response = JSON.parse(data);
				console.log(response);
				if(response.success) {
					var tblKeys = "";
					for(var i = 0; i < response.keys.length; i++) {
						if(response.keys[i].isDeleted == 1) continue;
						
						tblKeys += "<tr>";
						tblKeys += "<td>" + (i + 1) + "</td>";
						tblKeys += "<td>" + response.keys[i].name + "</td>";
						tblKeys += "<td>" + response.keys[i].apiKey + "</td>";
						tblKeys += "<td>" + response.keys[i].createdOn + "</td>";
						tblKeys += "<td>" + response.keys[i].requestCount + "</td>";
						tblKeys += "<td><a href=\"" + i + "\" class=\"delete\"><i class=\"fas fa-trash\"></i></a></td>";
						tblKeys += "</tr>";
					}
					tblKeys += "<tr><td colspan=6><a href=\"/\" id=\"create\"><i class=\"fas fa-plus\"></i>&nbsp;&nbsp;Create API key</a></td></tr>";
					$("#tblKeys").html(tblKeys);
					
					$(".delete").click(function(e) {
						e.preventDefault();
					});
					$("#create").click(function(e) {
						e.preventDefault();
					});
				}
			});
		</script>
	</body>
</html>
