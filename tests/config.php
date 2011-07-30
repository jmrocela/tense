<?php
/**
 * Test Configuration
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
 
// Define the Simpletest Path
define('SIMPLETEST', "../../../libraries/simpletest/");

// Require the Simpletest Autorun script
require_once SIMPLETEST . 'autorun.php';

// Define Test Parameters
define('TENSE_TEST_ENDPOINT', 'http://' . $_SERVER['SERVER_NAME'] . '/tense/tests/endpoint.php');
define('TENSE_DEBUG', 1);

require_once '../tense/api.php';
require_once '../tense/interface.php';
require_once '../tense/request.php';
require_once '../tense/response.php';

// We get the Context Sample
require_once 'context.php';

?>