<?php
/**
 * Response Tests
 *
 * @package TENSE_TEST
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
require_once 'config.php';

class ResponseTestCase extends UnitTestCase {

	/**
	 * Context
	 *
	 * @access public
	 */
	public $context = null;
	
	/**
	 * Test: Create a Context for the Testcase
	 *
	 * @access public
	 */
	public function testCreatContext() {
		$endpoint = TENSE_TEST_ENDPOINT;
		$this->context = new context($endpoint);
		$this->assertIsA($this->context, 'tense_api');
	}
	
	/**
	 * Test: See if we have a Proper Response
	 *
	 * @access public
	 */
	public function testHasResponse() {
		$action = '?controller=working&action=noparams';
		$return = $this->context->action($action);
		$this->assertEqual($return->status, 200);
	}
	
	/**
	 * Test: See if we have a Proper Response Header
	 *
	 * @access public
	 */
	public function testHasResponseHeader() {
		$action = '?controller=working&action=noparams';
		$return = $this->context->action($action);
		$keys = array("url","content_type","http_code","header_size","request_size","filetime","ssl_verify_result","redirect_count","total_time","namelookup_time","connect_time","pretransfer_time","size_upload","size_download","speed_download","speed_upload","download_content_length","upload_content_length","starttransfer_time","redirect_time","certinfo");
		foreach ($keys as $key) {
			$this->assertTrue(array_key_exists($key, $return->headers));
		}
	}
	
	/**
	 * Test: See what will happen if there is a Bad Request
	 *
	 * @access public
	 */
	public function testResponseIsBadRequest() {
		$this->expectException();
		$action = '?controller=broken&action=badrequest';
		$return = $this->context->action($action);
		$this->assertEqual($return->status, 400);
	}
	
}

?>