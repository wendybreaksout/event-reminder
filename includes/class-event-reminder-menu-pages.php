<?php

class Event_Reminder_Menu_Pages {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */


	public function __construct() {

	}

	public function admin_menu_pages(){
		// Add the top-level admin menu
		$page_title = 'Event Reminder Plugin Setings';
		$menu_title = 'Event Reminder';
		$capability = 'manage_options';
		$menu_slug = 'event-reminder-settings';
		$function = 'event_reminder_settings';

		$settings = new Event_Reminder_Settings();

		add_menu_page($page_title, $menu_title, $capability, $menu_slug, array($settings, 'settings_page')) ;

		// Add submenu page with same slug as parent to ensure no duplicates
		$sub_menu_title = 'Settings';
		add_submenu_page($menu_slug, $page_title, $sub_menu_title, $capability, $menu_slug, array( $settings, 'settings_page'));


	}

	public function event_reminder_settings() {
		$settings = new Event_Reminder_Settings();
		$settings->settings_init();
	}
  
}

	