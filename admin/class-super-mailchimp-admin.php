<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://anderskristo.me
 * @since      1.0.0
 *
 * @package    Super_Mailchimp
 * @subpackage Super_Mailchimp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Super_Mailchimp
 * @subpackage Super_Mailchimp/admin
 * @author     Anders Kristoffersson <anderskristo@gmail.com>
 */
class Super_Mailchimp_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Super_Mailchimp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Super_Mailchimp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/super-mailchimp-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Super_Mailchimp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Super_Mailchimp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/super-mailchimp-admin.js', array( 'jquery' ), $this->version, false );

	}

	
	/**
	 * Menu in admin
	 */
	
	public function add_admin_menu() {
		add_menu_page('Mailchimp Signup', 'Mailchimp Signup', 'manage_options', $this->plugin_name, array($this, 'display_main_setting_page'), 'dashicons-email', 100);
	}

	/**
	 * Render pages for this plugin
	 */
	
	public function display_main_setting_page() {
		include_once('partials/super-mailchimp-admin-display.php');
	}

	/**
	 * Enable redirection
	 */

	public function custom_redirect($admin_notice, $response) {
		wp_redirect(esc_url_raw(add_query_arg(array(
			'msg' => $admin_notice,
			'response' => $response,
		), admin_url('admin.php?page='. $this->plugin_name))));
	}

	/**
	 * Proccess Ajax Request.
	 */

	public function mailchimp_proccess_submit() {
		if (isset($_POST['mailchimp_meta_nonce']) && wp_verify_nonce($_POST['mailchimp_meta_nonce'], 'mailchimp_meta_form_nonce')) {
			$saved_data = [];

			$selected_list = 'selected_list';

            $saved_meta['api_key'] = sanitize_text_field($_POST['mailchimp_api_key']);
            $saved_meta['selected_lang'] = sanitize_text_field($_POST['selected_lang']);
            $saved_meta['selected_list'] = sanitize_text_field($_POST[$selected_list]);
            $saved_meta['terms_link'] = sanitize_text_field($_POST['terms_link']);

			update_option('mailchimp_meta', serialize($saved_meta));

			$callMailChimp = $this->mailchimp_integration_list();

			if (!empty($callMailChimp) && $callMailChimp !== false) {
				$saved_meta['lists'] = $callMailChimp;
				update_option('mailchimp_meta', serialize($saved_meta));
				$this->custom_redirect('succces', 200);
			}
			
			return false;
		}

		return false;
	}

	/**
	 * Get Saved data
	 */

	public function mailchimp_get_meta_data() {
		$saved_meta = get_option('mailchimp_meta', '');

		if (!empty($saved_meta)) {
			return unserialize($saved_meta);
		}

		return false;
	}

	/**
	 * MailChimp
	 */

	public function mailchimp_integration_list() {
		$saved_data = $this->mailchimp_get_meta_data();

		if (!empty($saved_data) && isset($saved_data['api_key'])) {
			$MailChimp = new \SuperMC\MailChimp($saved_data['api_key']);
			return @$MailChimp->call('lists/list');
		}

		return false;
	}
}
