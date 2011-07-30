<?php

/**
 * Core API Object
 *
 * @package TENSE_API
 *
 * Tense, A small PHP Library to interact with REST Services
 * Copyright (C) 2011 John Rocela
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */
class tense_api {

	/**
	 * Response Status
	 *
	 * @access Private
	 */
	private $status = null;
	
	/**
	 * The Endpoint of the Request
	 *
	 * @access Private
	 */
	private $endpoint = null;
	
	
	/**
	 * Current Request Action
	 *
	 * @access Private
	 */
	private $action = null;
	
	/**
	 * Response Object
	 *
	 * @access Private
	 */
	private $response = null;
	
	/**
	 * Request Object
	 *
	 * @access Private
	 */
	private $request = null;
	
	/**
	 * Status Setter
	 *
	 * @access Public
	 * @return void
	 */
	public function setStatus($status = 500) {
		$this->status = $status;
	}
	
	/**
	 * Action Setter
	 *
	 * @access Public
	 * @return void
	 */
	public function setAction($action = 'search') {
		$this->action = $action;
	}
	
	/**
	 * Status Getter
	 *
	 * @access Public
	 * @return mixed $status
	 */
	public function getStatus() {
		return $this->status;
	}
	
	/**
	 * Action Getter
	 *
	 * @access Public
	 * @return mixed $context
	 */
	public function getAction() {
		return $this->action;
	}
	
	public function __construct($settings) {
		$this->endpoint = $settings;
	}

	/**
	 * We request the API to do some shiz
	 *
	 * @access Public
	 * @return
	 */
	public function action($action = null, $params = array(), $defaults = array(), $default_params = array()) {
		// We assign an ID to every request
		$params['json_id'] = (@$params['json_id']) ? $params['json_id']: time();
		$params = array_merge($default_params, $params);
		$this->request = new tense_request($this->endpoint, $action, $params, $defaults);
		$this->response = $this->request->call();

		// We parse the response from the request
		$this->status = $this->response->status;
		
		// Check if there was any problem
		if ($this->status == '200') {
			// Return the Response Object
			return $this->response($this->response);
		} else {
			// Else, we throw a nice error
			if (TENSE_DEBUG) {
				echo '<p>API Call failed with status code: ' . $this->status . '</p>';
			}
			return false;
		}
	}

	/**
	 * Ping an Endpoint if it exists or not
	 *
	 * @access Public
	 * @return
	 */
	public function ping() {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->endpoint);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return $status;
	}

}

/**
 * API Context Interface
 *
 * @package TENSE_API
 *
 * Tense, A small PHP Library to interact with REST Services
 * Copyright (C) 2011 John Rocela
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */
interface tense_interface {
	
	/**
	 * Builds a Request
	 *
	 * @access Public
	 */
	public function request($params = array());
	
	/**
	 * Handles a Response
	 *
	 * @access Public
	 */
	public function response($response = null);
	
}

/**
 * Request
 *
 * Builds a request from a specific context and uses the
 * Response object to handle responses.
 *
 * @package TENSE_API
 *
 * Tense, A small PHP Library to interact with REST Services
 * Copyright (C) 2011 John Rocela
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */
class tense_request {
	
	/**
	 * Builds a Request
	 *
	 * @access Public
	 */
	const GET = 'get';
	
	/**
	 * Builds a Request
	 *
	 * @access Public
	 */
	const POST = 'post';
	
	/**
	 * Builds a Request
	 *
	 * @access Public
	 */
	const PUT = 'put';
	
	/**
	 * Builds a Request
	 *
	 * @access Public
	 */
	const DELETE = 'delete';

	/**
	 * API Endpoint
	 *
	 * @access Public
	 */
	public $endpoint = null;

	/**
	 * Request Method
	 *
	 * @access Public
	 */
	public $method = null;
	
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
	public function __construct($settings = array(), $action = null, $params = array(), $defaults = array()) {
		// We set where the API would call
		if (is_array($settings)) {
			$this->endpoint = (isset($settings['endpoint'])) ? $settings['endpoint']: null;
			$this->method = (isset($settings['method'])) ? $settings['method']: null;
		} else {
			$this->endpoint = $settings . $action;
			$this->method = 'GET';
		}
		// We check the global request var for similar keys
		if ($params) {
			foreach ($params as $key => $value) {
				$key = strtolower($key);
				if (in_array($key, $defaults) || empty($defaults)) {
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
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if ($this->fields) {
			$fields = $this->fields;
			$postfields = array();
			foreach ($fields as $field => $value) {
				if (is_array($value)) {
					foreach ($value as $key => $val) {
						if (!is_array($val)) {
							$postfields[] = $field . '[]=' . $val;
						}
					}
				} else {
					$postfields[] = $field . '=' . $value;
				}
			}
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, implode('&', $postfields));
		}
		$this->contents = curl_exec($ch);
		$this->info = curl_getinfo($ch);
		curl_close($ch);
		return new tense_response($this);
	}
	
}


/**
 * Response
 *
 * Handles Responses from the API
 *
 * @package TENSE_API
 *
 * Tense, A small PHP Library to interact with REST Services
 * Copyright (C) 2011 John Rocela
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */
class tense_response {

	/**
	 * Response Status
	 *
	 * @access Public
	 */
	public $status = 200;

	/**
	 * JSON ID for the Request
	 *
	 * @access Public
	 */
	public $json_id = null;
	
	/**
	 * Request Object
	 *
	 * @access Public
	 */
	public $contents = null;

	/**
	 * Constructor
	 *
	 * @access Public
	 * @return
	 */
	public function __construct(tense_request $request) {
		// We know it's JSON so we DECODE IT!!! we should check first
		if (json_decode($request->contents)) {
			$this->contents = json_decode($request->contents);
			$this->headers = $request->info;
			$this->status = $request->info['http_code'];
		} else {
			if ('TENSE_DEBUG') {
				throw new Exception('API Call failed because the Response returned is not in JSON format. Server returned with: ' . $request->contents);
			}
		}
	}
	
}

?>