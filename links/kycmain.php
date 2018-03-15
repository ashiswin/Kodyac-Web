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
	$completedMethods = explode("|", $link[LinkConnector::$COLUMN_COMPLETEDMETHODS]);
	if(strcmp($completedMethods[0], "") == 0) {
		unset($completedMethods[0]);
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $company[CompanyConnector::$COLUMN_NAME]; ?>'s KYC Procedure</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
		<script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
		<link rel="stylesheet" href="common.css">
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
						<div id="prgCompletion" class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
						<span id="percentage" style="margin-left: 1vh"><span id="completionCount"></span> out of <span id="methodCount"></span> complete</span>
					</div>
				</div>
				<button class="ml-auto btn btn-success disabled" disabled="true" id="btnComplete">Complete</button>
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
									echo "<tr id=\"mtdSMS\" class=\"method-items\"><td><h4>SMS Verification</h4><div style=\"font-size: 12px;\">Status: ";
									if(in_array("sms", $completedMethods)) {
										echo "<span id=\"mtdSMSStatus\" style=\"color: green\">Complete</span></div>";
									}
									else {
										echo "<span id=\"mtdSMSStatus\" style=\"color: red\">Incomplete</span></div>";
									}
									echo "</td></tr>";
								}
								if(in_array("myinfo", $methods)) {
									echo "<tr id=\"mtdMyInfo\" class=\"method-items\"><td><h4>Basic Information Verification</h4><div style=\"font-size: 12px;\">Status: ";
									if(in_array("myinfo", $completedMethods)) {
										echo "<span id=\"mtdMyInfoStatus\" style=\"color: green\">Complete</span></div>";
									}
									else {
										echo "<span id=\"mtdMyInfoStatus\" style=\"color: red\">Incomplete</span></div>";
									}
									echo "</td></tr>";
								}
								if(in_array("nric", $methods)) {
									echo "<tr id=\"mtdNRIC\" class=\"method-items\"><td><h4>Photo Verification</h4><div style=\"font-size: 12px;\">Status: ";
									if(in_array("nric", $completedMethods)) {
										echo "<span id=\"mtdNRICStatus\" style=\"color: green\">Complete</span></div>";
									}
									else {
										echo "<span id=\"mtdNRICStatus\" style=\"color: red\">Incomplete</span></div>";
									}
									echo "</td></tr>";
								}
								if(in_array("video", $methods)) {
									echo "<tr id=\"mtdVideo\" class=\"method-items\"><td><h4>Video Verification</h4><div style=\"font-size: 12px\">Status: ";
									if(in_array("video", $completedMethods)) {
										echo "<span id=\"mtdVideoStatus\" style=\"color: green\">Complete</span></div>";
									}
									else {
										echo "<span id=\"mtdVideoStatus\" style=\"color: red\">Incomplete</span></div>";
									}
									echo "</td></tr>";
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
					<div class="detail-pane" id="MyInfoPane">
						<h1 style="margin-top: 2vh">Basic Information Verification</h1>
						<br>
						<div id="barcodeStream" style="margin-bottom: 2vh"></div>
						<form class="form form-inline">
							<div class="form-group">
								<input type="text" placeholder="NRIC" class="form-control disabled" id="txtNRIC" disabled="disabled" />
								<button class="btn btn-primary" id="btnScan">Start Scan</button>
								<button class="btn btn-secondary" id="btnStopScan">Stop Scan</button>
							</div>
						</form>
						<br>
						<table class="table borderless">
							<tr>
								<td>Name:</td><td id="txtScanName"></td>
							</tr>
							<tr>
								<td>Sex:</td><td id="txtScanSex"></td>
							</tr>
							<tr>
								<td>Race:</td><td id="txtScanRace"></td>
							</tr>
							<tr>
								<td>Date of Birth:</td><td id="txtScanDOB"></td>
							</tr>
							<tr>
								<td>Address:</td><td id="txtScanAddress"></td>
							</tr>
						</table>
						<br>
						<button class="btn btn-success" id="btnScanConfirm">Confirm Scan</button>
					</div>
					<div class="detail-pane" id="NRICPane">
						<h1 style="margin-top: 2vh">Photo Verification</h1>
					</div>
					<div class="detail-pane" id="VideoPane">
						<h1 style="margin-top: 2vh">Video Verification</h1>
					</div>
				</div>
			</div>
		</div>
		<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
		<script src="dist/quagga.min.js"></script>
		<script type="text/javascript">
			var linkId = <?php echo $_GET['id']; ?>;
			var totalMethods = <?php echo count($methods); ?>;
			var completionCount = <?php echo count($completedMethods); ?>;
			var link = JSON.parse("<?php echo(addslashes(json_encode($link))); ?>");
			var completedMethods = link.completedMethods;
			
			$("#btnStopScan").hide();
			$("#btnScanConfirm").hide();
			// Change status of link to In Progress
			$.post("../scripts/BeginKYC.php", { id: linkId }, function(data) {});
			
			function notifyCompletion() {
				$("#prgCompletion").css('width', (completionCount * 100 / totalMethods) + '%');
				$("#methodCount").html(totalMethods);
				$("#completionCount").html(completionCount);
				
				if(totalMethods == completionCount) {
					$("#btnCompleted").removeAttr('disabled');
				}
				
				if(completedMethods != null && completedMethods.includes("sms")) {
					smsVerified();
				}
				if(completedMethods != null && completedMethods.includes("myinfo")) {
					myinfoVerified();
				}
			}
			
			function smsVerified() {
				$("#btnVerifyOTP").addClass('btn-success').html("<i class=\"fas fa-check\"></i> Verified").addClass('disabled').attr("disabled", "disbled");
				$("#btnSendSMS").addClass('disabled').attr("disabled", "disbled");
				$("#txtNumber").addClass('disabled').attr("disabled", "disbled");
				$("#txtOTP").addClass('disabled').attr("disabled", "disbled");
				$("#slcCountryCode").hide();
			}
			
			function myinfoVerified() {
				$("#btnScan").attr('disabled', 'disabled').addClass('disabled');
				$("#btnStopScan").attr('disabled', 'disabled').addClass('disabled');
			}
			
			notifyCompletion();
			
			if(completedMethods != null && completedMethods.includes("sms")) {
				$("#txtNumber").val(link.contact);
			}
			if(completedMethods != null && completedMethods.includes("myinfo")) {
				$("#txtScanName").html(link.name);
				$("#txtScanSex").html(link.sex);
				$("#txtScanRace").html(link.nationality);
				$("#txtScanAddress").html(link.address);
				$("#txtScanDOB").html(link.dob);
			}
			$("#mtdSMS").click(function() {
				$(".detail-pane").hide();
				$(".method-items").removeClass("bg-info");
				
				$("#SMSPane").show();
				$(this).addClass("bg-info");
			});
			$("#mtdMyInfo").click(function() {
				$(".detail-pane").hide();
				$(".method-items").removeClass("bg-info");
				
				$("#MyInfoPane").show();
				$(this).addClass("bg-info");
			});
			$("#mtdNRIC").click(function() {
				$(".detail-pane").hide();
				$(".method-items").removeClass("bg-info");
				
				$("#NRICPane").show();
				$(this).addClass("bg-info");
			});
			$("#mtdVideo").click(function() {
				$(".detail-pane").hide();
				$(".method-items").removeClass("bg-info");
				
				$("#VideoPane").show();
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
						$("#btnSendSMS").removeClass('disabled').removeAttr('disabled').addClass('btn-success').html("<i class=\"fas fa-check\"></i> Sent");
						setTimeout(function(){
							$("#btnSendSMS").removeClass('btn-danger').removeClass('btn-success').addClass('btn-secondary').html("Resend SMS");
						}, 2000);
					}
					else {
						$("#btnSendSMS").removeClass('disabled').removeAttr('disabled').addClass('btn-danger').html("<i class=\"fas fa-times\"></i> Send Failed");
						setTimeout(function(){
							$("#btnSendSMS").removeClass('btn-danger').removeClass('btn-success').addClass('btn-primary').html("Send SMS");
						}, 2000);
					}
				});
			});
			
			$("#btnVerifyOTP").click(function(e) {
				e.preventDefault();
				
				var otp = $("#txtOTP").val();
				if(!otp || otp.length == 0) {
					$("#txtOTP")[0].setCustomValidity("Please enter the OTP you received");
		                	$("#txtOTP")[0].reportValidity();
					
					return;
				}
				
				$("#btnVerifyOTP").html("<i class=\"fas fa-sync-alt spinning\"></i> Verifying").addClass('disabled').attr("disabled", "disbled");
				$.post("../scripts/VerifySMSOTP.php", { otp: otp, linkId: linkId }, function(data) {
					response = JSON.parse(data);
					if(response.success) {
						$.post("../scripts/AddMethodCompletion.php", { method: "sms", linkId: linkId }, function(data2) {
							$("#btnVerifyOTP").addClass('btn-success').html("<i class=\"fas fa-check\"></i> Verified");
							$("#mtdSMSStatus").css('color', 'green').html("Complete");
							completionCount++;
							if(completedMethods == null) {
								completedMethods = ["sms"];
							}
							else {
								completedMethods.push("sms");
							}
							notifyCompletion();
						});
					}
					else {
						$("#btnVerifyOTP").removeClass('disabled').removeAttr('disabled').addClass('btn-danger').html("<i class=\"fas fa-times\"></i> Verification Failed");
						$("#txtOTP")[0].setCustomValidity(response.message);
		                		$("#txtOTP")[0].reportValidity();
						
						setTimeout(function(){
							$("#btnVerifyOTP").removeClass('btn-danger').addClass('btn-primary').html("Verify");
						}, 2000);
					}
				});
			});
			
			$("#btnScan").click(function(e) {
				e.preventDefault();
				
				Quagga.init({
					inputStream : {
						name : "Live",
						type : "LiveStream",
						target: document.querySelector('#barcodeStream')    // Or '#yourElement' (optional)
					},
					decoder : {
						readers : ["code_39_reader"]
					}
				}, function(err) {
					if (err) {
						console.log(err);
						return
					}
					console.log("Initialization finished. Ready to start");
					Quagga.start();
					$("#barcodeStream").show();
					$(".drawingBuffer").hide();
					$("#btnScan").hide();
					$("#btnStopScan").show();
				});
			});
			
			$("#btnStopScan").click(function(e) {
				e.preventDefault();
				$("#barcodeStream").hide();
				Quagga.stop();
				$(this).hide();
				$("#btnScan").show();
			});
			
			Quagga.onDetected(function(result) {
				var code = result.codeResult.code;

				$("#txtNRIC").val(code);
				$("#barcodeStream").hide();
				Quagga.stop();
				$("#btnStopScan").hide();
				$("#btnScan").show();
				
				$.get("../scripts/GetMyInfo.php?nric=" + code, function(data) {
					response = JSON.parse(data);
					if(response.success) {
						$("#txtScanName").html(response.details.name);
						$("#txtScanSex").html(response.details.sex);
						$("#txtScanRace").html(response.details.race);
						$("#txtScanDOB").html(response.details.dob);
						$("#txtScanAddress").html(response.details.address);
						$("#btnScanConfirm").show();
					}
				});
			});
			
			$("#btnScanConfirm").click(function(e) {
				e.preventDefault();
				var name = $("#txtScanName").html();
				var sex = $("#txtScanSex").html();
				var race = $("#txtScanRace").html();
				var dob = $("#txtScanDOB").html();
				var address = $("#txtScanAddress").html();
				
				$.post("../scripts/VerifyMyInfo.php", { name: name, sex: sex, nationality: race, dob: dob, address: address, linkId: linkId }, function(data) {
					response = JSON.parse(data);
					if(response.success) {
						$.post("../scripts/AddMethodCompletion.php", { method: "myinfo", linkId: linkId }, function(data2) {
							completionCount++;
							if(completedMethods == null) {
								completedMethods = ["myinfo"];
							}
							else {
								completedMethods.push("myinfo");
							}
							notifyCompletion();
						});
					}
				});
			});
				
		</script>
	</body>
</html>
