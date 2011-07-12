<?php
/**
 * API Context Interface
 *
 * @package PG_API
 */
interface pg_interface {

	/**
	 * Search the Object
	 *
	 * @access Public
	 */
	public function search();

	/**
	 * Create an Entity
	 *
	 * @access Public
	 */
	public function create();

	/**
	 * Read an Entity
	 *
	 * @access Public
	 */
	public function read();

	/**
	 * Update an Entity
	 *
	 * @access Public
	 */
	public function update();

	/**
	 * Delete an Entity
	 *
	 * @access Public
	 */
	public function delete();
	
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