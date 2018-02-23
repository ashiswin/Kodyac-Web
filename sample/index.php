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
		<div class="text-center">
			<div class="offset-md-5 col-md-2">
				<img src="img/logo.png" class="img-fluid">
			</div>
		</div>
		<h1 style="text-align: center; font-size: 5em; font-family: 'Ubuntu Bold', Arial, sans-serif">ABC Company</h1>
		<div class="form" style="margin-top: 2vh">
			<form class="col-md-3 text-center" style="display: block;margin-left: auto;margin-right: auto;">
				<input type="text" class="form-control" style="margin-right: 1vh" placeholder="API Key" id="txtKey">
				<button class="btn btn-primary" id="btnLogin" style="width: 100%; margin-top: 1vh">Generate Link</button>
			</form>
			<a href="" id="link"></a>
		</div>
		<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
		
		<script type="text/javascript">
			$("#btnLogin").click(function(e) {
				e.preventDefault();
				
				var key = $("#txtKey").val().trim();
				
				if(!key || key.length == 0) {
					$("#txtKey")[0].setCustomValidity("Please enter your API key");
		                	$("#txtKey")[0].reportValidity();
				}
				else {
					$(this).html('<i class=\'fas fa-sync-alt spinning\'></i> Generating...');
					$(this).addClass('disabled').attr("disabled", "disbled");
					$.post("../scripts/CreateLink.php", { apiKey: key }, function(data) {
						response = JSON.parse(data);
						if(response.success) {
							$("#link").attr('href', response.link).html("Go to KYC");
						}
						else {
							$("#txtKey")[0].setCustomValidity(response.message);
				                	$("#txtKey")[0].reportValidity();
							$("#btnLogin").removeClass('disabled').removeAttr('disabled').html("Generate Link");
						}
					});
				}
			});
		</script>
	</body>
</html>
