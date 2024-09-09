<?php
$alerts_visibility = 0;
if (isset($_GET['toggle_alerts'])) {
	$alerts_visibility = $_GET['toggle_alerts'];
}

$slides_override = 0;
if (isset($_GET['toggle_slides'])) {
	$slides_override = $_GET['toggle_slides'];
}

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
		echo "No wakealerts<br />";
	}
}

$ET = new DateTimeZone('US/Eastern');
$now = new DateTime('now', $ET);
$unix_timestamp = $now->format('U');
?>

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
		top: 0px;
		left: 0;
		width: 100%;
		height: 100%;
	}
</style>
<div class='hidden'>
	<div id='alerts-visibility' data-value='<?=$alerts_visibility?>'></div>
	<div id='slides-override' data-value='<?=$slides_override?>'></div>
	<div id='last-updated' data-value='<?=$unix_timestamp?>'></div>
</div>
<div id='last-updated' data-value='<?=$unix_timestamp?>'></div>
<div class="wakealerts-layer">
	<?php
	if ($alerts_visibility == 1) {
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

	if ($slides_override) {
		$presentation_id = $override_id;
	}
	?>
	<iframe id="slides-iframe" src="https://docs.google.com/presentation/d/<?=$presentation_id?>/embed?start=true&loop=true&delayms=10000&rm=minimal" allowfullscreen="true" mozallowfullscreen="true" webkitallowfullscreen="true"></iframe>
</div>
<div class="base-layer">
	<img src="dunes-at-dusk.jpg" alt="Desert dunes at dusk">
</div>










