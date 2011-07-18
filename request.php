<?php
/**
 * Request
 *
 * Builds a request from a specific context and uses the
 * Response object to handle responses.
 *
 * @package TENSE_API
 */
class tense_request {

	/**
	 * API Endpoint
	 *
	 * @access Public
	 */
	public $endpoint = null;
	
	/**
	 * Current Context. listing|agents
	 *
	 * @access Public
	 */
	public $context = null;
	
	/**
	 * POST fields for cURL session
	 *
	 * @access Public
	 */
	public $fields = array();

	/**
	 * Constructor
	 *
	 * @access Public
	 * @return
	 */
	public function __construct($endpoint = null, $action = null, $params = array(), $defaults = array()) {//$context, $action, $params = array(), $defaults = array(), $default_params = array()) {
		// We set where the API would call
		$this->endpoint = $endpoint . $action;
		// We check the global request var for similar keys
		foreach ($params as $key => $value) {
			$key = strtolower($key);
			if (in_array($key, $defaults) || !empty($defaults)) {
				if (is_array($value)) {
					// No arrays please
				} else {
					$this->fields[$key] =  $value;
				}				
			}
		}
	}

	/**
	 * We call the API and then create a response object
	 *
	 * @access Public
	 */
	public function call() {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->endpoint);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->fields);
		$this->contents = curl_exec($ch);		
		curl_close($ch);
		return new tense_response($this);
	}
	
}

?>