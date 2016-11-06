<?php

include( './includes/config.php' );
include( './includes/classes.php' );

// The API returns a lot more data than this
// but only these are currently being used in final output.
$wustorm_error_output =
$wustorm_weather_icon_img =
$wustorm_display_location_full =
$wustorm_display_location_city =
$wustorm_display_location_state =
$wustorm_display_location_state_name =
$wustorm_display_location_country =
$wustorm_display_location_zip =
$wustorm_observation_location_full =
$wustorm_observation_location_city =
$wustorm_observation_location_state =
$wustorm_observation_location_country =
$wustorm_observation_location_elevation =
$wustorm_current_observation_weather =
$wustorm_current_observation_temperature_string =
$wustorm_current_observation_relative_humidity =
$wustorm_current_observation_wind_string =
$wustorm_current_observation_wind_mph =
$wustorm_current_observation_wind_dir =
$wustorm_current_observation_precip_today_in =
$wustorm_current_observation_observation_time =
$wustorm_current_observation_icon_url = '';

$wustorm_display_location =
$wustorm_observation_location =
$wustorm_current_observation = array();

// Get data from Weather Underground
$wustorm_data = new MIS\WU\Wunderground_Data( $wustorm_api_key,$wustorm_api_zip );
$wustorm_response = $wustorm_data->get_response();

// Check for error messages
if ( $wustorm_response['error_msg'] ) {
	$wustorm_error_output = $wustorm_response['error_msg'];
}
elseif ( isset( $wustorm_response['response_error'] ) ) {
	$wustorm_error_output = $wustorm_response['response_error'];
}
// If no errors, get the various pieces of weather data and assign variables for final output.
else {

	// Current Observation data
	$wustorm_current_observation_obj = new MIS\WU\Current_Observation($wustorm_response['weather_data']);
	$wustorm_current_observation = $wustorm_current_observation_obj->get_current_observation();

	foreach ($wustorm_current_observation as $key => $value) {
		${"wustorm_current_observation_" . $key} = $value;
	}

	// Display Location data
	$wustorm_display_location_obj = new MIS\WU\Display_Location($wustorm_response['weather_data']);
	$wustorm_display_location = $wustorm_display_location_obj->get_display_location();

	foreach ($wustorm_display_location as $key => $value) {
		${"wustorm_display_location_" . $key} = $value;
	}

	// Observation Location data
	$wustorm_observation_location_obj = new MIS\WU\Observation_Location($wustorm_response['weather_data']);
	$wustorm_observation_location = $wustorm_observation_location_obj->get_observation_location();

	foreach ($wustorm_observation_location as $key => $value) {
		${"wustorm_observation_location_" . $key} = $value;
	}

	// Compose the heading and icon output
	$wustorm_title_primary .= " for " . $wustorm_display_location_city . ', ' .
			$wustorm_display_location_state . ' ' . $wustorm_display_location_zip;

	$wustorm_weather_icon_img = '<img src="' . $wustorm_current_observation_icon_url . '" alt="' .
			$wustorm_current_observation_weather . '" />';
}

// Output error text when present
if ( $wustorm_error_output )
{
	$wustorm_error_output = <<<errorOutput
<div class="wustorm-error" role="alert">
	$wustorm_error_output
</div>
errorOutput;
}

include( './output/display.php' );
?>
