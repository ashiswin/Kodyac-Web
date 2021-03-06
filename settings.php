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
		<title>KodYaC - Account Settings</title>
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
			<h1 style="font-family: 'Martel', Times New Roman, serif; font-weight: bold; text-align: center; padding: 5vh; color: #FFFFFF; font-size: 3em">Account Settings</h1>
		</div>
		<div class="container-fluid">
			<h2>Change Password</h2>
			<div class="row">
				<div class="offset-md-1 col-md-2">
						<label for="txtCurrentPassword">Current Password:&nbsp;&nbsp;</label>
				</div>
				<div class="col-md-3">
						<input type="password" class="form-control" id="txtCurrentPassword" length="30">
				</div>
			</div>
			<div class="row">
				<div class="offset-md-1 col-md-2">
						<label for="txtNewPassword">New Password:&nbsp;&nbsp;</label>
				</div>
				<div class="col-md-3">
						<input type="password" class="form-control" id="txtNewPassword" length="30">
				</div>
			</div>
			<div class="row">
				<div class="offset-md-1 col-md-2">
						<label for="txtRetypePassword">Retype Password:&nbsp;&nbsp;</label>
				</div>
				<div class="col-md-3">
						<input type="password" class="form-control" id="txtRetypePassword" length="30">
				</div>
			</div>
			<div class="row">
				<div class="offset-md-1 col-md-2">
					<button class="btn btn-primary" id="btnSavePassword">Save</button>
				</div>
			</div>
			<hr>
			<h2>Callback URL</h2>
			<br>
			<div class="row">
				<div class="offset-md-1 col-md-2">
					<label for="txtCallback">Callback URL:</label>
				</div>
				<div class="col-md-7">
					<input type="text" class="form-control" id="txtCallback" value="<?php echo $company[CompanyConnector::$COLUMN_CALLBACK]; ?>">
				</div>
				<div class="col-md-1">
					<button class="btn btn-primary" id="btnSaveCallback">Save</button>
				</div>
			</div>
		</div>
	</body>
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
	<script type="text/javascript">
		$("#navSettings").addClass('active');
		
		var companyId = <?php echo $_SESSION['companyId']; ?>;
		$("#btnSavePassword").click(function(e) {
			e.preventDefault();
			
			var password = $("#txtCurrentPassword").val();
			var newPassword = $("#txtNewPassword").val();
			var retypePassword = $("#txtRetypePassword").val();
			
			if(newPassword != retypePassword) {
				// TODO: Throw error
				return;
			}
			
			$.post("scripts/UpdatePassword.php", {companyId: companyId, password: password, newPassword: newPassword}, function(data) {
				response = JSON.parse(data);
				if(response.success) {
					$("#btnSavePassword").removeClass("btn-success").addClass("btn-success").html('Saved');
					$("#txtCurrentPassword").val("");
					$("#txtNewPassword").val("");
					$("#txtRetypePassword").val("");
				}
			});
		});
		
		$("#btnSaveCallback").click(function(e) {
			e.preventDefault();
			
			var callback = $("#txtCallback").val();
			
			$.post("scripts/UpdateCallback.php", {companyId: companyId, callback: callback}, function(data) {
				response = JSON.parse(data);
				if(response.success) {
					$("#btnSaveCallback").removeClass("btn-primary").addClass("btn-success").html('Saved');
				}
			});
		});
	</script>
</html>
