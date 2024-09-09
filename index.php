<!doctype html>
<html class="no-js" lang="en" dir="ltr">
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<title>Bulletin Board | Wake Forest School of Law</title>
	<script src="https://code.jquery.com/jquery-latest.js"></script>
	<?php include_once '/home/swardgsi/public_html/includes/head.php';?>
</head>
<body>
<script>
	$(document).ready(function() {
		$('#content-container').load('content.php', function(response, status, xhr) {
			if (status == 'error') {
				alert('page not found.');
			} else {
				// var last_updated = $('#last-updated').data('value');
				// var last_updated = new Date(last_updated * 1000);
				// alert(last_updated);
			}
		});

		setInterval(function() {
			var slides_override = $('#slides-override').data('value');
			var alerts_visibility = $('#alerts-visibility').data('value');
			$('#content-container').load(`content.php?toggle_alerts=${alerts_visibility}&toggle_slides=${slides_override}`, function(response, status, xhr) {
				var last_updated = $('#last-updated').data('value');
				if (status == 'error') {
					var connection_error_time = last_updated + (20 * 60); //adds a grace period (20 mins) to the last_updated time stamp
					var now = Math.floor(Date.now() / 1000);
					if (now > connection_error_time) {
						alert ('Unable to connect to content page.');
					} else {
						// var last_updated = new Date(last_updated * 1000);
						// alert(last_updated);
					}
				} else {
					// var last_updated = new Date(last_updated * 1000);
					// alert(last_updated);
				}
			}
		)}, 600000); // looks for 

		// why doesn't this function work outside of the .ready function?
		$('#toggle-alerts').click(function() {
			var slides_override = $('#slides-override').data('value');
			var alerts_visibility = $('#alerts-visibility').data('value');
			if (alerts_visibility == 1) {
				alerts_visibility = 0;
			} else {
				alerts_visibility = 1;
			}
			// alert(`content.php?toggle-alerts=${alerts_visibility}`);
			$('#content-container').load(`content.php?toggle_alerts=${alerts_visibility}&toggle_slides=${slides_override}`);
		});

		$('#toggle-slides').click(function() { 
			var slides_override = $('#slides-override').data('value');
			var alerts_visibility = $('#alerts-visibility').data('value');
			if (slides_override == 1) {
				slides_override = 0;
			} else {
				slides_override = 1;
			}
			// alert(`content.php?toggle-alerts=${alerts_visibility}`);
			$('#content-container').load(`content.php?toggle_alerts=${alerts_visibility}&toggle_slides=${slides_override}`);
		});
	});
</script>
<div class="container">
<!-- 	<div class='row' style='opacity: 0.5;'>
		<div class='columns small-4'></div>
		<div class='columns small-4'></div>
		<div class='columns small-4' style='background-color: white;'> -->
		<div style='background-color: white; position: absolute; z-index:10;'>
			<p><a id='toggle-alerts'>toggle alerts</a></p>
		<!-- </div> -->
		<!-- <div class='columns small-4' style='background-color: white;'> -->
			<p><a id='toggle-slides'>toggle slides</a></p>
		</div>
<!-- 		</div>
		<div class='columns small-4'></div>
		<div class='columns small-4'></div> -->
	</div>
	<div id="content-container">
	</div>
</div>
</body>
</html>