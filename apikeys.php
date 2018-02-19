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
			<div class="alert alert-success alert-dismissible fade show" role="alert" id="altApiKeyCreated">
				<strong>Success!</strong> API key was created!
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="alert alert-success alert-dismissible fade show" role="alert" id="altApiKeyDeleted">
				<strong>Success!</strong> API key was deleted!
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
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
		<div class="modal fade" id="mdlCreateKey">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Create API Key</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-2">
								<label for="txtKeyName">Name:</label>
							</div>
							<div class="col-md-10">
								<input type="text" class="form-control" id="txtKeyName">
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-success" data-dismiss="modal" id="btnAdd">Add</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="mdlDeleteKey">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Delete API Key</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<p>Are you sure you want to delete API key <span id="apiKeyName"></span>?</p>
						<input type="hidden" id="hdnKeyId" />
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnDelete">Delete</button>
					</div>
				</div>
			</div>
		</div>
		<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
		<script type="text/javascript">
			$("#navAPIKeys").addClass('active');
			$("#altApiKeyCreated").hide();
			$("#altApiKeyDeleted").hide();
			
			var companyId = <?php echo $_SESSION['companyId']; ?>;
			var keys = null;
			
			function loadKeys() {
				$.get("scripts/GetAPIKeys.php?companyId=" + companyId, function(data) {
					response = JSON.parse(data);
					if(response.success) {
						var tblKeys = "";
						keys = response.keys;
						$(".delete").off( "click");
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
							var i = $(this).attr('href');
							
							$("#apiKeyName").html(keys[i].name);
							$("#hdnKeyId").val(keys[i].id);
							$("#mdlDeleteKey").modal();
						});
						
						$("#create").click(function(e) {
							e.preventDefault();
							$("#mdlCreateKey").modal();
						});
						
					}
				});
			}
			$("#btnDelete").click(function(e) {
				var id = $("#hdnKeyId").val();
				$.post("scripts/DeleteAPIKey.php", { id: id }, function(data) {
					response = JSON.parse(data);
					if(response.success) {
						$("#altApiKeyDeleted").show();
						$("#hdnKeyId").val("");
						loadKeys();
					}
				});
			});
			$("#btnAdd").click(function(e) {
				var name = $("#txtKeyName").val();
				$.post("scripts/CreateAPIKey.php", { companyId: companyId, name: name }, function(data) {
					response = JSON.parse(data);
					if(response.success) {
						$("#altApiKeyCreated").show();
						$("#txtKeyName").val("");
						loadKeys();
					}
				});
			});
			loadKeys();
		</script>
	</body>
</html>
