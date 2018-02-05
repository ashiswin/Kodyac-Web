<!DOCTYPE html>
<html>
	<head>
		<title>KodYaC - Registration</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
		<script src="https://use.fontawesome.com/1c64219ae2.js"></script>
		<style type="text/css">
			html, body {
				margin: 0;
				padding: 0;
			}
			.logo { 
				background: url(img/logo.png) 50% 50% no-repeat; /* 50% 50% centers image in div */
				background-size: 300px;
				width: 200px;
				height: 200px;
				display: block;
				margin-left: auto;
				margin-right: auto;
				border-radius: 5px;
			}
			@font-face {
				font-family: "Martel";
				src: url("font/martel-regular.otf") format("opentype");
			}
			@font-face {
				font-family: "Ubuntu";
				src: url("font/Ubuntu-R.ttf") format("truetype");
			}
			.spinning {
				animation: spin 1s infinite linear;
				-webkit-animation: spin2 1s infinite linear;
			}
			@keyframes spin {
				from { transform: scale(1) rotate(0deg); }
				to { transform: scale(1) rotate(360deg); }
			}
			@-webkit-keyframes spin2 {
				from { -webkit-transform: rotate(0deg); }
				to { -webkit-transform: rotate(360deg); }
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
		</nav>
		<div class="bg-inverse">
			<h1 style="font-family: 'Martel', Times New Roman, serif; font-weight: bold; text-align: center; padding: 5vh; color: #FFFFFF; font-size: 3em">Registration</h1>
		</div>
		<div class="container">
			<div class="form" style="margin-top: 2vh">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="txtUsername">Username:</label>
							<input type="text" class="form-control" id="txtUsername" name="txtUsername">
						</div>
						<div class="form-group">
							<label for="txtPassword">Password:</label>
							<input type="password" class="form-control" id="txtPassword" name="txtPassword">
						</div>
						<div class="form-group">
							<label for="txtConfirmPassword">Confirm Password:</label>
							<input type="password" class="form-control" id="txtConfirmPassword" name="txtConfirmPassword">
						</div>
						<div class="form-group">
							<label for="chkVerificationMethods">Verification Methods:</label><br>
							<input type="checkbox" id="chkSMS">&nbsp;&nbsp;SMS 2FA Verification<br>
							<input type="checkbox" id="chkNRIC">&nbsp;&nbsp;NRIC Verification<br>
							<input type="checkbox" id="chkBiometric">&nbsp;&nbsp;Biometric Verification<br>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="txtCompanyName">Company Name:</label>
							<input type="text" class="form-control" id="txtCompanyName" name="txtCompanyName">
						</div>
						<div class="form-group">
							<label for="txtPOCName">POC Name:</label>
							<input type="text" class="form-control" id="txtPOCName" name="txtPOCName">
						</div>
						<div class="form-group">
							<label for="txtPOCEmail">POC Email:</label>
							<input type="email" class="form-control" id="txtPOCEmail" name="txtPOCEmail">
						</div>
						<div class="form-group">
							<label for="txtPOCContactNumber">POC Contact Number:</label>
							<input type="phone" class="form-control" id="txtPOCContactNumber" name="txtPOCContactNumber">
						</div>
					</div>
				</div>
				<button class="btn btn-primary" id="btnRegister">Register</button>
			</div>
		</div>
		<form action="manage.php" method="post" id="frmContinue">
			<input type="hidden" id="companyId" name="companyId">
		</form>
		<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
		
		<script type="text/javascript">
			$("#btnRegister").click(function(e) {
				e.preventDefault();
				
				var username = $("#txtUsername").val().trim();
				var password = $("#txtPassword").val().trim();
				var cPassword = $("#txtConfirmPassword").val().trim();
				var name = $("#txtCompanyName").val().trim();
				var pocName = $("#txtPOCName").val().trim();
				var pocEmail = $("#txtPOCEmail").val().trim();
				var pocContact = $("#txtPOCContactNumber").val().trim();
				
				var methods = "";
				
				if($("#chkSMS").is(":checked")) {
					if(!methods) {
						methods += "sms";
					}
					else {
						methods += "|sms";
					}
				}
				if($("#chkNRIC").is(":checked")) {
					if(!methods) {
						methods += "nric";
					}
					else {
						methods += "|nric";
					}
				}
				if($("#chkBiometric").is(":checked")) {
					if(!methods) {
						methods += "biometric";
					}
					else {
						methods += "|biometric";
					}
				}
				if(!username || username.length == 0) {
					$("#txtUsername")[0].setCustomValidity("Please enter a username");
		                	$("#txtUsername")[0].reportValidity();
				}
				else if(!password || password.length == 0) {
					$("#txtPassword")[0].setCustomValidity("Please enter a password");
		                	$("#txtPassword")[0].reportValidity();
				}
				else if(!cPassword || cPassword.length == 0) {
					$("#txtConfirmPassword")[0].setCustomValidity("Please confirm your password");
		                	$("#txtConfirmPassword")[0].reportValidity();
				}
				else if(password != cPassword) {
					$("#txtPassword")[0].setCustomValidity("Passwords do not match");
		                	$("#txtPassword")[0].reportValidity();
				}
				else if(!name || name.length == 0) {
					$("#txtCompanyName")[0].setCustomValidity("Please enter your company's name");
		                	$("#txtCompanyName")[0].reportValidity();
				}
				else if(!pocName || pocName.length == 0) {
					$("#txtPOCName")[0].setCustomValidity("Please your name");
		                	$("#txtPOCName")[0].reportValidity();
				}
				else if(!pocEmail || pocEmail.length == 0) {
					$("#txtPOCEmail")[0].setCustomValidity("Please enter your email address");
		                	$("#txtPOCEmail")[0].reportValidity();
				}
				else if(!pocContact || pocContact.length == 0) {
					$("#txtPOCContactNumber")[0].setCustomValidity("Please enter your contact number");
		                	$("#txtPOCContactNumber")[0].reportValidity();
				}
				else if(!methods || methods.length == 0) {
					$("#chkSMS")[0].setCustomValidity("Please select at least one verification method");
					$("#chkSMS")[0].reportValidity();
				}
				else {
					$(this).html('<i class=\'fa fa-refresh spinning\'></i> Registering...');
					$(this).addClass('disabled').attr("disabled", "disbled");
					$.post("scripts/CreateCompany.php", { name: name, username: username, password: password, pocName: pocName, pocEmail: pocEmail, pocContactNumber: pocContact, methods: methods }, function(data) {
						response = JSON.parse(data);
						if(response.success) {
							$("#companyId").val(response.companyId);
							$("#frmContinue").submit();
						}
					});
				}
			});
		</script>
	</body>
</html>
