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
