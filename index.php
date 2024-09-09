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
		$('#background').load('content.php?type=background');
		$('#slides').load('content.php?type=slides');
		$('#alerts').load('content.php?type=alerts');

		setInterval(function() {
			$('#alerts').load('content.php?type=alerts&randval='+ Math.random());
		}, 10000);
	});
</script>
	<div id="alerts">
	</div>
	<div id="slides">
	</div>
	<div id="background">
	</div>
</body>
</html>