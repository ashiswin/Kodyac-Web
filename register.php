<!DOCTYPE html>
<html>
	<head>
		<title>KodYaC - Registration</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
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
				src: url("font/Ubuntu-B.ttf") format("truetype");
			}
		</style>
	</head>
	<body>
		<nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse">
			<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<a class="navbar-brand" href="/"><img src="img/logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
 KodYaC</a>
		</nav>
		<div class="bg-inverse">
			<h1 style="font-family: 'Ubuntu', Arial, sans-serif; text-align: center; padding: 5vh; color: #FFFFFF; font-size: 3em">Registration</h1>
		</div>
		<div class="form" style="margin-top: 2vh">
			<div class="col-md-3 text-center" style="display: block;margin-left: auto;margin-right: auto;">
				<input type="text" class="form-control" style="margin-right: 1vh" placeholder="Username" id="txtUsername">
				<input type="password" class="form-control" style="margin-right: 1vh" placeholder="Password" id="txtPassword">
				<button class="btn btn-primary" id="btnLogin" style="width: 100%; margin-top: 1vh">Log In</button>
				<a href="register.php" style="margin-top: 1vh">Register now</a>
			</div>
		</div>
		<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
	</body>
</html>
