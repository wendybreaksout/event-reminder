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

		if ( current_user_can('activate_plugins') ) {	
			$options = array();
			$options['version'] = EVENT_REMINDER_VERSION ;
			$options['message_text'] = 'Dear __FIRST__, This is a reminder that you are attending __TITLE__ on __DATETIME__';	
			add_option( EVENT_REMINDER_OPTIONS_NAME, $options );
		}

	}

}
