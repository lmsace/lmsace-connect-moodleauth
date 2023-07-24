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
 * Auth LMSACE Connect - Handle the userkey based authetication and user creation.
 *
 * @package    auth_lmsace_connect
 * @copyright  2023 LMSACE Dev Team <https://www.lmsace.com>.
 * @copyright  2016 Dmitrii Metelkin (dmitriim@catalyst-au.net)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . "/externallib.php");
require_once($CFG->libdir.'/authlib.php');
require_once($CFG->dirroot . '/user/lib.php');

/**
 * LMSACE Connect - creates SSO between wordpress and moodle.
 */
class auth_plugin_lmsace_connect extends auth_plugin_base {

    public $keymanager;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->authtype = 'lmsace_connect';
        $this->keymanager = new \auth_lmsace_connect\userkey_manager();
    }

    /**
     * Returns true if this authentication plugin can change the users'
     * password.
     *
     * @return bool
     */
    function can_change_password() {
        //override if needed
        return false;
    }

    /**
     * Prevent the direct login using this mehtod.
     *
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function user_login($username, $password) {
        return false;
    }

    /**
     * Create the user in LMS with givent data.
     *
     * @param [type] $user
     * @param boolean $notify
     * @return void
     */
    public function user_signup($user, $notify=true) {
        global $CFG, $DB;

        require_once($CFG->dirroot.'/user/lib.php');

        if (!empty($user) && $user['autocreate']) {
            $requiredfields = ['username', 'firstname', 'lastname', 'email'];
            foreach ($requiredfields as $field) {
                if (empty($user[$field])) {
                    $missingfields[] = $field;
                }
            }
            if (isset($missingfields)) {
                throw new invalid_parameter_exception('Unable to create user, missing value(s): ' . implode(',', $missingfields));
            }

            if ($DB->record_exists('user', array('username' => $user['username'], 'mnethostid' => $CFG->mnet_localhost_id))) {
                throw new invalid_parameter_exception('Username already exists: '.$user['username']);
            }
            if (!validate_email($user['email'])) {
                throw new invalid_parameter_exception('Email address is invalid: '.$user['email']);
            } else if (empty($CFG->allowaccountssameemail) &&
                $DB->record_exists('user', array('email' => $user['email'], 'mnethostid' => $CFG->mnet_localhost_id))) {
                throw new invalid_parameter_exception('Email address already exists: '.$user['email']);
            }

            $user['auth'] = 'lmsace_connect';
            $user['confirmed'] = 1;
            $user['mnethostid'] = $CFG->mnet_localhost_id;

            $userid = user_create_user($user);
            return $DB->get_record('user', ['id' => $userid]);
        }
        return false;
    }

    /**
     * Generate key to key for the given user.
     * if user email not exists then create new user if the autocreate enabled.
     *
     * @param array $user
     * @return void
     */
    public function generate_userloginkey( $user=array() ) {
        global $DB;

        if (!isset($user['email']) || empty($user['email'])) {
            return '';
        }

        if (!$DB->record_exists('user', ['email' => $user['email']]) && $user['autocreate']) {
            $userid = $this->user_signup($user);
        }
        $userid = $DB->get_field('user', 'id', ['email' => $user['email']]);
        if (!empty($userid)) {
            $keymanager = new \auth_lmsace_connect\userkey_manager();
            $key = $keymanager->create_key($userid);
        }

        return [$key, $userid];
    }

    /**
     * Compelte the user login based on the wwebserive token and login token.
     *
     * @return void
     */
    public function user_login_key() {
        global $DB;
        $wstoken = required_param('wstoken', PARAM_TEXT);
        $userkey = required_param('key', PARAM_TEXT);

        // Obtain token record.
        if (!$token = $DB->get_record('external_tokens', array('token' => $wstoken))) {
            //client may want to display login form => moodle_exception
            throw new moodle_exception('invalidtoken', 'webservice');
        }

        try {
            $key = $this->keymanager->validate_key($userkey);
        } catch (moodle_exception $exception) {
            // If user is logged in and key is not valid, we'd like to logout a user.
            if (isloggedin()) {
                require_logout();
            }
            print_error($exception->errorcode);
        }

        // $token = optional_param('token');
        $user = get_complete_user_data('id', $key->userid);

        complete_user_login($user);

        // Call user_authenticated_hook.
        $authsenabled = get_enabled_auth_plugins();
        foreach ($authsenabled as $auth) {
            $hauth = get_auth_plugin($auth);
            $hauth->user_authenticated_hook($user, $user->username, "");
        }
    }
     /**
     * Hook for overriding behaviour of logout page.
     * This method is called from login/logout.php page for all enabled auth plugins.
     *
     * @global object
     * @global string
     */
    public function logoutpage_hook() {
        global $CFG, $redirect, $USER;
        $logouturl = get_config('auth_lmsace_connect', 'logouturl');
        if ( !empty($logouturl) && $USER->auth == 'lmsace_connect') {
            $redirect = $logouturl;
        }
    }
}
