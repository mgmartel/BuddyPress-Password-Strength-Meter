<?php
/*
Plugin Name: BuddyPress Password Strength Meter
Plugin URI: https://github.com/mgmartel/BuddyPress-Password-Strength-Meter/
Author: Mike Martel
Author URI: http://trenvo.nl
Description: Adds a strength meter / indicator to the 'change password' field in BuddyPress
Version: 0.5
Revision Date: July 06, 2012
*/

/**
 * "BuddyPress Password Strength Meter"
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 3 as published by
 * the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see http://www.gnu.org/licenses/.
 *
 * @package bp-password-strength-meter
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) )
	exit;

/**
 * Version number
 *
 * @since 0.5
 */
define ( 'BPPS_VERSION', '0.5' );

/**
 * PATHs and URLs
 *
 * @since 0.5
 */
define ( 'BPPS_DIR', plugin_dir_path(__FILE__) );
define ( 'BPPS_URL', plugin_dir_url(__FILE__) );
define ( 'BPPS_INC_URL', BPPS_URL . '_inc/' );

if ( ! class_exists( 'BuddyPressPasswordStrength' ) ) :
    class BuddyPressPasswordStrength {

        /**
         * Creates an instance of the BuddyPressPasswordStrength class.
         *
         * Thanks to the Jetpack and BP Labs plugins for the idea with this function.
         *
         * @return BuddyPressPasswordStrength object
         * @since 0.5
         * @static
         */
        public static function &init() {
            static $instance = false;

            if ( !$instance ) {
                $instance = new BuddyPressPasswordStrength;
            }

            return $instance;
        }

        /**
         * Constructor
         *
         * @since 0.5
         */
        public function __construct() {
            // Is this the settings page?
            if ( ! bp_current_component ( 'settings' ) || ! bp_current_action ( 'general' ) ) return;

            add_action('wp_enqueue_scripts',array ( &$this, 'load_scripts' ) );
            add_action ( 'bp_core_general_settings_before_submit', array ( &$this, 'strength_indicator' ) );

        }
            /**
             * PHP4
             *
             * @since 0.5
             */
            public function BuddyPressPasswordStrength() {
                $this->_construct();
            }

        /**
         * Load the necessary scripts
         *
         * @since 0.5
         */
        public function load_scripts() {
            if( ! is_admin() ) {

                wp_enqueue_script('jquery');
                wp_enqueue_script('password-strength-meter');
                wp_enqueue_script('user-profile');

                wp_enqueue_style ( 'bp-password-strength-meter', BPPS_INC_URL . '/css/bp-password-strength-meter.css' );

            }
        }

        /**
         * Prints strength indicator
         *
         * @since 0.5
         */
        public function strength_indicator() {
            global $bp;
            ?>

            <input type="hidden" id="user_login" value="<?php echo $bp->loggedin_user->id; ?>">
            <div id="pass-strength-result"><?php _e('Strength indicator'); ?></div>
            <p class="description indicator-hint"><?php _e('Hint: The password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers and symbols like ! " ? $ % ^ &amp; ).'); ?></p>

            <?php
        }
    }
    add_action( 'bp_init', array( 'BuddyPressPasswordStrength', 'init' ) );
endif;