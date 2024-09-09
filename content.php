<?php
if (isset($_GET['type']) and $_GET['type'] == 'slides') {
	$presentation_key_filepath = 'cache.current_presentation_key.json';
	$presentation_key = file_get_contents($presentation_key_filepath);
	if (!$presentation_key) {
		echo "<p style='position: absolute; z-index: 20; background: white; border: solid; border-color: black; border-width: thick;'>".
			"Error: failed to retrieve presentation key.</p>";
	}
	$presentation_key = json_decode($presentation_key, $associative = true);
	$presentation_id = '';
	include 'bulletin_board_config.php';
	if (isset($presentation_key['key'])) {
		$presentation_key = $presentation_key['key'];
		$presentation_id = $presentations[$presentation_key];
	} else {
		echo 'Error: No presentation key set.<br />';
	}
}
// $curl = curl_init('https://wakealert.wfu.edu/test_banner.json');
$curl = curl_init('https://wakealert.wfu.edu/banner.json');
// $curl = curl_init('https://swardg.sites.wfu.edu/data/test_banner.json');
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
// 		if ($alert['alert_code'] == 1 or $alert['alert_code'] == 2) {
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
// 		}
	}
	foreach ($alerts_css as $css) {
		echo $css;
	}
	?>
	.wakealerts-layer {
/*		background-color: white;*/
		position: absolute;
		z-index: 3; /* put wakealerts above slides and base layer */
/*			top: 0;
		left: 0;*/
		width: 100%;
		height: 100%;
/*		opacity: 0.5;*/
	}
	.google-slides-layer {
		position: relative;
		padding-bottom: 56.25%; /* 16:9 Ratio, (9/16)*100% = 56.25% */
		z-index: 2;
		height: 0;
		overflow: hidden;
/*		opacity: 0.5;*/
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
	<div id='last-updated' data-value='<?=$unix_timestamp?>'></div>
</div>
<?php
	if (!isset($_GET['type'])) {
		echo "Error: Pass a type (either Alerts or Slides) to this page.<br />";
		return false;
	}
	if ($_GET['type'] == 'alerts') {
		echo '<div class="wakealerts-layer">';
		foreach ($alerts_html as $alert) {
			echo $alert;
		}
	} elseif ($_GET['type'] == 'slides') {
		echo '</div>';
		echo '<div class="google-slides-layer">';
		echo '<iframe id="slides-iframe" src="https://docs.google.com/presentation/d/'.$presentation_id.'/embed?start=true&loop=true&delayms=10000&rm=minimal" allowfullscreen="true" mozallowfullscreen="true" webkitallowfullscreen="true"></iframe>';
		echo '</div>';
	} elseif ($_GET['type'] == 'background') {
		echo '<div class="base-layer">';
			echo '<img src="dunes-at-dusk.jpg" alt="Desert dunes at dusk">';
		echo '</div>';
	}
?>










