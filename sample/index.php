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
				src: url("../font/martel-regular.otf") format("opentype");
			}
			@font-face {
				font-family: "Ubuntu Bold";
				src: url("../font/Ubuntu-B.ttf") format("truetype");
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
		<div class="container">
			<h1 style="text-align: center; font-size: 5em; font-family: 'Ubuntu Bold', Arial, sans-serif">ABC Company</h1>
			<div style="text-align: center; font-size: 3em; font-family: 'Martel', Times New Roman, serif">Registration</div>
			<div class="row" style="margin-top: 2vh">
				<div class="col-md-2">
					Username:
				</div>
				<div class="col-md-10">
					<input type="text" class="form-control">
				</div>
			</div>
			<div class="row" style="margin-top: 2vh">
				<div class="col-md-2">
					Password:
				</div>
				<div class="col-md-10">
					<input type="password" class="form-control">
				</div>
			</div>
			<div class="row" style="margin-top: 2vh">
				<div class="col-md-2">
					Retype Password:
				</div>
				<div class="col-md-10">
					<input type="password" class="form-control">
				</div>
			</div>
			<div class="form">
				<form class="col-md-3 text-center" style="display: block;margin-left: auto;margin-right: auto;">
					<button class="btn btn-primary" id="btnLogin" style="width: 100%; margin-top: 1vh">Begin KYC</button>
					<br>
					<br>
					<a href="" id="link"></a>
					<br>
					<img id="imgQR" width="100%"/>
					<br>
					<button class="btn btn-primary disabled" id="btnSubmit" disabled="disabled">Submit</button>
				</form>
			</div>
		</div>
		<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
		
		<script type="text/javascript">
			$("#btnLogin").click(function(e) {
				e.preventDefault();
				
				var key = "w9JLaX5mRD5ZhPecUuUXZcNKcZY2QXpE";
				
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
							$("#imgQR").attr('src', 'https://chart.googleapis.com/chart?cht=qr&chs=200x200&chl=' + response.link);
							$("#btnLogin").removeClass('disabled').removeAttr('disabled').html("Generate Link");
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
