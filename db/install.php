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
 * Auth lmsace connect - Installtion process, add auth external functions into lmsace connect service functions.
 *
 * @package   auth_lmsace_connect
 * @copyright 2023 LMSACE Dev Team <https://www.lmsace.com>.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 /**
  * Add the SSO based external functions to the local_lmsace_connect external functions.
  *
  * @return bool
  */
function xmldb_auth_lmsace_connect_install() {

    global $DB;

    if ($serviceid = $DB->get_field('external_services', 'id', ['shortname' => 'local_lmsace_connect'])) {
        $data = array(
            'externalserviceid' => $serviceid,
            'functionname' => 'auth_lmsace_connect_generate_userloginkey'
        );
        if (!$DB->record_exists('external_services_functions', $data)) {
            $DB->insert_record('external_services_functions', $data);
        }

        $data = array(
            'externalserviceid' => $serviceid,
            'functionname' => 'auth_lmsace_connect_is_userloggedin'
        );
        if (!$DB->record_exists('external_services_functions', $data)) {
            $DB->insert_record('external_services_functions', $data);
        }
    }

    return true;
}
