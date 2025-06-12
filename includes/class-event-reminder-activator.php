<?php

/**
 * Fired during plugin activation
 *
 * @link       https://tnotw.com
 * @since      1.0.0
 *
 * @package    Event_Reminder
 * @subpackage Event_Reminder/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Event_Reminder
 * @subpackage Event_Reminder/includes
 * @author     Wendy Emerson <wendy@tnotw.com>
 */
class Event_Reminder_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		$settings = new Event_Reminder_Settings();
		$settings->add_option_defaults();
	}

}
