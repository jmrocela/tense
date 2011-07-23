<?php

class context extends tense_api implements tense_interface {

	public function request($params = array()) {
		// This builds the request Data
		return $params;
	}
	
	public function response($response = null) {
		// This parses the returned Data
		return $response;
	}

}

?>