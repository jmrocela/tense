<?php
/**
 *  Just a Mock Context
 */
class context extends tense_api implements tense_interface {

	public function request($endpoint = null, $action = null, $params = array()) {
		// This builds the request Data
		return array('endpoint' => $endpoint, 'action' => $action, 'params' => $params);
	}
	
	public function response($response = null) {
		// This parses the returned Data
		return $response;
	}

}

?>