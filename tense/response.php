<?php
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
	public $status = 500;

	/**
	 * JSON ID for the Request
	 *
	 * @access Public
	 */
	public $json_id = null;
	
	/**
	 * Request Object
	 *
	 * @access Private
	 */
	private $request = null;

	/**
	 * Constructor
	 *
	 * @access Public
	 * @return
	 */
	public function __construct(tense_request $request) {
		// We know it's JSON so we DECODE IT!!! we should check first
		if (is_json($request->contents)) {
			$this->request = json_decode($request->contents);
			
			// Add the JSON ID, heck, I don't know what this does.
			$this->json_id = $this->request->json_id;
			
			if ($this->request->stat == 'ok') {
				// We convert the supplied status code to something better
				switch ($this->request->stat) {
					case "ok":
						$this->status = 200;
					break;
					case "error":
					default:
						$this->status = 500;
					break;
				}
			}

		} else {
			if (TENSE_DEBUG) {
				echo '<p>API Call failed because the Response returned is not in JSON format.</p>';
				die();
			}
		}
	}
	
	public function getRequest() {
		return $this->request;
	}
	
}

?>