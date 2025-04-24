<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://tnotw.com
 * @since      1.0.0
 *
 * @package    Event_Reminder
 * @subpackage Event_Reminder/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Event_Reminder
 * @subpackage Event_Reminder/includes
 * @author     Wendy Emerson <wendy@tnotw.com>
 */
class Event_Reminder {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Event_Reminder_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'EVENT_REMINDER_VERSION' ) ) {
			$this->version = EVENT_REMINDER_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'event-reminder';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Event_Reminder_Loader. Orchestrates the hooks of the plugin.
	 * - Event_Reminder_i18n. Defines internationalization functionality.
	 * - Event_Reminder_Admin. Defines all hooks for the admin area.
	 * - Event_Reminder_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-event-reminder-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-event-reminder-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-event-reminder-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-event-reminder-public.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-event-reminder-settings.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-event-reminder-menu-pages.php';



		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-event-reminder-manager.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-event-reminder-recipients-manager.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-event-reminder-event-retriever.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-event-reminder-wc-manager.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-event-reminder-wc-recipients-manager.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-event-reminder-wc-event-retriever.php';

		$this->loader = new Event_Reminder_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Event_Reminder_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Event_Reminder_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Event_Reminder_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$settings = new Event_Reminder_Settings();
		$menu_pages = new Event_Reminder_Menu_Pages();

		$this->loader->add_action( 'admin_menu', $settings, 'add_options_page' );
		$this->loader->add_action( 'admin_init', $settings, 'settings_init' );
		$this->loader->add_action( 'admin_menu', $menu_pages, 'admin_menu_pages' );	


		$reminder_manager = new Event_Reminder_WC_Manager();
		$this->loader->add_action('tnotw_event_reminders_cron', $reminder_manager, 'cron_exec');
		if ( ! wp_next_scheduled('tnotw_event_reminders_cron' ) ) {
			$timestamp = strtotime("12pm");
      wp_schedule_event($timestamp, 'daily', 'tnotw_event_reminders_cron'  );
    }

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Event_Reminder_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Event_Reminder_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
