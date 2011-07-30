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
		extract($this->request($this->endpoint, $action, $params));
		$this->request = new tense_request($endpoint, $action, $params, $defaults);
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

?>