<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://tnotw.com
 * @since             1.0.0
 * @package           Event_Reminder
 *
 * @wordpress-plugin
 * Plugin Name:       Event Reminder
 * Plugin URI:        https://tnotw.com
 * Description:       Send email reminder to event attendees. 
 * Version:           1.0.4
 * Author:            Wendy Emerson
 * Author URI:        https://tnotw.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       event-reminder
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'EVENT_REMINDER_VERSION', '1.0.4' );
define('EVENT_REMINDER_TEXT_DOMAIN', 'tnotw_event_reminder');
define('EVENT_REMINDER_OPTIONS_NAME', 'tnotw_event_reminder_settings');
define('EVENT_REMINDER_DATE_TIME_FIELD', 'event_datetime');

define('EVENT_REMINDER_DEFAULT_LEAD_TIME', 60 ); 
define('EVENT_REMINDER_DEFAULT_SEND_LIMIT', 1 ); 
define('EVENT_REMINDER_SEND_COUNT_FIELD_NAME', 'event_reminder_notification_count');


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-event-reminder-activator.php
 */
function activate_event_reminder() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-event-reminder-activator.php';
	Event_Reminder_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-event-reminder-deactivator.php
 */
function deactivate_event_reminder() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-event-reminder-deactivator.php';
	Event_Reminder_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_event_reminder' );
register_deactivation_hook( __FILE__, 'deactivate_event_reminder' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-event-reminder.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_event_reminder() {

	$plugin = new Event_Reminder();
	$plugin->run();

}
run_event_reminder();
