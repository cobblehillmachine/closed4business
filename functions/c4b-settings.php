<?php

$themename = "Closed for Business";
$shortname = "c4b";

$options = array (
	
	array(	"name" => "SEO / Meta Data",
			"type" => "title"),
			
	array(	"type" => "open"),
	
	array(	"name" => "Description",
			"desc" => "Website description meta tag",
			"id" => $shortname."_meta_desc",
			"std" => "",
			"type" => "textarea"),
	
	array(	"name" => "Keywords",
			"desc" => "Separate, keywords, with, commas",
			"id" => $shortname."_meta_keywords",
			"std" => "",
			"type" => "textarea"),
	
	array(	"name" => "Analytics Code",
			"desc" => "Paste Google Analytics code here",
			"id" => $shortname."_google_analytics",
			"std" => "",
			"type" => "textarea"),
	
	array(	"type" => "open"),

	array(	"name" => "Website Content",
			"type" => "title"),
			
	array(	"type" => "open"),
	
	array(	"name" => "Home Page",
			"desc" => "Text to display on the home page",
			"id" => $shortname."_content_home",
			"std" => "",
			"type" => "textarea"),
	
	array(	"name" => "Menu Page",
			"desc" => "Text to display on the menu page",
			"id" => $shortname."_content_menu",
			"std" => "",
			"type" => "textarea"),

	array(	"name" => "Friends Page",
			"desc" => "Text to display on the friends page",
			"id" => $shortname."_content_friends",
			"std" => "",
			"type" => "textarea"),
		
	array(	"type" => "open"),

	array(	"name" => "Social Media",
			"type" => "title"),
			
	array(	"type" => "open"),
	
	array(	"name" => "Facebook",
			"desc" => "Full URL of the Facebook page.",
			"id" => $shortname."_facebook",
			"std" => "",
			"type" => "text"),
	
	array(	"name" => "Twitter",
			"desc" => "Full URL of the Twitter page.",
			"id" => $shortname."_twitter",
			"std" => "",
			"type" => "text"),
			
	array(	"name" => "Instagram",
			"desc" => "Full URL of the Instagram page.",
			"id" => $shortname."_instagram",
			"std" => "",
			"type" => "text"),

	array(	"type" => "open"),
	
	/*array(	"type" => "open"),
	
	array(	"name" => "Footer",
			"type" => "title"),
			
	array(	"type" => "open"),
	
	array(	"name" => "Footer Text",
            "desc" => "",
			"id" => $shortname."_footer_text",
			"std" => "",
			"type" => "text"),*/
				
	array(	"type" => "close")
	
);

function mytheme_add_admin() {

    global $themename, $shortname, $options;

    if ( $_GET['page'] == basename(__FILE__) ) {
    
        if ( 'save' == $_REQUEST['action'] ) {

                foreach ($options as $value) {
                    update_option( $value['id'], $_REQUEST[ $value['id'] ] ); }

                foreach ($options as $value) {
                    if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], stripslashes($_REQUEST[ $value['id'] ])  ); } else { delete_option( $value['id'] ); } }

                header("Location: options-general.php?page=c4b-settings.php&saved=true");
                die;

        } else if( 'reset' == $_REQUEST['action'] ) {

            foreach ($options as $value) {
                delete_option( $value['id'] ); }

            header("Location: options-general.php?page=c4b-settings.php&reset=true");
            die;

        }
    }

    // add_theme_page($themename." Options", "".$themename." Options", 'edit_themes', basename(__FILE__), 'mytheme_admin');
	//add_options_page(''.$themename.' Settings', ''.$themename.' Settings', 'edit_pages', basename(__FILE__), 'mytheme_admin');
	add_menu_page('C4B Setup', 'C4B Setup', 'edit_pages', basename(__FILE__), 'mytheme_admin');

}

function mytheme_admin() {

    global $themename, $shortname, $options;

    if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings saved.</strong></p></div>';
    if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings reset.</strong></p></div>';
    
?>
<div class="wrap">
<h2><?php echo $themename; ?> Settings</h2>

<form method="post">



<?php foreach ($options as $value) { 
    
	switch ( $value['type'] ) {
	
		case "open":
		?>
        <table width="100%" border="0">
		
        
        
		<?php break;
		
		case "close":
		?>
		
        </table><br />
        
        
		<?php break;
		
		case "title":
		?>
		<table width="100%" border="0"><tr>
        	<td colspan="2"><h3><?php echo $value['name']; ?></h3></td>
        </tr>
                
        
		<?php break;

		case 'text':
		?>
        
        <tr>
            <td width="30%" valign="top"><?php echo $value['name']; ?><br /><small><?php echo $value['desc']; ?></small><br /><br /></td>
            <td width="70%" valign="top"><input style="width:400px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>" /></td>
        </tr>

		<?php 
		break;
		
		case 'textarea':
		?>
        
        <tr>
            <td width="30%" valign="top"><?php echo $value['name']; ?><br /><small><?php echo $value['desc']; ?></small><br /><br /></td>
            <td width="70%" valign="top"><textarea name="<?php echo $value['id']; ?>" style="width:400px; height:200px;" type="<?php echo $value['type']; ?>" cols="" rows=""><?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?></textarea></td>
            
        </tr>


		<?php 
		break;
		
		case 'select':
		?>
        <tr>
            <td width="30%" valign="top"><?php echo $value['name']; ?><br /><small><?php echo $value['desc']; ?></small><br /><br /></td>
            <td width="70%" valign="top"><select style="width:240px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>"><?php foreach ($value['options'] as $option) { ?><option<?php if ( get_settings( $value['id'] ) == $option) { echo ' selected="selected"'; } elseif ($option == $value['std']) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option><?php } ?></select></td>
       </tr>

		<?php
        break;
            
		case "checkbox":
		?>
            <tr>
            <td width="30%" valign="top"><strong><?php echo $value['name']; ?></strong><br /><small><?php echo $value['desc']; ?></small><br /><br /></td>
                <td width="70%" valign="top"><? if(get_settings($value['id'])){ $checked = "checked=\"checked\""; }else{ $checked = ""; } ?>
                        <input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />
                        </td>
            </tr>
            

            
        <?php 		break;
	
 
} 
}
?>

<!-- </table> -->

<table>
<tr><td>
<p class="submit">
<input name="save" type="submit" value="Save changes" />    
<input type="hidden" name="action" value="save" />
</p>
</form></td><td>
<form method="post">
<p class="submit">
<input name="reset" type="submit" value="Reset" />
<input type="hidden" name="action" value="reset" />
</p>
</form>
</td></tr>
</table>

<?php } add_action('admin_menu', 'mytheme_add_admin'); ?>