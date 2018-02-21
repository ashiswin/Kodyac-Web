<?php
	if(!isset($_GET['id'])) {
		die("No link ID provided!");
	}
	
	require_once "../scripts/utils/database.php";
	require_once "../scripts/connectors/CompanyConnector.php";
	require_once "../scripts/connectors/LinkConnector.php";
	
	$CompanyConnector = new CompanyConnector($conn);
	$LinkConnector = new LinkConnector($conn);
	
	$link = $LinkConnector->select($_GET['id']);
	
	if(!$link) {
		die("Invalid link ID provided!");
	}
	
	$company = $CompanyConnector->select($link[LinkConnector::$COLUMN_COMPANYID]);
	$methods = explode("|", $company[CompanyConnector::$COLUMN_METHODS]);
?>

<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $company[CompanyConnector::$COLUMN_NAME]; ?>'s KYC Procedure</title>
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
			html, body {
				margin: 0;
				height: 100%;
				overflow: hidden
			}
			.row.main-content {
				height: 100%;
			}

			.scrollable {
				overflow-y: auto !important;
				overflow-x: auto;
				height: 90vh;
			}
			.center {
				margin: auto;
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
		<!-- HTML file containing the navbar to reduce repetition of code -->
		<nav class="navbar navbar-toggleable-md" style="border-bottom: 1px solid #CCCCCC;">
			<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<a class="navbar-brand" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $_GET['id']; ?>"><img src="../img/logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
			<span style="font-family: 'Ubuntu', Arial, sans-serif">KodYaC</span></a>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<div class="ml-auto">
					<div class="progress" style="width: 90vh; margin-top: 1vh;">
						<div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
						<span id="percentage" style="margin-left: 1vh">0 out of <?php echo count($methods); ?></span>
					</div>
				</div>
				<button class="ml-auto btn btn-success disabled" disabled="true">Complete</button>
			</div>
		</nav>
		<div class="container-fluid"><!-- To get it to take up the whole width -->
			<div class="row main-content">
				<!-- Create side pane with events -->
				<div class="col-md-3 col-xs-3">
					<div class="scrollable">
						<table class="table table-hover">
							<?php
								if(in_array("sms", $methods)) {
									echo "<tr id=\"mtdSMS\"><td><h4>SMS Verification</h4><div style=\"font-size: 12px; color: red;\">Status: Incomplete</div></td></tr>";
								}
								if(in_array("nric", $methods)) {
									echo "<tr id=\"mtdNRIC\"><td><h4>NRIC Verification</h4><div style=\"font-size: 12px; color: red;\">Status: Incomplete</div></td></tr>";
								}
								if(in_array("biometric", $methods)) {
									echo "<tr id=\"mtdBiometric\"><td><h4>Biometric Verification</h4><div style=\"font-size: 12px; color: red;\">Status: Incomplete</div></td></tr>";
								}
							?>
						</table>
					</div>
				</div>
				<!-- Begin main detail view -->
				<div class="col-md-9 col-xs-9 scrollable" style="border-left: 1px solid #CCCCCC;">
					<!-- Begin Details panel -->
					<div class="detail-pane" id="SMSPane">
						<h1 style="margin-top: 2vh">SMS Verification</h1>
						<br>
						<form class="form form-inline">
							<div class="form-group">
								<select id="slcCountryCode" class="form-control"><?php echo file_get_contents('countrycodes.txt'); ?></select>
								<input type="text" placeholder="Phone number" class="form-control" id="txtNumber" />
								<button class="btn btn-primary" id="btnSendSMS">Send SMS</button>
								<button class="btn btn-secondary" id="btnResendSMS">Resend SMS</button>
							</div>
						</form>
						<br>
						<form class="form form-inline">
							<div class="form-group">
								<input type="text" placeholder="One-Time Pass" class="form-control" id="txtOTP" />
								<button class="btn btn-primary" id="btnVerifyOTP">Verify OTP</button>
							</div>
						</form>
						
					</div>
				</div>
			</div>
		</div>
		<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
		<script type="text/javascript">
			var linkId = <?php echo $_GET['id']; ?>;
			
			$("#mtdSMS").click(function() {
				$(".detail-pane").hide();
				
				$("#SMSPane").show();
				$(this).addClass("bg-info");
			});
			$("#mtdSMS").trigger('click');
			
			$("#btnSendSMS").click(function(e) {
				e.preventDefault();
				
				var number = $("#txtNumber").val();
				if(!number || number.length == 0) {
					$("#txtNumber")[0].setCustomValidity("Please enter your mobile phone number");
		                	$("#txtNumber")[0].reportValidity();
					
					return;
				}
				
				var fullNumber = "+" + $("#slcCountryCode").val() + number;
				$("#btnSendSMS").html("<i class=\"fas fa-sync-alt spinning\"></i> Sending").addClass('disabled').attr("disabled", "disbled");
				$.post("../scripts/SendSMSOTP.php", { number: fullNumber, linkId: linkId }, function(data) {
					response = JSON.parse(data);
					if(response.success) {
						$("#btnSendSMS").removeClass('disabled').removeAttr('disabled').addClass('btn-success').html("<i class=\"fas fa-sync-tick\"></i> Sent");
					}
					else {
						$("#btnSendSMS").removeClass('disabled').removeAttr('disabled').addClass('btn-danger').html("<i class=\"fas fa-sync-times\"></i> Send Failed");
					}
					setTimeout(function(){
						$("#btnSendSMS").removeClass('btn-danger').removeClass('btn-success').addClass('btn-primary')
					}, 2000);
				});
		</script>
	</body>
</html>
