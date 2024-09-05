<?php
include_once '/home/swardgsi/public_html/includes/head.php';

$main_id = "1EsfHL3-RT7QGVAGxhx1Bs1rJ4gftY99RFvsl4wr3JoY";
$override_id = "14cSUMuTyZrY7yDFxdQ_s_zxqy51MxQVsafOxQOz-aRY";
$presentation_id = $main_id;
$presentation_div_visibility = '';
$alert_div_visibility = 'hidden';



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
		echo "no wakealerts<br />";
	}
}

$dismiss_alert = false;
if (isset($_GET['alert'])) {
	if ($_GET['alert'] == 0) {
		$dismiss_alert = true;
	}
}

$enable_wakealerts = false;
if (count($alerts) > 0 and !($dismiss_alert)) {
	foreach ($alerts as $alert) {
		if ($alert['alert_code'] != 0) {
			$enable_wakealerts = true;
			break;
		}
	}
} else {
	echo "no wakealerts<br />";
}

if ($enable_wakealerts) {
	$alert_div_visibility = '';
	$presentation_div_visibility = 'hidden';
} else if (isset($_GET['override'])) {
	if ($_GET['override'] == 1) {
		$presentation_id = $override_id;
	}
}
?>

<!DOCTYPE html>
<html>
<head>
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

				$alert_name = $alert['alert_name'];
				$banner_text = $alert['banner_text'];

				$alert_string = "<div class='$class_name'>" .
							"<div class='row'>" .
							"<div class='columns medium-24'>" .
							"<h1>$alert_name</h1>" .
							"</div></div>" .
							"<div class='row'>" .
							"<div class='columns medium-24'>" .
							"<h1>$banner_text</h1>" .
							"</div></div></div>";
				$alert_css .= '}';
				$alerts_html[] = $alert_string;
				$alerts_css[] = $alert_css;
				$alert_number += 1;
			}
		}
		foreach ($alerts_css as $css) {
			echo $css;
		}
		?>
		.responsive-google-slides {
			position: relative;
			padding-bottom: 56.25%; /* 16:9 Ratio, (9/16)*100% = 56.25% */
			height: 0;
			overflow: hidden;
		}
		.responsive-google-slides iframe {
			border: 0;
			position: absolute;
			top: 0;
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
				<p><a href="./embed.php?alert=1">Enable alerts</a></p>
			</div>
			<div class='columns small-8'>
				<p><a href="./embed.php?main=1&alert=0">Main presentation</a></p>
			</div>
			<div class='columns small-8'>
				<p><a href="./embed.php?override=1&alert=0">Override presentation</a></p>
			</div>
		</div>
		<div class=<?=$alert_div_visibility?>>
		<?php
		foreach ($alerts_html as $alert) {
			echo $alert;
		}
		?>
		</div>
	</div>
	<div class="responsive-google-slides <?=$presentation_div_visibility?>">
		<iframe src="https://docs.google.com/presentation/d/<?=$presentation_id?>/embed?start=true&loop=true&delayms=10000&rm=minimal" allowfullscreen="true" mozallowfullscreen="true" webkitallowfullscreen="true"></iframe>
	</div>
</div>
</body>
</html>










