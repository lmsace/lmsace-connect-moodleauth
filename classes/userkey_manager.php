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
 * User private key manager class. Modified version of auth_userkey plugin core_userkey_manager class.
 *
 * @package    auth_userkey
 * @copyright  2023 LMSACE Dev Team <https://www.lmsace.com>.
 * @copyright  2016 Dmitrii Metelkin (dmitriim@catalyst-au.net), Modified verion of auth_userkey.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace auth_lmsace_connect;

defined('MOODLE_INTERNAL') || die;

/**
 * Userkey management.
 */
class userkey_manager  {

    /**
     * This script script required by core create_user_key().
     */
    const LACONN_USERKEY_SCRIPT = 'auth/lmsace_connect';

    /**
     * Default life time of the user key in seconds.
     */
    const KEY_LIFETIME = 100;

    /**
     * Config object.
     *
     * @var \stdClass
     */
    protected $config;

    /**
     * Create a user key.
     *
     * @param int $userid User ID.
     *
     * @return string Generated key.
     */
    public function create_key($userid) {
        $this->delete_keys($userid);

        $validuntil = time() + self::KEY_LIFETIME;

        return create_user_key(
            self::LACONN_USERKEY_SCRIPT,
            $userid,
            $userid,
            null,
            $validuntil
        );
    }

    /**
     * Delete all keys for a specific user.
     *
     * @param int $userid User ID.
     */
    public function delete_keys($userid) {
        delete_user_key(self::LACONN_USERKEY_SCRIPT, $userid);
    }

    /**
     * Validates key and returns key data object if valid.
     *
     * @param string $keyvalue User key value.
     *
     * @return object Key object including userid property.
     *
     * @throws \moodle_exception If provided key is not valid.
     */
    public function validate_key($keyvalue) {
        global $DB;

        $options = array(
            'script' => self::LACONN_USERKEY_SCRIPT,
            'value' => $keyvalue
        );

        if (!$key = $DB->get_record('user_private_key', $options)) {
            print_error('invalidkey');
        }

        if (!empty($key->validuntil) and $key->validuntil < time()) {
            print_error('expiredkey');
        }

        if (!$user = $DB->get_record('user', array('id' => $key->userid))) {
            print_error('invaliduserid');
        }
        return $key;
    }

}
