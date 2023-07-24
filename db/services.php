<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Auth lmsace connect - Services used to create authetication between WP and Moodle.
 *
 * @package   auth_lmsace_connect
 * @copyright 2023 LMSACE Dev Team <https://www.lmsace.com>.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$functions = array(
    'auth_lmsace_connect_generate_userloginkey' => array(
        'classname'   => 'auth_lmsace_connect\external',
        'methodname'  => 'generate_userloginkey',
        'description' => 'Generate auth token data to login the woocomerce customer in the Moodle LMS',
        'type'        => 'write',
    ),

    'auth_lmsace_connect_is_userloggedin' => array(
        'classname'   => 'auth_lmsace_connect\external',
        'methodname'  => 'is_userloggedin',
        'description' => 'Check the user is loggedin the woocomerce customer in the Moodle LMS',
        'type'        => 'write',
    )
);
