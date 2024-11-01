<?php
/*
Plugin Name: WP-Strings
Plugin URI:
Description: This plugin will allow you to input custom fields into the admin control panel, then you will be able to call these strings from your template files.
Version: 0.2
Author: Jonathan Garrett
Author URI: http://www.jgmediahouse.com
*/


register_activation_hook(__FILE__,'wp_strings_install'); 
register_deactivation_hook( __FILE__, 'wp_strings_uninstall' );

function create_default_records(){
	global $wpdb;
	$table_name = $wpdb->prefix . "wp_strings_data";
	$sql = $wpdb->prepare("INSERT INTO $table_name (String, Value, Custom) 
								VALUES 	('Telephone', '', '0'), 
										('Name', '', '0'),
										('Site Name', '', '0'),
										('Footer Text', '', '0'),
										('House', '', '0'),
										('Address Line 1', '', '0'),
										('Address Line 2', '', '0'),
										('Address Line 3', '', '0'),
										('Address Line 4', '', '0'),
										('Postcode', '', '0')
						");
	$wpdb->query($sql);
}

function wp_strings_install() {
    //Get the table name with the WP database prefix
    global $wpdb;
    $table_name = $wpdb->prefix . "wp_strings_data";
     //Check if the table already exists and if the table is up to date, if not create it
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $sql = $wpdb->prepare("CREATE TABLE " . $table_name . " (
              ID tinyint(9) NOT NULL AUTO_INCREMENT,
              String text NOT NULL,
              Value text,
			  Custom tinyint(1),
              UNIQUE KEY ID (ID)
            );");

        $wpdb->query($sql);
		}
		
	create_default_records();
}

function wp_strings_uninstall() {
	/* Deletes the database table */
	global $wpdb;
    $table_name = $wpdb->prefix . "wp_strings_data";
    //Check if the table already exists and if the table is up to date, if not create it
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
		$sql = $wpdb->prepare("DROP TABLE " . $table_name);
		$wpdb->query($sql);
	}
}

add_action('admin_head', 'admin_register_head');
function admin_register_head() {
    $siteurl = get_option('siteurl');
    $url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/style.css';
    echo "<link rel='stylesheet' type='text/css' href='$url' />\n";
	echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>';
}

function donate(){
	$donate = '
	<div class="wp-strings">
	<form name="_xclick" action="https://www.paypal.com/uk/cgi-bin/webscr" method="post">
            <input type="hidden" name="cmd" value="_xclick">
            <input type="hidden" name="business" value="jag1989@gmail.com">
            <input type="hidden" name="item_name" value="WP-Strings Contribution">
            <input type="hidden" name="currency_code" value="GBP">
            <input type="hidden" name="amount" value="">
            <input type="image" src="http://www.paypal.com/en_GB/i/btn/x-click-butcc-donate.gif" border="0" name="submit" alt="Make payments with PayPal - its fast, free and secure!">
		</form></div>';
	echo $donate;
}

function wp_strings_html_page() {
	?>
	<div class="wrap">
		<div id="icon-options-general" class="icon32"></div>
        <h2><?php _e('WP Strings'); ?></h2>  
        
        <?php if($_POST != NULL) options_update($_POST); ?> 
        <form method="post" action="" class="wp-strings">
        <?php wp_nonce_field(); ?>
         <div class="response"></div>
            <ul id="options">
            	<?php
					global $wpdb;
					$table_name = $wpdb->prefix . "wp_strings_data";
					$result = $wpdb->get_results("SELECT * FROM $table_name");	

						foreach($result as $row) {  ?>
                        
                        <li>
                            <p class="label"><label for="<?php echo $row->ID; ?>"><?php echo $row->String; ?></label></p>
                            <p class="input"><input name="<?php echo $row->ID; ?>" type="text" id="<?php echo $row->String; ?>" value="<?php echo $row->Value; ?>" size="20" /></p>
                            <?php if($row->Custom == 1) { ?>
                            	<p class="delete"><input type="button" name="delete" class="button-secondary delete" id="delete_<?php echo $row->ID; ?>" value="Delete" /></p>
                            <?php } ?>
                        </li>  	
						<?php } ?>
            </ul>
            <p>
            	<input type="submit" class="button-primary" value="<?php _e('Save Options') ?>" />
            </p>
        </form>
         <?php donate(); ?>
	</div>
    
	<?php
}

function options_update($postdata){
	global $wpdb;
	$updated=0;
	$table_name = $wpdb->prefix . "wp_strings_data";
	foreach($postdata as $id => $value){
		$sql = "UPDATE $table_name SET Value = '$value' WHERE ID = $id";
		$status = $wpdb->query($sql);
		if($status==1) $updated++;
	}		
	if( $updated > 0) { ?>
        <div id="message" class="updated">
            <p><strong><?php _e('Settings saved.') ?></strong></p>
        </div>	
	<?php }	else { ?>
    	<div id="message" class="updated">
            <p><strong><?php _e('Error saving settings. Please try again.') ?></strong></p>
        </div>
    <?php } 
}

function string_option($string, $echo=1){
	if(isset($string)){
		global $wpdb;
		$table_name = $wpdb->prefix . "wp_strings_data";
		$result = $wpdb->get_results("SELECT * FROM $table_name WHERE String = '$string'");	
		if(!$echo) return $result[0]->Value;
		else echo $result[0]->Value;
	}
}

function wp_strings_add_string() {
	?>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			var $total=1;
			jQuery('#add_new_button').click(function(){
				$total++;
				jQuery("ul.add_strings").append('<li><p class="label"><label>String Name: </label></p><p class="input"><input name="new_string_'+$total+'" type="text" size="20" /></p></li>');
			});
		});
    </script>  
            
	<div class="wrap">
		<div id="icon-options-general" class="icon32"></div>
        <h2>WP Strings - Add Custom String</h2>  
        <?php if($_POST != NULL) insert_new_strings($_POST); ?> 
        	<form method="post" action="">
                <ul id="options" class="add_strings">
                 	<li>
                    	<p class="label"><label>String Name: </label></p>
                        <p class="input"><input name="new_string_1" type="text" size="20" /></p>
                    </li>        	
                </ul>
                <p><input type="submit" class="button-primary" value="<?php _e('Save New Strings') ?>" /><input type="button" class="button-secondary" id="add_new_button" value="Add another"/></p>          
            </form> 
            <?php donate(); ?>
	</div>
    
	<?php
}

function insert_new_strings($strings){
	global $wpdb;
	$inserted=0;
	$table_name = $wpdb->prefix . "wp_strings_data";
	
	foreach($_POST as $id => $string){
		if($string != ''){
			$sql = "INSERT INTO $table_name SET String = '$string', Custom = '1'";
			$status = $wpdb->query($sql);
			if($status==1) $inserted++;
		}
	}	
		
	if( $inserted > 0) { ?>
        <div id="message" class="updated">
            <p><strong><?php _e('Strings saved.') ?></strong></p>
        </div>	
	<?php }	else { ?>
    	<div id="message" class="updated">
            <p><strong><?php _e('Error saving strings. Please try again.') ?></strong></p>
        </div>
    <?php } 
}





if ( is_admin() ){
	/* Call the html code */
	add_action('admin_menu', 'wp_strings_admin_menu');
	
	function wp_strings_admin_menu() {
		global $settings;
		$settings = add_menu_page('WP Strings - Options', 'WP Strings', 'administrator', 'wp-strings', 'wp_strings_html_page');
		add_submenu_page('wp-strings', 'Add Custom String', 'Custom Strings', 'administrator', 'custom-string', 'wp_strings_add_string');
		/*add_options_page;*/
	}
}


function load_scripts($hook){
	global $settings;
	
	if($hook != $settings)
		return;
		
	wp_enqueue_script('strings-ajax', plugin_dir_url(__FILE__) . 'js/strings-ajax.js', array('jquery'));	
}
add_action('admin_enqueue_scripts', 'load_scripts');

function delete_record_process(){
	global $wpdb;
	if(isset($_POST['id'])){
		$id = $_POST['id'];
		$id = str_replace('delete_','',$id);
		
		if(is_numeric($id)){
			$table_name = $wpdb->prefix . "wp_strings_data";
			$delete=0;
			
			$sql = "DELETE FROM $table_name WHERE ID = '$id'";
			$status = $wpdb->query($sql);	
			
		}
	}
	die();
}
add_action('wp_ajax_delete_record', 'delete_record_process');

function string_shortcode( $atts, $content = null ){
	extract( shortcode_atts( array( 's' => ''), $atts ) );
	$get = "{$s}";
	global $wpdb;
	$table_name = $wpdb->prefix . "wp_strings_data";
	$result = $wpdb->get_results("SELECT * FROM $table_name WHERE String = '$get'");	
	return $result[0]->Value;
}
add_shortcode( 'string', 'string_shortcode' );


?>