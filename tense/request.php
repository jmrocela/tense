<?php
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

?>