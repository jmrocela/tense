<?php
/**
 * API Context Interface
 *
 * @package TENSE_API
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

?>