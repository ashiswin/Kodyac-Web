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
?>

<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $company[CompanyConnector::$COLUMN_NAME]; ?>'s KYC Procedure</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
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
		</style>
	</head>
	<body>
		<div class="container-fluid"><!-- To get it to take up the whole width -->
			<div class="row main-content">
				<!-- Create side pane with events -->
				<div class="col-md-4 col-xs-4">
					<div class="scrollable">
						<table class="table table-hover">
							<?php
								$methods = explode("|", $company[CompanyConnector::$COLUMN_METHODS]);
								
								if(in_array("sms", $methods)) {
									echo "<tr id=\"mtdSMS\"><td class=\"bg-danged\"><h4>SMS Verification</h4><div style=\"font-size: 12px; color: #AAAAAA;\">Status: Incomplete</div></td></tr>";
								}
							?>
						</table>
					</div>
				</div>
				<!-- Begin main detail view -->
				<div class="col-md-8 col-xs-8 scrollable" style="border-left: 1px solid #CCCCCC;">
					<!-- Begin Details panel -->
					<div id="detailsPane">
					</div>
				</div>
			</div>
		</div>
		<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
	</body>
</html>
