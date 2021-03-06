<?php
	session_start();
	if(isset($_SESSION['companyId'])) {
		header("Location: manage.php");
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>KodYaC</title>
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
		<div class="text-center">
			<div class="offset-md-5 col-md-2">
				<img src="img/logo.png" class="img-fluid">
			</div>
		</div>
		<h1 style="text-align: center; font-size: 5em; font-family: 'Ubuntu Bold', Arial, sans-serif">KodYaC</h1>
		<h3 style="text-align: center; font-family: 'Martel', Times New Roman, serif; font-weight: bold">KYC you can trust</h3>
		<div class="form" style="margin-top: 2vh">
			<form class="col-md-3 text-center" style="display: block;margin-left: auto;margin-right: auto;">
				<input type="text" class="form-control" style="margin-right: 1vh" placeholder="Username" id="txtUsername">
				<input type="password" class="form-control" style="margin-right: 1vh" placeholder="Password" id="txtPassword">
				<button class="btn btn-primary" id="btnLogin" style="width: 100%; margin-top: 1vh">Log In</button>
				<a href="register.php" style="margin-top: 1vh">Register now</a>
			</form>
		</div>
		<form action="manage.php" method="post" id="frmContinue">
			<input type="hidden" id="companyId" name="companyId">
		</form>
		<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
		
		<script type="text/javascript">
			$("#btnLogin").click(function(e) {
				e.preventDefault();
				
				var username = $("#txtUsername").val().trim();
				var password = $("#txtPassword").val().trim();
				
				if(!username || username.length == 0) {
					$("#txtUsername")[0].setCustomValidity("Please enter a username");
		                	$("#txtUsername")[0].reportValidity();
				}
				else if(!password || password.length == 0) {
					$("#txtPassword")[0].setCustomValidity("Please enter a password");
		                	$("#txtPassword")[0].reportValidity();
				}
				else {
					$(this).html('<i class=\'fa fa-refresh spinning\'></i> Logging In...');
					$(this).addClass('disabled').attr("disabled", "disbled");
					$.post("scripts/Login.php", { username: username, password: password }, function(data) {
						response = JSON.parse(data);
						console.log(response);
						if(response.success) {
							$("#companyId").val(response.companyId);
							$("#frmContinue").submit();
						}
						else {
							$("#txtUsername")[0].setCustomValidity(response.message);
				                	$("#txtUsername")[0].reportValidity();
							$("#btnLogin").removeClass('disabled').removeAttr('disabled').html("Log In");
						}
					});
				}
			});
		</script>
	</body>
</html>
