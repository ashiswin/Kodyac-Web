<nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse">
	<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<a class="navbar-brand" href="/"><img src="img/logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
<span style="font-family: 'Ubuntu', Arial, sans-serif">KodYaC</span></a>
	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav">
			<li class="nav-item"><span class="nav-link">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Welcome <?php echo $company["name"]; ?></span></li>
		</ul>

		<ul class="navbar-nav ml-auto">
			<li id="navDashboard" class="nav-item"><a href="manage.php" class="nav-link">Dashboard</a></li>
			<li id="navProfiles" class="nav-item"><a href="profiles.php" class="nav-link">Profiles</a></li>
			<li id="navAPIKeys" class="nav-item"><a href="apikeys.php" class="nav-link">API Keys</a></li>
			<li id="navLogout" class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>
		</ul>
	</div>
</nav>
