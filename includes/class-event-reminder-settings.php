<?php

class Event_Reminder_Settings {

  /*
	 * Sets the name of plugin option.
	 */
	private $options_name = EVENT_REMINDER_OPTIONS_NAME;
	
	/*
	 * Default values for plugin options are defined here. 
	 * These values are recorded in wp_option at activation time. 
	 * 
	 */
	private $default_use_widget_area = false;
	private $default_remove_data_on_uninstall = false;
	private $version = EVENT_REMINDER_VERSION;
	
	/**
	 * Constructor
	 * 
	 * @since 1.0.0
	 */
	public function __construct() {

	}
	
	
	/*
	 * Get the plugin option name. 
	 * 
	 * @return string plugin option name.
	 */
	public function get_options_name() {
		return $this->options_name;
	}
	
	
	/*
	 * This function is called at activation time and by the constructor. It records
	 * the plugin settings default values in the wp_options table. 
	 * If the plugin options already exist in the database, they 
	 * are not overwritten. 
	 * 
	 * @since 1.0.0
	 */
	public function add_option_defaults() {

		return;
		
		if ( current_user_can('activate_plugins') ) {	
			$options = array();
			$options['version'] = $this->version ;
			$options['message_text'] = 'Dear __FIRST__, This is a reminder that you are attending __TITLE__ on __DATETIME__';	
			add_option( $this->options_name, $options );
		}
		
	}




	/*
	 * This function was intended to be called to delete the 
	 * options from the database. 
	 * 
	 * @todo Can this delete_options() be removed. 
	 * @since 1.0.0
	 */
	
	public function delete_options() {
		if ( current_user_can('delete_plugins') ) {
			delete_option($this->options_name );			
		}
	}

  public function get_message_text() {
		$option = get_option( $this->options_name);
		return $option['message_text'];
	}

  public function settings_init(  ) {

		register_setting( 'event-reminder-settings-group', $this->options_name, array( $this, 'sanitize') );
		
		add_settings_section(
			'event-reminder-settings-general-section',
			__( 'Event Reminder General Settings', EVENT_REMINDER_TEXT_DOMAIN ),
			array($this, 'event_reminder_settings_general_info'),
			'event-reminder-settings-page'
		);

		
		add_settings_field('message_text', 
						__("Reminder message", 
						EVENT_REMINDER_TEXT_DOMAIN), 
						array($this, 'reminder_message_render'), 
						'event-reminder-settings-page',
						'event-reminder-settings-general-section');
    

      
	}

  public function add_options_page( ) {

		// Add the top-level admin menu
		$page_title = 'Event Reminder Plugin Setings';
		$menu_title = 'Event Reminder';
		$capability = 'manage_options';
		$menu_slug = 'event-reminder-settings';
		$function = 'settings_page';
		add_options_page($page_title, $menu_title, $capability, $menu_slug, array($this, $function)) ;


	}

  public function settings_page(  ) {
	
		?>
		<div class="wrap">
      <form action='options.php' method='post'>
        
        <div id="event-reminder-settings-container">
          <?php

          settings_fields( 'event-reminder-settings-group' );
          do_settings_sections( 'event-reminder-settings-page' );
          submit_button();
          ?>
      </form>
    </div>
			
		<?php

	}

	public function event_reminder_settings_general_info () {
		echo '<p>' . __("General settings for Event Reminder Plugin", EVENT_REMINDER_TEXT_DOMAIN) . '</p>';
	}

	public function reminder_message_render() {
		$options = get_option( $this->options_name );
	
		/*
		?>
		<textarea rows="10" cols="50" id="tnotw-event-reminder-message" name="tnotw_event_reminder_settings[message_text]"></textarea>
		<?php
		*/
		$editor_settings = array( 'tinymce' => true, 
															'default_editor' => 'tinymce',
															'textarea_name' => 'tnotw_event_reminder_settings[message_text]',
														  'wpautop' => false );

		wp_editor( $options['message_text'], "tnotw_event_reminder_settings_message_text", $editor_settings );

		
	}

	public function sanitize( $input ) {
		
		$new_input = array();
		
		if( isset( $input['message_text'] ) )
			$new_input['message_text'] = wp_kses( $input['message_text'], 'post' );


		return $new_input ;
	}

}