<?php
namespace MIS\WU;

class Wunderground_Data {

	/**
	 * Fetches weather data via Weather Underground API and processes the initial response.
	 */

	private $url = 'http://api.wunderground.com/api/';
	private $live_api_url;
	private $json_response;
	private $http_status_code;

	public $json_response_header;
	public $json_response_body;
	public $error_msg;

	/**
	 * Define this if you want to use a local copy
	 * of the API's json response during development
	 * instead of calling the API over and over.
	 * Use it in place of $this->live_api_url inside __construct().
	 * Example:
	 * private $test_api_url = 'http://localhost/~username/wustorm/dev/test.json'
	 */
	private $test_api_url;

	/**
	 * Assembles the API URL and fetches the data.
	 * @param string $api_key Your unique Weather Underground API key.
	 * @param string $api_zip The zip code to fetch data for.
	 */
	function __construct() {

		$this->live_api_url = $this->url . trim( WUSTORM_API_KEY ) .
				'/conditions/forecast/alerts/q/' . trim( WUSTORM_API_ZIP ) . '.json';

		if ( trim( WUSTORM_API_KEY ) && WUSTORM_API_KEY != 'api_key_here') {
			list( $this->json_response,$this->http_status_code ) = $this->get_url( $this->live_api_url );
		}
	}

	/**
	 * CURL function to call the API.
	 */
	private function get_url($url)
	{

		//  Initiate curl
		$ch = curl_init($url);

		// Set curl opts
		$options = array(
			CURLOPT_URL            => $url,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HEADER         => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_ENCODING       => "",
			CURLOPT_AUTOREFERER    => true,
			CURLOPT_CONNECTTIMEOUT => 60,
			CURLOPT_TIMEOUT        => 60,
			CURLOPT_MAXREDIRS      => 5,
		);
		curl_setopt_array( $ch, $options );

		// Execute curl
		$response = curl_exec($ch);
		$http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		// Close connection and free resources
		curl_close($ch);

		return array($response,$http_status_code);
	}

	/**
	 * Process the HTTP and API response and return an array
	 * with error messages and separated json header & body.
	 */
	function get_response() {

		if ( 200 !== $this->http_status_code ) {
			if ( !trim( WUSTORM_API_KEY ) || WUSTORM_API_KEY == 'api_key_here') {
				$this->error_msg = "No API Key was defined.";
			}
			else {
				$this->error_msg = "The weather could not be obtained. (Status code: " . $this->http_status_code .")";
			}
		}
		else {
			list( $this->json_response_header, $this->json_response_body ) = explode( "\r\n\r\n", $this->json_response, 2 );
		}

		$r['error_msg'] = $this->error_msg;
		$r['weather_data'] = json_decode( $this->json_response_body, true );

		if (isset( $r['weather_data']['response']['error']['description'] )) {
			$r['response_error'] = $r['weather_data']['response']['error']['description'];
		}

		return $r;
	}
}

class Current_Observation {

	/**
	 * Returns the "Current Observation" portion of the processed API response.
	 */

	private $weather_data;

	function __construct($weather_data) {
		$this->weather_data = $weather_data;
	}

	function get_current_observation() {
		foreach( $this->weather_data['current_observation'] as $key => $value ) {
			if (!is_array( $key ) ) {
				$r[$key] = $value;
			}
		}

		return $r;
	}
}

class Display_Location {

	/**
	 * Returns the "Display Location" portion of the processed API response.
	 */

	private $weather_data;

	function __construct($weather_data) {
		$this->weather_data = $weather_data;
	}

	function get_display_location() {
		foreach( $this->weather_data['current_observation']['display_location'] as $key => $value ) {
			$r[$key] = $value;
		}

		return $r;
	}
}

class Observation_Location {

	/**
	 * Returns the "Observation"Location portion of the processed API response.
	 */

	private $weather_data;

	function __construct($weather_data) {
		$this->weather_data = $weather_data;
	}

	function get_observation_location() {
		foreach( $this->weather_data['current_observation']['observation_location'] as $key => $value ) {
			$r[$key] = $value;
		}

		return $r;
	}
}
?>
