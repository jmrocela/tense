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

	public $contextArraySettings = null;
	
	public function setUp() {
		// We get the Context Sample
		require_once 'context.php';
	}
	
	public function testCreatContext() {
		$endpoint = TENSE_TEST_ENDPOINT;
		$this->context = new context($endpoint);
		$this->assertIsA($this->context, 'tense_api');
	}
	
	public function testCreatContextWithArraySettings() {
		$endpoint = TENSE_TEST_ENDPOINT;
		$settings = array(
							'endpoint' => $endpoint,
							'method' => TENSE_REQUEST::POST
						);
		$this->contextArraySettings = new context($settings);
		$this->assertIsA($this->context, 'tense_api');
	}
	
	public function testNoParams() {
		$action = '?controller=working&action=noparams';
		$return = $this->context->action($action);
		$this->assertEqual($return->status, 200);
	}
	
	public function testParams() {
		$action = '?controller=working&action=withparams';
		$params = array(
							'foo' => 1,
							'bar' => 2,
							'string_foo' => 'This is a String',
							'float' => 0.24205
						);
		$return = $this->context->action($action, $params);
		$returned = array(
							'foo' => $return->contents->foo,
							'bar' =>$return->contents->bar,
							'string_foo' => $return->contents->string_foo,
							'float' => $return->contents->float
						);
		$this->assertEqual($returned, $params);
		$this->assertEqual($return->status, 200);
	}
	
	public function testParamsWithArray() {
		
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