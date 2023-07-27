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
 * Auth lmsace connect - External methods defined, Fetching user login status, userlogin key for SSO.
 *
 * @package   auth_lmsace_connect
 * @copyright 2023 LMSACE Dev Team <https://www.lmsace.com>.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace auth_lmsace_connect;

require_once($CFG->libdir . "/externallib.php");

use external_api;
use external_function_parameters;
use external_single_structure;

/**
 * External function definition to run the SSO between moodle and woocommerece.
 */
class external extends external_api {

    /**
     * Paramenters to generate the user login key.
     *
     * @return void
     */
    public static function generate_userloginkey_parameters() {
        return new external_function_parameters(
            array(
                'user' => new external_single_structure(
                    array(
                        'email' => new \external_value(PARAM_EMAIL, 'User email id to login', ),
                        'autocreate' => new \external_value(PARAM_INT, 'Create the user if not exists', VALUE_OPTIONAL),
                        'firstname' => new \external_value(PARAM_NOTAGS, 'The first name(s) of the user', VALUE_OPTIONAL),
                        'lastname' => new \external_value(PARAM_NOTAGS, 'The family name of the user', VALUE_OPTIONAL),
                        'username' => new \external_value(PARAM_USERNAME, 'The family name of the user', VALUE_OPTIONAL)
                    )
                )
            )
        );
    }

    /**
     * Generate the login key for shared user, prevent the key for admin users.
     *
     * @param array $user
     * @return void
     */
    public static function generate_userloginkey($user) {
        global $CFG;

        $auth = get_auth_plugin('lmsace_connect');
        $user['autocreate'] = (isset($user['autocreate'])) ? $user['autocreate'] : 0;
        list($key, $userid) = $auth->generate_userloginkey($user);
        $user = \core_user::get_user($userid);

        if (is_siteadmin($user) || $user->deleted) {
            return ['loginkey' => $key, 'error' => 'usernotaccessible'];
        }

        return ($key) ? ['loginkey' => $key, 'userid' => $userid, 'username' => $user->username] : ['loginkey' => $key, 'error' => 'usernotavailable'];
    }

    /**
     * Returns the gnerated login key for user.
     *
     * @return external_single_structure
     */
    public static function generate_userloginkey_returns() {

        return new external_single_structure(
            array(
                'loginkey' => new \external_value( PARAM_RAW, 'Login key for a user to log in' ),
                'userid' => new \external_value( PARAM_INT, 'Created user id', VALUE_OPTIONAL ),
                'username' => new \external_value(\core_user::get_property_type('username'), 'User name', VALUE_OPTIONAL),
            )
        );
    }

    /**
     * Parameters to check any user is logged in.
     *
     * @return bool
     */
    public static function is_userloggedin_parameters() {

        return new external_function_parameters(
            array(
                'userid' => new \external_value(PARAM_INT, 'id of the conversation'),
            )
        );
    }

    /**
     * Verfiy the user is logged in, also confirm the logged in user is specified user.
     *
     * @param string $userid
     * @return boolean
     */
    public static function is_userloggedin($userid='') {
        global $USER;

        if (isloggedin() && $USER->id == $userid) {
            return true;
        }
        return false;
    }

    /**
     * Return the status of the user logged in.
     *
     * @return bool
     */
    public static function is_userloggedin_returns() {
        return new \external_value(PARAM_BOOL, 'Check is user logged in');
    }
}
