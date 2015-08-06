<?php
function intouch_add_mce_button() {
	// check user permissions
	if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
		return;
	}
	// check if WYSIWYG is enabled
	if ( 'true' == get_user_option( 'rich_editing' ) ) {
		add_filter( 'mce_external_plugins', 'intouch_add_tinymce_plugin' );
		add_filter( 'mce_buttons', 'intouch_register_mce_button' );
	}
}

add_action('admin_head', 'intouch_add_mce_button');

// Declare script for new button
function intouch_add_tinymce_plugin( $plugin_array ) {
	$plugin_array['intouch_mce_button'] = plugins_url('js/mce-button.js', __FILE__);
	return $plugin_array;
}

// Register new button in the editor
function intouch_register_mce_button( $buttons ) {
	array_push( $buttons, 'intouch_mce_button' );
	return $buttons;
}



add_action('admin_enqueue_scripts',  'action_admin_scripts_init');

function action_admin_scripts_init(){
	
	wp_enqueue_style('popup', plugins_url( 'css/popup.css', __FILE__), false, '1.0', 'all');
	
	
	wp_enqueue_script('jquery-livequery', plugins_url('js/jquery.livequery.js' , __FILE__), false, '1.1.1', false);
	wp_enqueue_script('intouch-popup', plugins_url( 'js/popup.js' , __FILE__), false, '1.0', false);
	wp_localize_script('jquery', 'IntouchShortcodes', array('plugin_folder' => plugins_url('' , __FILE__)) );
}