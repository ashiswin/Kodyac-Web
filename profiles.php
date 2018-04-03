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
			@media (max-width: 992px) {
				.container {
					width: 100%;
				}
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
			<div class="modal-dialog modal-lg" role="document">
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
								<div class="row">
									<div class="col-md-10">
										<h1 id="mdlProfileName"></h1>
									</div>
									<div class="col-md-2">
										<span class="badge" id="bdgStatus"></span>
									</div>
								</div>
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
								<br>
								Sex:&nbsp;<span id="mdlProfileSex"></span>
								<br>
								Race:&nbsp;<span id="mdlProfileRace"></span>
							</div>
							<div class="col-md-4">
								<img id="mdlProfilePicture" width="100%" />
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
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
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
				
				if(status == "all") {
					$("#tblProfiles").html(tblProfiles);
				}
				else {
					loadSubTable(status);
				}
				
				$(".viewprofile").click(function(e) {
					var subprofiles = null;
					if(status == "all") subprofiles = profiles;
					if(status == "requested") subprofiles = requested;
					if(status == "inprogress") subprofiles = inprogress;
					if(status == "completed") subprofiles = completed;
					if(status == "cancelled") subprofiles = cancelled;
					e.preventDefault();
				
					var i = $(this).attr('href');
					$("#mdlProfileName").html(subprofiles[i].name);
					$("#mdlProfileDOB").html(moment(subprofiles[i].dob, "YYYY-MM-DD").format('MMMM Do YYYY'));
					$("#mdlProfileNationality").html(subprofiles[i].nationality);
					$("#mdlProfileNRIC").html(subprofiles[i].nric);
					$("#mdlProfileContact").html(subprofiles[i].contact);
					$("#mdlProfileAddress").html(subprofiles[i].address);
					$("#mdlProfileSex").html(subprofiles[i].sex);
					$("#mdlProfileRace").html(subprofiles[i].race);
					$.ajax({
						url:'uploads/link_' + subprofiles[i].linkId,
						type:'GET',
						error: function(){
							$("#mdlProfilePicture").attr('src', 'http://via.placeholder.com/150x200')
						},
						success: function(){
							$("#mdlProfilePicture").attr('src', 'uploads/link_' + subprofiles[i].linkId)
						}
					});
					$("#mdlViewProfile").modal();
				});
			});
			
			$(".viewprofile").click(function(e) {
				e.preventDefault();
				
				var i = $(this).attr('href');
				$("#mdlProfileName").html(profiles[i].name);
				$("#mdlProfileDOB").html(moment(profiles[i].dob, "YYYY-MM-DD").format('MMMM Do YYYY'));
				$("#mdlProfileNationality").html(profiles[i].nationality);
				$("#mdlProfileNRIC").html(profiles[i].nric);
				$("#mdlProfileContact").html(profiles[i].contact);
				$("#mdlProfileAddress").html(profiles[i].address);
				$("#mdlProfileSex").html(profiles[i].sex);
				$("#mdlProfileRace").html(profiles[i].race);
				$.ajax({
					url:'uploads/link_' + profiles[i].linkId,
					type:'GET',
					error: function(){
						$("#mdlProfilePicture").attr('src', 'http://via.placeholder.com/150x200')
					},
					success: function(){
						$("#mdlProfilePicture").attr('src', 'uploads/link_' + profiles[i].linkId)
					}
				});
				
				$("#bdgStatus").removeClass("badge-*");
				
				if(profiles[i].status == "requested") $("#bdgStatus").addClass("badge-default");
				if(profiles[i].status == "inprogress") $("#bdgStatus").addClass("badge-primary");
				if(profiles[i].status == "completed") $("#bdgStatus").addClass("badge-success");
				if(profiles[i].status == "cancelled") $("#bdgStatus").addClass("badge-default");
				$("#mdlViewProfile").modal();
			});
			
			function loadSubTable(status) {
				var tblProfiles = "";
				var profiles = null;
				
				if(status == "requested") profiles = requested;
				if(status == "inprogress") profiles = inprogress;
				if(status == "completed") profiles = completed;
				if(status == "cancelled") profiles = cancelled;
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
				}
				$("#tblProfiles").html(tblProfiles);
			}
		</script>
	</body>
</html>
