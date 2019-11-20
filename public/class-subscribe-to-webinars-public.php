<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://visualbi.com
 * @since      1.0.0
 *
 * @package    Subscribe_To_Webinars
 * @subpackage Subscribe_To_Webinars/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Subscribe_To_Webinars
 * @subpackage Subscribe_To_Webinars/public
 * @author     Balasubramaniyan M and K Gopal Krishna <website@visualbi.com>
 */
class Subscribe_To_Webinars_Public {

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
		 * defined in Subscribe_To_Webinars_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Subscribe_To_Webinars_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/subscribe-to-webinars-public.css', array(), $this->version, 'all' );

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
		 * defined in Subscribe_To_Webinars_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Subscribe_To_Webinars_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

        if($this->is_page_applicable()){
			wp_enqueue_script( $this->plugin_name.'-blockUI','//malsup.github.io/jquery.blockUI.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name.'-validate','//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/subscribe-to-webinars-public.js', array(  $this->plugin_name.'-blockUI',$this->plugin_name.'-validate','jquery' ), $this->version, false );
			wp_localize_script($this->plugin_name, 'subscribe_to_webinar_vars', array(
			'_bd_free_isp_error_msg' => get_option( '_bd_free_isp_error_msg' ),
			'_bd_consultants_error_msg' => get_option( '_bd_consultants_error_msg' ),
			'_blocked_domains_free_isp' => get_option( '_blocked_domains_free_isp' ),
			'_blocked_domains_consultants' => get_option( '_blocked_domains_consultants' ),
			'_contact_us_ajax_url' => esc_url( admin_url( 'admin-ajax.php' ) )
		)
	    );
        }

	}

	public function is_page_applicable()
	{
		global $post;
		$tagged_pages = ['4756','312'];
		if( in_array($post->ID, $tagged_pages) ){
			return true;
		}
		return false;
	}


	public function add_webinar_notify_form()
	{
		if( $this->is_page_applicable() ){
			$this->show_webinar_notify_form();
		}
	}

	public function show_webinar_notify_form()
	{
		?>
		<div class="webinar_notify" id="webinar_notify">
			<span class="helper"></span>
			<div class="webinar_notify_popup_tag_form_wrapper">
				<span  id="webinar-notify-close-button"  class="webinar-trigger"><i class="fa fa-times" aria-hidden="true"></i><br>Close</span>
				<form action="" id="webinar_notify_form" method="post" class="clearfix">
					<div class="webinar-container">
						<h2 style="padding-bottom:20px;">Subscribe to this Webinar</h2>
						<p id="webinar_subscribed">
							
						</p>
						<input id="pagename" name="pagename" value="<?php global $post; echo $post->post_title; ?>" type="hidden" />
						<input id="pageurl" name="pageurl" value="<?php echo "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" type="hidden" />
						<input id="hubutk" name="hubutk" value="<?php echo $_COOKIE['hubspotutk']; ?>" type="hidden" />
						<input id="ipaddr" name="ipaddr" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>" type="hidden" />
						<input id="webinar_title" name="webinar_title" value="" type="hidden" />
						<input class="webinar_notify_popup_tag_fields" id="email" type="text" name="email" placeholder="Business Email*" required="" aria-required="true" value="" />
						<p class="sbmt_btn">
							<button class="et_pb_contact_submit et_pb_button webinar_notify_popup_popup_form_submit" type="submit" name="webinar_notify_popup_popup_form_submit">SUBSCRIBE</button>
						</p>
						
					</div>
				</form>
			</div>
			
		</div>
		<?php
	}

	public function webinar_notify_form_submission()
	{
		$email 						= $_POST['email'];
		$webinar_title 				= $_POST['webinar_title'];
		$page_url 					= $_POST['pageurl'];
		$page_name 					= $_POST['pagename'];
		$hubspotutk      			= $_POST['hubspotutk'];
		$ip_addr         			= $_POST['ipaddr'];
		$form_id 					= '1498e4b6-e4ba-4e38-92ec-04d07b19ff1c';
		$portal_id 					= '2857966';
		$hapikey 					= '48161464-9aa4-4c0c-81a9-3ce65814157b';

		$hs_context = array(
			'hutk' 		=> $hubspotutk,
			'ipAddress' => $ip_addr,
			'pageUrl' 	=> $page_url,
			'pageName' 	=> $page_name
		);

		$hs_context_json = json_encode($hs_context);

		$str_post = "va_webinars_subscribed=" . urlencode($webinar_title) 
		. "&email=" . urlencode($email) 
		. "&hs_context=" . urlencode($hs_context_json);

		$this->check_property_and_update('va_webinars_subscribed',$webinar_title,$hapikey);

		$webinar_titles = $this->get_contact_property_value($email,'va_webinars_subscribed',$hapikey);

		if($webinar_titles !== false && $webinar_titles !== ""){
			$webinar_titles = explode(';', $webinar_titles);
			$webinar_titles[] = $webinar_title;
			$webinar_title = implode(';', array_unique($webinar_titles));
		}

		$endpoint = 'https://forms.hubspot.com/uploads/form/v2/'.$portal_id.'/'.$form_id;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $str_post);
		curl_setopt($ch, CURLOPT_URL, $endpoint);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/x-www-form-urlencoded' ));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response    = curl_exec($ch); 
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); 

		curl_close($ch);

		if($status_code == 204)
			echo "success";
		else
			echo "error";

		die();
	}

	
	public function hubspot_request($endpoint, $method="GET", $data=NULL, $content_type=NULL)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $endpoint);

		if(strtolower($method) == 'post')
			curl_setopt($ch, CURLOPT_POST, true);

		if(strtolower($method) != 'post' || strtolower($method) != 'get')
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));

		if($data != '' || $data != NULL)
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		if($content_type != '' || $content_type != NULL)
			curl_setopt($ch, CURLOPT_HTTPHEADER, array( "Content-Type: $content_type" ));

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response    = curl_exec($ch); 
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); 

		$response = json_decode($response,true);
		$response['http_status_code'] = $status_code;
		$response = json_encode($response);

		curl_close($ch);

		return $response;
	}

	public function check_property_and_update($property_name,$new_option,$hubspot_apikey){

		$property_response = $this->hubspot_request("https://api.hubapi.com/properties/v1/contacts/properties/named/$property_name?hapikey=$hubspot_apikey");
		$property_response = json_decode($property_response,true);

		if($property_response['http_status_code'] == 200){
			$options = array();
			foreach ($property_response['options'] as $option) {
				$options[] = $option['value'];
			}

			if(!in_array($new_option, $options)){
				$update_property_details = array();
				$update_property_details['name'] = $property_response['name'];
				$update_property_details['groupName'] = $property_response['groupName'];
				$update_property_details['description'] = $property_response['description'];
				$update_property_details['fieldType'] = $property_response['fieldType'];
				$update_property_details['formField'] = $property_response['formField'];
				$update_property_details['type'] = $property_response['type'];
				$update_property_details['displayOrder'] = $property_response['displayOrder'];
				$update_property_details['label'] = $property_response['label'];
				$update_property_details['options'] = $property_response['options'];

				$update_property_details['options'][] = array(
					'label' => $new_option,
					'value' => $new_option
				);

				$property_response = $this->hubspot_request("https://api.hubapi.com/properties/v1/contacts/properties/named/$property_name?hapikey=$hubspot_apikey", "PUT", json_encode($update_property_details), "application/json");
			}else{
				$property_response = json_encode($property_response);
			}
		}

		return $property_response;
	}

	public function get_contact_property_value($contact_email,$property_name,$hubspot_apikey){
		$contact_response = $this->hubspot_request("https://api.hubapi.com/contacts/v1/contact/email/$contact_email/profile?hapikey=$hubspot_apikey");
		$contact_response = json_decode($contact_response,true);

		if($contact_response['http_status_code'] == 200){
			if(array_key_exists($property_name, $contact_response['properties'])){
				$property_value = $contact_response['properties'][$property_name]['value'];
			}else{
				$property_value = false;
			}
		}else{
			$property_value = false;
		}

		return $property_value;
	}


}
