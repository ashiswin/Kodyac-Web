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
	$profiles = $LinkConnector->selectByCompany($_SESSION['companyId']);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>KodYaC - Profile</title>
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
			<h1 style="font-family: 'Martel', Times New Roman, serif; font-weight: bold; text-align: center; padding: 5vh; color: #FFFFFF; font-size: 3em">Profiles</h1>
		</div>
		<div class="container">
			<ul class="nav nav-pills">
				<li class="nav-item">
					<a class="nav-link tabletab active" href="all">All</a>
				</li>
				<li class="nav-item">
					<a class="nav-link tabletab" id="tabRequested" href="requested">Requested</a>
				</li>
				<li class="nav-item">
					<a class="nav-link tabletab" id="tabInprogress" href="inprogress">In Progress</a>
				</li>
				<li class="nav-item">
					<a class="nav-link tabletab" id="tabCompleted" href="completed">Completed</a>
				</li>
				<li class="nav-item">
					<a class="nav-link tabletab" id="tabCancelled" href="cancelled">Cancelled</a>
				</li>
			</ul>
			<table class="table" style="margin-top: 2vh">
				<colgroup>
					<col span="1" style="width: 5%;">
					<col span="1" style="width: 10%;">
					<col span="1" style="width: 30%;">
					<col span="1" style="width: 10%;">
					<col span="1" style="width: 20%;">
					<col span="1" style="width: 20%;">
					<col span="1" style="width: 5%;">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Profile ID</th>
						<th>Profile Name</th>
						<th>Status</th>
						<th>Created On</th>
						<th>Completed On</th>
						<th><i class="fas fa-eye"></i></th>
					</tr>
				</thead>
				<tbody id="tblProfiles">
				</tbody>
			</table>
		</div>
		<div class="modal fade" id="mdlViewProfile">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">View Profile</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-8">
								<h1 id="mdlProfileName"></h1>
								<br>
								DOB:&nbsp;<span id="mdlProfileDOB"></span>
								<br>
								NRIC:&nbsp;<span id="mdlProfileNRIC"></span>
								<br>
								Contact:&nbsp;<span id="mdlProfileContact"></span>
								<br>
								Address:&nbsp;<span id="mdlProfileAddress"></span>
								<br>
								Nationality:&nbsp;<span id="mdlProfileNationality"></span>
							</div>
							<div class="col-md-4">
								<img id="mdlProfilePictore" width="100%" />
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
			$("#navProfiles").addClass('active');
			function pad(n, width, z) {
				z = z || '0';
				n = n + '';
				return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
			}
			
			function prettyStatus(status) {
				if(status == "requested") return "Requested";
				if(status == "inprogress") return "In Progress";
				if(status == "completed") return "Completed";
				if(status == "cancelled") return "Cancelled";
				return "Unknown";
			}
			
			function prettyNull(s) {
				if(s == null || s == "null" || s == "NULL" || s == "Invalid date") {
					return "-";
				}
				return s;
			}
			
			var profiles = JSON.parse("<?php echo addslashes(json_encode($profiles)); ?>");
			
			var requested = Array();
			var inprogress = Array();
			var completed = Array();
			var cancelled = Array();
			
			var tblProfiles = "";
			
			for(var i = 0; i < profiles.length; i++) {
				tblProfiles += "<tr>";
				tblProfiles += "<td>" + (i + 1) + "</td>";
				tblProfiles += "<td>" + pad(profiles[i].id, 10) + "</td>";
				tblProfiles += "<td>" + prettyNull(profiles[i].name) + "</td>";
				tblProfiles += "<td>" + prettyStatus(profiles[i].status) + "</td>";
				tblProfiles += "<td>" + moment(profiles[i].createdOn, "YYYY-MM-DD hh:mm:ss").format('MMMM Do YYYY') + "</td>";
				tblProfiles += "<td>" + prettyNull(moment(profiles[i].completedOn, "YYYY-MM-DD hh:mm:ss").format('MMMM Do YYYY')) + "</td>";
				tblProfiles += "<td><a class=\"viewprofile\" href=\"" + i + "\"><i class=\"fas fa-eye\"></i></a></td>";
				tblProfiles += "</tr>";
				
				if(profiles[i].status == "requested") requested.push(profiles[i]);
				else if(profiles[i].status == "inprogress") inprogress.push(profiles[i]);
				else if(profiles[i].status == "completed") completed.push(profiles[i]);
				else if(profiles[i].status == "cancelled") cancelled.push(profiles[i]);
			}
			
			if(requested.length == 0) {
				$("#tabRequested").addClass('disabled');
			}
			if(inprogress.length == 0) {
				$("#tabInprogress").addClass('disabled');
			}
			if(completed.length == 0) {
				$("#tabCompleted").addClass('disabled');
			}
			if(cancelled.length == 0) {
				$("#tabCancelled").addClass('disabled');
			}
			
			$("#tblProfiles").html(tblProfiles);
			$(".tabletab").click(function(e) {
				e.preventDefault();
				
				if($(this).hasClass('disabled')) return;
				
				$(".tabletab").removeClass('active');
				$(this).addClass('active');
				
				var status = $(this).attr('href');
			});
			$(".viewprofile").click(function(e) {
				e.preventDefault();
				
				var i = $(this).attr('href');
				$("#mdlProfileName").html(profiles[i].name);
				$("#mdlProfileDOB").html(profiles[i].dob);
				$("#mdlProfileNationality").html(profiles[i].nationality);
				$("#mdlProfileNRIC").html(profiles[i].nric);
				$("#mdlProfileContact").html(profiles[i].contact);
				$("#mdlProfileAddress").html(profiles[i].address);
				$("#mdlProfilePicture").attr('src', 'uploads/' + profiles[i].id + '.jpg');
				$("#mdlViewProfile").modal();
			});
		</script>
	</body>
</html>
