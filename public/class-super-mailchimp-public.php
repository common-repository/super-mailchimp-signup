<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://anderskristo.me
 * @since      1.0.0
 *
 * @package    Super_Mailchimp
 * @subpackage Super_Mailchimp/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Super_Mailchimp
 * @subpackage Super_Mailchimp/public
 * @author     Anders Kristoffersson <anderskristo@gmail.com>
 */
class Super_Mailchimp_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/super-mailchimp-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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
		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'dist/main.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/super-mailchimp-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script($this->plugin_name, 'ajax_object', array(
			'ajax_url' => admin_url('admin-ajax.php')
		));

	}

	/**
	 * Get saved data from admin back-end
	 */

	public function mailchimp_get_data() {
		$saved_meta = get_option('mailchimp_meta', '');

		if (!empty($saved_meta)) {
			return unserialize($saved_meta);
		}

		return false;
	}

	
	/**
	 * Shortcode
	 */

	public function mailchimp_shortcode() {
		$settings = shortcode_atts([], $atts);
        $data = $this->mailchimp_get_data();
        $lang = $data['selected_lang'];

		// Strings TODO: Make translateble
        $success_message = ($lang == 'sv' ? 'Tack! Du prenumenerar nu på vårat nyhetsbrev' : 'Takk! Du er nå med på Pollsplace liste');
		$invalid_message = ($lang == 'sv' ? 'Det måste vara en riktigt e-postadress' : 'Det må være en ekte e-postadresse');
		$error_message = ($lang == 'sv' ? 'Något gick fel' : 'Noe gikk galt');
		$terms_label = ($lang == 'sv' ? 'Terms text' : 'Terms text');
		$submit_text = 'OK';
        $email_placeholder = ($lang == 'sv' ? 'Din e-postadress' : 'Din e-post');
    
        $terms_link = ($data['terms_link'] ? $data['terms_link'] : '/');

		if (!empty($data)) {
			return '
				<div class="mailchimp-newsletter">				
					<div class="mailchimp-newsletter__form-wrapper mailchimp-newsletter__form-wrapper--active fn-form-wrapper">	
						<form class="mailchimp-newsletter__form-wrapper__form fn-super-mailchimp-submit" method="post">
							<input type="hidden" name="mailchimp_newsletter_ajax_nonce" value="' . wp_create_nonce('mailchimp_newsletter_form_nonce') . '">
							<input type="email" class="mailchimp-newsletter__form-wrapper__form__input fn-email" placeholder="' . $email_placeholder . '">

							<div class="mailchimp-newsletter__form-wrapper__form__actions">
								<button class="btn fn-confirm-submit" type="button">' . $submit_text . '</button>
							</div>
						</form>

						<div class="mailchimp-newsletter__form-wrapper__footer">
							<p class="mailchimp-newsletter__message mailchimp-newsletter__message--invalid fn-invalid">' . $invalid_message . '</p>
							<p class="mailchimp-newsletter__message mailchimp-newsletter__message--success fn-success">' . $success_message . '</p>
							<p class="mailchimp-newsletter__message mailchimp-newsletter__message--error fn-error">' . $error_message . '</p>
						</div>
					</div>
		
					<div class="mailchimp-newsletter__confirm-wrapper fn-confirm-wrapper">
						<div class="mailchimp-newsletter__confirm-wrapper__inner">
							<p class="mailchimp-newsletter__confirm-wrapper__inner__desc">Är denna e-post korrekt?</p>
							<p class="mailchimp-newsletter__confirm-wrapper__inner__email fn-email-holder"></p>
							
							<div class="mailchimp-newsletter__confirm-wrapper__inner__form-footer">
								<button class="btn btn--submit fn-newsletter-submit">Ja</button>
								<button class="btn btn--cancel fn-cancel">Avbryt</button>
							</div>
						</div>
					</div>
		
					<div class="mailchimp-newsletter__loading-wrapper fn-loading-wrapper">
						<p class="mailchimp-newsletter__loading-wrapper__loading fn-loading-status">Lägger till ...</p>
					</div>
				</div>
			';
		}

		return '
			<div class="mailchimp-newsletter">				
				<div class="mailchimp-newsletter__form-wrapper">
					<h3 class="mailchimp-newsletter__form-wrapper__title">400, error</h3>
				</div>
			</div>			
		';
	}

	/**
	 * Handle Ajax Request
	 */

	public function mailchimp_ajax_callback() {
		if (isset($_POST['mailchimp_newsletter_ajax_nonce']) && wp_verify_nonce($_POST['mailchimp_newsletter_ajax_nonce'], 'mailchimp_newsletter_form_nonce')) {
			$saved_data = $this->mailchimp_get_data();
			$email = isset($_POST['email']) ? sanitize_email($_POST['email']) : false;
			
			$response = array('success' => false);

			if (!empty($saved_data) && !empty($email)) {
				$list_id = $saved_data['selected_list'];
				$api_key = $saved_data['api_key'];

				$MailChimp = new \SuperMC\MailChimp($api_key);
	
				$result = $MailChimp->call('lists/subscribe', array(
					'id'                => $list_id,
					'email'             => array('email' => $email),
					'double_optin'      => false,
					'update_existing'   => true,
					'replace_interests' => false,
					'send_welcome'      => false,
				));

				if (!empty($result)) {
					if ($result['status'] === 'error') {
						$response['server_error'] = $result['error'];
					}

					if ($result['email'] && strlen($result['email'])) {
						$response['success'] = true;					
					}
				}
			}							

			if (empty($email) || empty($list_id)) {
				$response['success'] = false;
				$response['server_error'] = 'Validation: invalid parameters.';
			}
	
			if (empty($api_key) || !strlen($api_key)) {
				$response['success'] = false;
				$response['server_error'] = 'API: key is missing or invalid.';
			}

			echo json_encode($response);
			wp_die();
		}
	}
}