<?php
/**
 * Core API Object
 *
 * @package PG_API
 */
require_once PG_WP_API . 'request.php';
require_once PG_WP_API . 'response.php';
/**
 * PropertyGuru API
 */
class pg_api {

	/**
	 * Response Status
	 *
	 * @access Private
	 */
	private $status = null;
	
	/**
	 * Current Request Action
	 *
	 * @access Private
	 */
	private $action = null;
	
	/**
	 * Current Context. listing|agents
	 *
	 * @access Private
	 */
	private $context = null;
	
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
	 * Raw Content of the Initial Resposne
	 *
	 * @access Private
	 */
	private $contents = null;
	
	/**
	 * Constructor
	 *
	 * @access Public
	 * @return void
	 */
	public function __construct() {}
	
	/**
	 * Context Setter
	 *
	 * @access Public
	 * @return void
	 */
	public function setContext($context) {
		$this->context = $context;
	}
	
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
	 * Content Setter
	 *
	 * @access Public
	 * @return void
	 */
	public function setContent($content = 'search') {
		$this->content = $content;
	}
	
	/**
	 * Context Getter
	 *
	 * @access Public
	 * @return mixed $context
	 */
	public function getContext() {
		return $this->context;
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
	
	/**
	 * Content Getter
	 *
	 * @access Public
	 * @return void
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * We request the API to do some shiz
	 *
	 * @access Public
	 * @return
	 */
	public function action($params = array(), $defaults = array(), $default_params = array()) {
		// We make an API call
		$context = ($context) ? $this->getContext(): $context;
		// We assign an ID to every request
		$params['json_id'] = ($params['json_id']) ? $params['json_id']: time();
		$params = array_merge($default_params, $params);
		// Default Parameters for the Request
		$params = array_merge($_REQUEST, $params);		
		extract($this->request($params));
		$this->request = new pg_api_request($endpoint, $params, $defaults);
		$this->response = $this->request->call();
		
		// We parse the response from the request
		$this->status = $this->response->status;
		
		// Check if there was any problem
		if ($this->status == '200') {
			// Return the Response Object
			return $this->response($this->response);
		} else {
			// Else, we throw a nice error
			if (PG_WP_DEBUG && $action != 'login') {
				echo '<p>API Call failed with status code: ' . $this->status . '</p>';
			}
			return false;
		}
	}

}

?>