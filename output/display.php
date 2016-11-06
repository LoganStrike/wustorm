<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo( $wustorm_title_primary ) ?></title>
		<link rel="stylesheet" href="./assets/css/styles.css" />
	</head>
	<body>
		<div class="wustorm-container">
			<?php echo( $wustorm_error_output );?>
			<h2 class="wustorm-location">
				<?php echo( $wustorm_display_location_full . ' ' . $wustorm_display_location_zip ); ?>
			</h2>
			<div class="wustorm-current-observations">
				<div class="wustorm-group">
					<div class="wustorm-summary">
						<div class="wustorm-current-weather-icon">
							<?php echo( $wustorm_weather_icon_img ); ?>
						</div>
						<div class="wustorm-current-weather">
							<?php echo( $wustorm_current_observation_weather ); ?>
						</div>
						<div class="wustorm-current-temperature">
							<?php echo( $wustorm_current_observation_temperature_string ); ?>
						</div>
					</div>
					<div class="wustorm-details">
						<ul class="wustorm-list">
							<li>
								Wind: <?php echo( $wustorm_current_observation_wind_mph ) . ' mph (From the ' . $wustorm_current_observation_wind_dir . ')'; ?>
							</li>
							<li>
								Relative Humidity: <?php echo( $wustorm_current_observation_relative_humidity ); ?>
							</li>
							<li>
								Today's Precipitation: <?php echo( $wustorm_current_observation_precip_today_in ); ?> in
							</li>
						</ul>
					</div>
				</div>
				<div class="wustorm-footer">
					<div class="wustorm-observation-location">
						Observed from <?php echo( $wustorm_observation_location_full . ' ' . $wustorm_display_location_zip . ' (' . $wustorm_observation_location_elevation . ')' ); ?>
					</div>
					<div class="wustorm-attribution">
							<?php echo( $wustorm_current_observation_observation_time ); ?>
							<br />
							Powered by Weather Underground
							<a href="https://www.wunderground.com/"><img src="./assets/img/wundergroundLogo_4c_rev_horz_90px.png" alt="Weather Underground" class="wustorm-attribution-img" /></a>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
