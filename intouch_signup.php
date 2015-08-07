<?php
    /*
    Plugin Name: InTouch Forms
    Plugin URI: http://www.intouchcrm.com
    Description: Plugin for displaying signup forms within a user's account from intouch. It allows the user to easily add their signup form in.
    Author: InTouch
    Version: 1.5
    Author URI: http://www.intouchcrm.com/
    */

    //PHP does not have a native way of implementing ENUMS, we must use a class unfortuantely.

    //Check to see if file_get_html is allready loaded to avoid Fatal error
if(!function_exists(file_get_html)) {
  require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . 'simple_html_dom.php');
}

	include('shortcode.php');
    class Response
	{
    	const updated = "updated";
    	const error = "error";

	}

	///Object to handle requests to the REST API and manage responses / plugin settings
	class Intouch{

		//Constants, that only change with new releases
		//Raw site address
		const api_website = "https://intouchcrm.co.uk";

		//Endpoint of Intouch REST api to be consumed
		const api_endpoint = "https://intouchcrm.co.uk/app/api/Intouch.svc/";

		//Version of the api to request
		const api_version = 1.0;

		//User's API key
		private $apikey;

		private $affliate_id;

		//Stores the last error - if any
		private $last_error;


		// Paid user rols array
		public $paidUserRoles = array('Sales' , 'Marketing' , 'Complete' , 'Trial' , 'BCSG');
		//Getter for apikey
		public function GetApiKey(){
			return $this->apikey;
		}

		//Getter for Last error
		public function GetLast_error(){
			return $this->last_error;
		}

		//Default Constructor stuff
		public function Intouch($apikey = ""){

			if(strlen($apikey) == 0)
				//try and retrieve from wordpress settings
				$this->apikey = get_option('intouch_apikey');
			else
				$this->apikey = $apikey;

		}

		//Destruct
		function __destruct() {

			 //Close any connections

		}

		//Makes a request to a particular part of the API - prepends api_endpoint to all calls
		//$request_path = "/"
		private function MakeRequest($request_path){

			if(isset($this->apikey))
				//Basic for now - should really deserialise into response object
				return json_decode(file_get_contents(Intouch::api_endpoint . $request_path));
			else
				return Intouch::PrettyReturn(Response::error, "Sorry, there is no Api Key set.");
		}

		//Methods

		//Clears settings such as API key
		//TODO: Allow option to clear old API key references?
		public function ClearSettings(){

			//Clear
			$this->apikey = null;
			//Save
			update_option('intouch_apikey', $this->apikey);

			//Success message
			return Intouch::PrettyReturn(Response::updated, "Your settings have been cleared.");

		}

		//Validate the current API key
		public function ValidateApiKey(){

			//Make a request
			$verify = $this->MakeRequest("/Verify/$this->apikey/");

			if(isset($verify) && $verify->{'auth'}){

				//Update the system stored key
				update_option('intouch_apikey', $this->apikey);

				//Return success
				return Intouch::PrettyReturn(Response::updated, "Options saved. That Api key belongs to " . $verify->{'name'});

			}else
				return Intouch::PrettyReturn(Response::error, "Sorry, there was a problem contacting the API");

		}


		public function GetAffliateID(){

			return get_option('intouch_affliate_id');

		}
		//Gets an account's signup forms by API key
		public function GetSignupForms(){

			return $this->MakeRequest("/Signupforms/$this->apikey/");

		}

		///Gets a signup form based on UID
		public function GetSignupForm($uid){

			if(strlen($uid) == 0)
				return Intouch::PrettyReturn(Response::error, "Sorry, no signup form ID was supplied.");
			else
				return $this->MakeRequest("/Signupforms/get/$this->apikey/$uid/");


		}

		//Returns a message and formats as arguments stipulate
		public function PrettyReturn($Response, $message = "Sorry, there was an error."){

			echo "<div class=\"$Response\"><p><strong>$message</strong></p></div>";

		}

		public function formHtml($html){


			$html = new simple_html_dom($html);

			$logo = $html->find('img' . 0)->outertext;

			$powerd_by = $html->find('a', 0 );

			$form = $html->find('form', 0)->outertext;

			$accountType = $this->GetAccountLevel();

			$filteredHtml = $form;

			if($accountType != 'Free') $filteredHtml = $logo . $filteredHtml;

			$affiliate_id = ($this->GetAffliateID() != "")?"?a_aid=".$this->GetAffliateID():"";

			if(!empty($powerd_by) && $affiliate_id != "") $powerd_by->setAttribute('href' , $powerd_by->getAttribute('href').  $affiliate_id);

			if(empty($powerd_by))
				$powerd_by = '<div style="text-align:right;">Powered By : <a href="http://www.intouchcrm.com'.$affiliate_id.'">InTouch CRM</a></div>';
			else
				$powerd_by = '<div style="text-align:right;">Powered By : ' .$powerd_by->outertext. '</div>';

			if(in_array($accountType , $this->paidUserRoles)){
				if(get_option('intouch_activate_powered_by') == 1){
					$filteredHtml = $filteredHtml . $powerd_by;
				}
			} else {
				$filteredHtml = $filteredHtml . $powerd_by;
			}

			// Add the validation script to the plugin output - #BUG 001
			$filteredHtml = $filteredHtml . $html->find('script',0)->outertext;

			return $filteredHtml;
		}

		public function GetAccountLevel(){

			return $this->MakeRequest("/AccountLevel/$this->apikey/");

		}

	}

	//--------------------------
	//Manage the plugin's hooks. They allow the plugin to listen for Wordpress events and act upon them.
	//E.g. showing an admin menu option
	//More on hooks / action references: http://codex.wordpress.org/Plugin_API/Action_Reference
	//TODO: Should add Activate, deactivate and uninstall hooks http://codex.wordpress.org/Function_Reference/register_uninstall_hook
	//ideally deactivate should go through all pages removing any intouch_signupform tags - management say no!

	//Handle admin menu page click - include the gui file for admin
	function intouch_signup_admin() {
		include('intouch_signup_admin.php');
	}

	//Show in admin menus - users need the "manage_pages" right
	function intouch_signup_admin_actions() {
		add_options_page("InTouch Forms", "InTouch Forms", 1, "InTouch_Forms", "intouch_signup_admin");
	}

	//pickup when admin menu has rendered.. Add hook:
	add_action('admin_menu', 'intouch_signup_admin_actions');

	//This allows us to hook signup forms in through widget text
	add_filter('widget_text', 'intouch_widget_text_filter', 9);

	//Checks if this current content has any intouch_signupform tags within it - if so invokes the do_shortcode handler
	function intouch_widget_text_filter( $content ) {

		//Ensure that our handler only lets intouch based tags through
		if ( ! preg_match( '/\[[\r\n\t ]*intouch_signupform[\r\n\t ].*?\]/', $content ) )
			//This isn't an intouch tag, just return the content un-changed
			return $content;

		//Pass to short code event handlers
		$content = do_shortcode( $content );

		return $content;
	}

	//Hook once plugins have all loaded - ensure that the intouch sginup form shortcodes are correctly monitored and handled
	add_action( 'plugins_loaded', 'intouch_add_shortcodes' );

	//Add shortcodes for Intouch signup forms - allows them to be monitored and replaced
	function intouch_add_shortcodes() {
		add_shortcode( 'intouch_signupform', 'intouch_signup_form_tag_func' );
	}

	//Short code tag functions - fired when tags matching intouch signup form syntax (above) are located
	function intouch_signup_form_tag_func($atts, $content = null, $code = '') {

		//Exclude RSS feeds
		if ( is_feed() )
			return '[intouch_signupform]';

		//Translate the found tag into it's array bound items
		$atts = shortcode_atts(array('uid' => ''), $atts);

		//Try and retrieve the UID of the signup form from this tag's attributes
		$uid = trim( $atts['uid'] );

		//instantiate intouch api class
		$Intouchapi = new Intouch();

		//Try and reteieve the signup form via API
		$signupform = $Intouchapi->GetSignupForm($uid);

		//Was it loaded?
		if ( ! $signupform )
			return '[intouch_signupform could not be loaded]';

		//Found and loaded successfully, push back the html to be displayed
		return $Intouchapi->formHtml($signupform->formHtml);
//		return $signupform->{'formHtml'};
	}


	//Allows the plugin to pickup when wordpress is rendering a page post.
	//http://codex.wordpress.org/Plugin_API/Action_Reference/the_post (not much documentation on this)


// Hooks your functions into the correct filters

?>
