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

	/**
	 * Context
	 *
	 * @access public
	 */
	public $context = null;

	/**
	 * Context with Array Settings
	 *
	 * @access public
	 */
	public $contextArraySettings = null;
	
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
	 * Test: Create a Context for the Testcase with Array Settings
	 *
	 * @access public
	 */
	public function testCreatContextWithArraySettings() {
		$endpoint = TENSE_TEST_ENDPOINT;
		$settings = array(
							'endpoint' => $endpoint,
							'method' => TENSE_REQUEST::POST
						);
		$this->contextArraySettings = new context($settings);
		$this->assertIsA($this->contextArraySettings, 'tense_api');
	}
	
	/**
	 * Test: Call a Service without Parameters
	 *
	 * @access public
	 */
	public function testWithNoParams() {
		$action = '?controller=working&action=noparams';
		$return = $this->context->action($action);
		$this->assertEqual($return->status, 200);
	}
	
	/**
	 * Test: Call a Service with Parameters
	 *
	 * @access public
	 */
	public function testWithParams() {
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
		$this->assertIsA($this->context, 'tense_api');
		$this->assertEqual($return->status, 200);
	}
	
	/**
	 * Test: Call a Service with Parameters including an Array
	 *
	 * @access public
	 */
	public function testParamsWithArray() {
		$action = '?controller=working&action=withparams';
		$params = array(
							'foo' => 1,
							'bar' => 2,
							'string_foo' => 'This is a String',
							'float' => 0.24205,
							'array_param' => array(
															3,
															4,
														)
						);
		$return = $this->context->action($action, $params);
		$returned = array(
							'foo' => $return->contents->foo,
							'bar' =>$return->contents->bar,
							'string_foo' => $return->contents->string_foo,
							'float' => $return->contents->float,
							'array_param' => $return->contents->array_param
						);
		$this->assertEqual($returned, $params);
		$this->assertIsA($this->context, 'tense_api');
		$this->assertEqual($return->status, 200);
	}
	
	/**
	 * Test: Call a Service that is filtered with Default keys
	 *
	 * @access public
	 */
	public function testWithDefaults() {
		$action = '?controller=working&action=withparams';
		$defaults = array('foo', 'bar', 'string_foo', 'float');
		$params = array(
							'foo' => 1,
							'bar' => 2,
							'string_foo' => 'This is a String',
							'float' => 0.24205,
							'array_param' => array(
															3,
															4,
														)
						);
		$return = $this->context->action($action, $params, $defaults);
		$returned_wrong = array(
							'foo' => $return->contents->foo,
							'bar' =>$return->contents->bar,
							'string_foo' => $return->contents->string_foo,
							'float' => $return->contents->float,
							'array_param' => @$return->contents->array_param
						);
		$returned_correct = array(
							'foo' => $return->contents->foo,
							'bar' =>$return->contents->bar,
							'string_foo' => $return->contents->string_foo,
							'float' => $return->contents->float,
						);
		$this->assertNotEqual($returned_wrong, $params);
		unset($params['array_param']);
		$this->assertEqual($returned_correct, $params);
		$this->assertIsA($this->context, 'tense_api');
		$this->assertEqual($return->status, 200);
	}
	
	/**
	 * Test: Call a Service that is filtered with Default Parameters
	 *
	 * @access public
	 */
	public function testWithDefaultParams() {
		$action = '?controller=working&action=withparams';
		$default_params = array(
							'foo' => 1,
							'bar' => 2,
							'string_foo' => 'This is a String',
							'float' => 0.24205
						);
		$params = array(
							'foo' => 5,
							'bar' => 8,
						);
		$return = $this->context->action($action, $params, array(), $default_params);
		$returned = array(
							'foo' => $return->contents->foo,
							'bar' =>$return->contents->bar,
							'string_foo' => $return->contents->string_foo,
							'float' => $return->contents->float,
						);
		$this->assertEqual($returned, array_merge($default_params, $params));
		$this->assertIsA($this->context, 'tense_api');
		$this->assertEqual($return->status, 200);
	}
	
	/**
	 * Test: Call a Service that is filtered with Default Parameters and Keys
	 *
	 * @access public
	 */
	public function testWithDefaultParamsAndDefaults() {
		$action = '?controller=working&action=withparams';
		$defaults = array('foo', 'bar', 'string_foo', 'float');
		$default_params = array(
							'foo' => 1,
							'bar' => 2,
							'string_foo' => 'This is a String',
							'float' => 0.24205,
							'array_param' => array(
															3,
															4,
														)
						);
		$params = array(
							'bar' => 8,
							'string_foo' => 'Another String',
						);
		$return = $this->context->action($action, $params, $defaults, $default_params);
		$returned_correct = array(
			'foo' => $return->contents->foo,
			'bar' => $return->contents->bar,
			'string_foo' => $return->contents->string_foo,
			'float' => $return->contents->float
		);
		$this->assertNotEqual($returned_correct, array_merge($default_params, $params));
		unset($default_params['array_param']);
		$this->assertEqual($returned_correct, array_merge($default_params, $params));
		$this->assertIsA($this->context, 'tense_api');
		$this->assertEqual($return->status, 200);
	}
	
	/**
	 * Test: Does the Server exist?
	 *
	 * @access public
	 */
	public function testThereIsAServer() {
		$endpoint = TENSE_TEST_ENDPOINT;
		$context = new context($endpoint);
		$this->assertEqual($context ->ping(), 200);
	}
	
	/**
	 * Test: The Server doesn't Exist
	 *
	 * @access public
	 */
	public function testThereIsNoServer() {
		$endpoint = 'http://dummy.doesnt.exist.mock.url/';
		$context = new context($endpoint);
		$this->assertNotEqual($context ->ping(), 200);
	}
	
	/**
	 * Let's See if the library accepts custom headers when passed
	 *
	 * @access public
	 */
	public function testCustomHeaders() {
		$action = '?controller=working&action=customheaders';
		$endpoint = TENSE_TEST_ENDPOINT;
		$settings = array(
							'endpoint' => $endpoint,
							'method' => TENSE_REQUEST::POST,
							'headers' => array(
												'X-TENSE-HEADER: tense_test',
												'X-FOO: bar',
											)
						);
		$this->contextArraySettings = new context($settings);
		$return = $this->contextArraySettings->action($action);
		$returned= array(
				'HTTP_X_TENSE_HEADER' => $return->contents->HTTP_X_TENSE_HEADER, 
				'HTTP_X_FOO' => $return->contents->HTTP_X_FOO
		);
		$this->assertEqual($returned, array('HTTP_X_TENSE_HEADER' => 'tense_test', 'HTTP_X_FOO' => 'bar'));
		$this->assertIsA($this->contextArraySettings, 'tense_api');
	}
	
	/**
	 * Let's See if it can pass custom referers as well
	 *
	 * @access public
	 */
	public function testWithReferer() {
		$action = '?controller=working&action=withreferer';
		$endpoint = TENSE_TEST_ENDPOINT;
		$settings = array(
							'endpoint' => $endpoint,
							'method' => TENSE_REQUEST::POST,
							'referer' => 'http://www.spoof.url.com/'
						);
		$this->contextArraySettings = new context($settings);
		$return = $this->contextArraySettings->action($action);
		$this->assertEqual($return->contents->REFERER, 'http://www.spoof.url.com/');
		$this->assertIsA($this->contextArraySettings, 'tense_api');
	}
	
	/**
	 * Let's see if it can pass useragent data too
	 *
	 * @access public
	 */
	public function testWithUserAgent() {
		$action = '?controller=working&action=useragent';
		$endpoint = TENSE_TEST_ENDPOINT;
		$settings = array(
							'endpoint' => $endpoint,
							'method' => TENSE_REQUEST::POST,
							'useragent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:5.0) Gecko/20100101 Firefox/5.0/'
						);
		$this->contextArraySettings = new context($settings);
		$return = $this->contextArraySettings->action($action);
		$this->assertEqual($return->contents->REFERER, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:5.0) Gecko/20100101 Firefox/5.0/');
		$this->assertIsA($this->contextArraySettings, 'tense_api');
	}
	
	/**
	 * Test with Basic Authentication
	 *
	 * @access public
	 */
	public function testWithAuthentication() {
		$action = '?controller=working&action=requireauthentication';
		$endpoint = TENSE_TEST_ENDPOINT;
		$settings = array(
							'endpoint' => $endpoint,
							'method' => TENSE_REQUEST::POST,
							'username' => 'user',
							'password' => 'password'
						);
		$this->contextArraySettings = new context($settings);
		$return = $this->contextArraySettings->action($action);
		$this->assertEqual($return->contents, 1);
		$this->assertIsA($this->contextArraySettings, 'tense_api');
	}
	
	/**
	 * Test with Failing Authentication
	 *
	 * @access public
	 */
	public function testWithWrongAuthentication() {
		$action = '?controller=working&action=requireauthentication';
		$endpoint = TENSE_TEST_ENDPOINT;
		$settings = array(
							'endpoint' => $endpoint,
							'method' => TENSE_REQUEST::POST,
							'username' => 'user',
							'password' => 'wrongpassword'
						);
		$this->contextArraySettings = new context($settings);
		$return = $this->contextArraySettings->action($action);
		$this->assertIsA($this->contextArraySettings, 'tense_api');
		$this->assertEqual($return, 401);
	}
	
	/**
	 * Test  using the PUT method
	 *
	 * @access public
	 */
	public function testPut() {
		$action = '?controller=working&action=put';
		$endpoint = TENSE_TEST_ENDPOINT;
		chdir(dirname(__FILE__));
		$settings = array(
							'endpoint' => $endpoint,
							'method' => TENSE_REQUEST::PUT,
							'file' => 'upload.txt',
							'binarytransfer' => 1
						);
		$this->contextArraySettings = new context($settings);
		$return = $this->contextArraySettings->action($action);
		$this->assertIsA($this->contextArraySettings, 'tense_api');
		$this->assertEqual($return->status, 200);
	}	
	
	
}

?>