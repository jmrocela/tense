<?php
/**
 * Request Tests
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

class RequestTestCase extends UnitTestCase {

	public $context = null;
	
	public function setUp() {
		// We get the Context Sample
		require_once 'context.php';
	}
	
	public function testCreatContext() {
		$endpoint = TENSE_TEST_ENDPOINT;
		$this->context = new context($endpoint);
		$this->assertIsA($this->context, 'tense_api');
	}
	
	public function testRawReturn() {
		$action = '?controller=working&action=noparams';
		$return = $this->context->action($action);
		$this->assertEqual($return->status, 200);
	}
	
	public function testParams() {
	
	}
	
	public function testWithDefaults() {
	
	}
	
	public function testWithDefaultParams() {
	
	}
	
	public function testOverrideParams() {
	
	}
	
	public function testThereIsAServer() {
	
	}
	
	public function testCall() {
	
	}
	
	public function testThereIsNoServer() {
	
	}
	
}

?>