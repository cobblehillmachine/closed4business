<?php 
//add theme options page
include('functions/c4b-settings.php');

//add lightbox rel tag to post images
function addlightboxrel_replace ($content)
{	global $post;
	$pattern = "/<a(.*?)href=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
  	$replacement = '<a$1href=$2$3.$4$5 rel="prettyPhoto"$6>';
    $content = preg_replace($pattern, $replacement, $content);
	$content = str_replace("%LIGHTID%", $post->ID, $content);
    return $content;
}
add_filter('the_content', 'addlightboxrel_replace');

//add wp alchemy
define('_TEMPLATEURL', WP_CONTENT_URL . '/' . stristr(TEMPLATEPATH, 'themes'));
include_once 'WPAlchemy/MetaBox.php';
if (is_admin()) wp_enqueue_style('custom_meta_css', _TEMPLATEURL . '/custom/meta.css');

//add gallery module
/*
function manage_cfb_gallery() {
	include('functions/gallery.php'); 	
}
function cfb_gallery() {
	add_menu_page('Gallery', 'Gallery', 'edit_pages', 'cfb_gallery', 'manage_cfb_gallery');
}
add_action('admin_menu', 'cfb_gallery');
*/

//add menus module
/*
function manage_cfb_menus() {
	include('functions/menus.php'); 	
}
function cfb_menus() {
	add_menu_page('Menus', 'Menus', 'edit_pages', 'cfb_menus', 'manage_cfb_menus');
}
add_action('admin_menu', 'cfb_menus');
*/

//add newsletter module
/*
function manage_cfb_newsletter() {
	include('functions/newsletter.php'); 	
}
function cfb_newsletter() {
	add_menu_page('Newsletter', 'Newsletter', 'edit_pages', 'cfb_newsletter', 'manage_cfb_newsletter');
}
add_action('admin_menu', 'cfb_newsletter');
*/

/*
//add export module
function manage_cfb_export() {
	include('functions/export.php');
}
function cfb_export() {
	add_submenu_page('cfb_newsletter','Export List', 'Export List', 'edit_pages', 'cfb_export', 'manage_cfb_export');
}
add_action('admin_menu', 'cfb_export');
*/

//add press module
/*
function manage_cfb_press() {
	include('functions/press.php'); 	
}
function cfb_press() {
	add_menu_page('Press', 'Press', 'edit_pages', 'cfb_press', 'manage_cfb_press');
}
add_action('admin_menu', 'cfb_press');
*/

//add sources module
function manage_cfb_sources() {
	include('functions/sources.php'); 	
}
function cfb_sources() {
	add_submenu_page('cfb_press','Sources', 'Sources', 'edit_pages', 'cfb_sources', 'manage_cfb_sources');
}
add_action('admin_menu', 'cfb_sources');

//change default email headers
if ( !class_exists('wp_mail_from') ) {
	class wp_mail_from {

		function wp_mail_from() {
			add_filter( 'wp_mail_from', array(&$this, 'fb_mail_from') );
			add_filter( 'wp_mail_from_name', array(&$this, 'fb_mail_from_name') );
		}

		// new name
		function fb_mail_from_name() {
			$name = 'Closed for Business';
			// alternative the name of the blog
			// $name = get_option('blogname');
			$name = esc_attr($name);
			return $name;
		}

		// new email-adress
		function fb_mail_from() {
			$email = 'no-reply@closed4business.com';
			$email = is_email($email);
			return $email;
		}

	}

	$wp_mail_from = new wp_mail_from();
}

//hide certain dashboard menu items
function remove_menus () {
global $menu;
	$restricted = array(__('Links'),__('Comments'),__('Tools'));
	end ($menu);
	while (prev($menu)){
		$value = explode(' ',$menu[key($menu)][0]);
		if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
	}
}
add_action('admin_menu', 'remove_menus');

//remove certain profile fields
function unset_lms_contactmethod( $contactmethods ) {
	  unset($contactmethods['aim']);
	  unset($contactmethods['jabber']);
	  unset($contactmethods['yim']);
	  return $contactmethods;
}
add_filter('user_contactmethods','unset_lms_contactmethod',10,1);

//remove the admin bar generated from wp_head and wp_foot?
add_filter( 'show_admin_bar', '__return_false' );

add_action( 'admin_print_scripts-profile.php', 'hide_admin_bar_prefs' );
function hide_admin_bar_prefs() { ?>
<style type="text/css">
	.show-admin-bar { display: none; }
</style>
<?php
}


// custom post type
add_action( 'init', 'create_post_type' );
function create_post_type() {

	$args1 = array(
		'labels' => array(
			'name' => __( 'Gallery' ),
			'singular_name' => __( 'Gallery' )
		),
		"publicly_queryable" => false,
    	"show_ui" => true,
		'public' => true,
		'menu_icon' => 'dashicons-screenoptions',
		'supports' => array('title' )
	);
  	register_post_type( 'Gallery', $args1);

  	$args2 = array(
		'labels' => array(
			'name' => __( 'Menus' ),
			'singular_name' => __( 'Menu' )
		),
		"publicly_queryable" => false,
    	"show_ui" => true,
		'public' => true,
		'menu_icon' => 'dashicons-screenoptions',
		'supports' => array( 'title' )
	);
  	register_post_type( 'Menus', $args2);

  	$args3 = array(
		'labels' => array(
			'name' => __( 'Press' ),
			'singular_name' => __( 'Press' )
		),
		"publicly_queryable" => false,
    	"show_ui" => true,
		'public' => true,
		'menu_icon' => 'dashicons-screenoptions',
		'supports' => array( 'title', 'editor' )
	);
  	register_post_type( 'Press', $args3);

  	flush_rewrite_rules();
}
