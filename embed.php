<?php
// $curl = curl_init('https://wakealert.wfu.edu/test_banner.json');
$curl = curl_init('https://swardg.sites.wfu.edu/data/test_banner.json');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($curl);
$err = curl_error($curl);

$alerts = array();

if ($err) {
	echo "cURL error: ". $err ."<br />";
} else {
	$response = json_decode($response, $associative=true);
	if (isset($response['alerts'])) {
		$alerts = $response['alerts'];
	} else {
		echo "No wakealerts.<br />";
	}
}
?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<title>Bulletin Board | Wake Forest School of Law</title>
	<script src="https://code.jquery.com/jquery-latest.js"></script>
	<?php include_once '/home/swardgsi/public_html/includes/head.php';?>
	<style>
		<?php
		$alerts_html = array();
		$alerts_css = array();
		$alert_number = 0;

		foreach ($alerts as $alert) {
			if ($alert['alert_code'] == 1 or $alert['alert_code'] == 2) {
				$class_name = "alert-$alert_number";

				$alert_css = ".$class_name {";
				$alert_css .= ' border: solid;';
				$alert_css .= ' border-color: ' . $alert['alert_color'] . ';';
				$alert_css .= ' border-width: thick; ';
				$alert_css .= ' background-color: ' . $alert['override_background_color'] . ';';
				$alert_css .= ' h1 {color: ' . $alert['override_text_color'] . ';}';
				$alert_css .= ' a {color: ' . $alert['override_link_color'] . ' !important;}';
				$alert_css .= '}';
				$alerts_css[] = $alert_css;

				$alert_string = "<div class='$class_name'>" .
							"<div class='row'>" .
							"<div class='columns medium-24'>" .
							"<h1>". $alert['alert_name'] ."</h1>" .
							"</div></div>" .
							"<div class='row'>" .
							"<div class='columns medium-24'>" .
							"<h1>". $alert['banner_text'] ."</h1>" .
							"</div></div></div>";
				$alerts_html[] = $alert_string;
				$alert_number += 1;
			}
		}
		foreach ($alerts_css as $css) {
			echo $css;
		}
		?>
		.wakealerts-layer {
			position: absolute;
			z-index: 3; /* put wakealerts above slides and base layer */
/*			top: 0;
			left: 0;*/
			width: 100%;
			height: 100%;
			opacity: 0.5;
		}
		.google-slides-layer {
			position: relative;
			padding-bottom: 56.25%; /* 16:9 Ratio, (9/16)*100% = 56.25% */
			z-index: 2;
			height: 0;
			overflow: hidden;
			opacity: 0.5;
			iframe {
				border: 0;
				position: absolute;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
			}
		}
		.base-layer {
			position: absolute;
			z-index: 0;
			top: 56px;
			left: 0;
			width: 100%;
			height: 100%;
		}
	</style>
</head>
<body>
<div class="container">
	<div class='row'>
		<div class='columns small-8'>
			<!-- I want to invoke a function to switch from 0 to 1 and vice versa. -->
			<p><a href="./embed.php?toggle_alerts=1">toggle alerts</a></p>
		</div>
		<div class='columns small-8'>
			<p><a href="./embed.php?toggle_slides=0&toggle_alerts=0">default slides</a></p>
		</div>
		<div class='columns small-8'>
			<p><a href="./embed.php?toggle_slides=1&toggle_alerts=0">override presentation</a></p>
		</div>
	</div>
	<div class="wakealerts-layer">
	<?php
	if (isset($_GET['toggle_alerts']) and $_GET['toggle_alerts'] == 1) {
		foreach ($alerts_html as $alert) {
			echo $alert;
		}
	}
	?>
	</div>
	<div class="google-slides-layer">
	<?php
		$presentation_id = "1EsfHL3-RT7QGVAGxhx1Bs1rJ4gftY99RFvsl4wr3JoY";
		$override_id = "14cSUMuTyZrY7yDFxdQ_s_zxqy51MxQVsafOxQOz-aRY";

		if (isset($_GET['toggle_slides'])) {
			if ($_GET['toggle_slides'] == 1) {
				$presentation_id = $override_id;
			}
		}
	?>
		<iframe src="https://docs.google.com/presentation/d/<?=$presentation_id?>/embed?start=true&loop=true&delayms=10000&rm=minimal" allowfullscreen="true" mozallowfullscreen="true" webkitallowfullscreen="true"></iframe>
	</div>
	<div class="base-layer">
		<img src="dunes-at-dusk.jpg" alt="Desert dunes at dusk">
	</div>
</div>
</body>
</html>










