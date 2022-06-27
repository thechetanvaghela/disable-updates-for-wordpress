<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/thechetanvaghela
 * @since      1.0.0
 *
 * @package    Disable_Updates_For_Wordpress
 * @subpackage Disable_Updates_For_Wordpress/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Disable_Updates_For_Wordpress
 * @subpackage Disable_Updates_For_Wordpress/admin
 * @author     Chetan Vaghela <ckvaghela92@gmail.com>
 */
class Disable_Updates_For_Wordpress_Admin {

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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/disable-updates-for-wordpress-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'dufw-scripts-js', plugin_dir_url( __FILE__ ) . 'js/disable-updates-for-wordpress-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add menu page to admin
	 *
	 * @since    1.0.0
	 */
	public function dufw_admin_menu_callback() {
			# add menu page option to admin
			add_menu_page('WP Disable Updates','WP Disable Updates','manage_options','dufw_admin_menu_settings_page',array($this,'dufw_admin_menu_settings_page_callback'),'dashicons-update-alt');
	}

	/**
	 * wordpress core remove update callback function
	 *
	 * @since    1.0.0
	 */
	public function dufw_admin_init_callback()
	{
		# Wordpress Core Update
		$dufw_wp = get_option('dufw-wp-update-disable');
		$dufw_wp = !empty($dufw_wp) ? $dufw_wp : '';
		if($dufw_wp == 'yes')
		{
			if ( !function_exists("remove_action") ) return;

			add_filter( 'pre_option_update_core', '__return_null' );
			remove_action( 'wp_version_check', 'wp_version_check' );
			remove_action( 'admin_init', '_maybe_update_core' );
			wp_clear_scheduled_hook( 'wp_version_check' );
			# version 3.0
			wp_clear_scheduled_hook( 'wp_version_check' );
			# version 3.7+
			remove_action( 'wp_maybe_auto_update', 'wp_maybe_auto_update' );
			remove_action( 'admin_init', 'wp_maybe_auto_update' );
			remove_action( 'admin_init', 'wp_auto_update_core' );
			wp_clear_scheduled_hook( 'wp_maybe_auto_update' );
			remove_all_filters( 'plugins_api' );
		}

		$dufw_auto_wp = get_option('dufw-all-auto-update-disable');
		$dufw_auto_wp = !empty($dufw_auto_wp) ? $dufw_auto_wp : '';
		if($dufw_auto_wp == 'yes')
		{
			add_filter( 'auto_update_translation', '__return_false' );
			add_filter( 'automatic_updater_disabled', '__return_true' );
			add_filter( 'allow_minor_auto_core_updates', '__return_false' );
			add_filter( 'allow_major_auto_core_updates', '__return_false' );
			add_filter( 'allow_dev_auto_core_updates', '__return_false' );
			add_filter( 'auto_update_core', '__return_false' );
			add_filter( 'wp_auto_update_core', '__return_false' );
			add_filter( 'auto_core_update_send_email', '__return_false' );
			add_filter( 'send_core_update_notification_email', '__return_false' );
			add_filter( 'auto_update_plugin', '__return_false' );
			add_filter( 'auto_update_theme', '__return_false' );
			add_filter( 'automatic_updates_send_debug_email', '__return_false' );
			add_filter( 'automatic_updates_is_vcs_checkout', '__return_true' );
			remove_action( 'init', 'wp_schedule_update_checks' );
			remove_all_filters( 'plugins_api' );
			add_filter( 'automatic_updates_send_debug_email ', '__return_false', 1 );
			if( !defined( 'AUTOMATIC_UPDATER_DISABLED' ) ) define( 'AUTOMATIC_UPDATER_DISABLED', true );
			if( !defined( 'WP_AUTO_UPDATE_CORE') ) define( 'WP_AUTO_UPDATE_CORE', false );
		}
	}

	/**
	 * Plugin remove update callback function
	 *
	 * @since    1.0.0
	 */
	public function dufw_site_transient_update_plugins_callback($value)
	{
		# plugins
	    $dufw_plugins = get_option('dufw-plugin-disable');
	    $dufw_plugins = !empty($dufw_plugins) ? $dufw_plugins : array();
	    if(!empty($dufw_plugins))
	    {
	    	foreach ($dufw_plugins as $key => $dufw_plugin) {
	    		if(!empty($dufw_plugin))
	    		{
	    	 		unset($value->response[$dufw_plugin]);
	    		}
	    	}
	    }
	    return $value;
	}

	/**
	 * theme remove update callback function
	 *
	 * @since    1.0.0
	 */
	public function dufw_site_transient_update_themes_callback($value)
	{
		# themes
	    $dufw_themes = get_option('dufw-theme-disable');
	    $dufw_themes = !empty($dufw_themes) ? $dufw_themes : array();
	    if(!empty($dufw_themes))
	    {
	    	foreach ($dufw_themes as $key => $dufw_theme) {
	    		if(!empty($dufw_theme))
	    		{
	    	 		unset($value->response[$dufw_theme]);
	    		}
	    	}
	    }
	    return $value;
	}

	/**
	 * admin notice removable callback function
	 *
	 * @since    1.0.0
	 */
	public function dufw_add_removable_arg_callback($args)
	{
		array_push($args,'dufw-msg');
    	return $args;
	}

	/**
	 * admin notice callback function
	 *
	 * @since    1.0.0
	 */
	public function dufw_admin_notice_callback() {

		# admin notice for form submit
		if (!empty($_REQUEST['dufw-msg'])) 
		{
			if($_REQUEST['dufw-msg'] == 'success')
			{
				$message = 'Settings Saved';
				$notice_class = 'updated notice-success';
			}
			else if($_REQUEST['dufw-msg'] == 'error')
			{
				$message = 'Sorry, your nonce did not verify';
				$notice_class = 'notice-error';
			}
			else
			{
				$message = 'Something went wrong!';
				$notice_class = 'notice-error';
			}
			# print admin notice
			printf('<div id="message" class="notice '.$notice_class.' is-dismissible"><p>' . __('%s.', 'disable-updates-for-wordpress') . '</p></div>', $message);
		}

	}

	/**
	 * Save options
	 *
	 * @since    1.0.0
	 */
	public function dufw_admin_menu_save_callback()
	{
		# declare variables
		$status = "";
		$save_selected_plugins = array();
		# check current user have manage options permission
		if ( current_user_can('manage_options') ) 
		{
			# check form submission
	      	if (isset($_POST['disable-updates-for-wp-form-settings'])) 
	     	{
	        	# current page url
		        $pluginurl = admin_url('admin.php?page=dufw_admin_menu_settings_page');
	        	# check nonce
	        	if ( ! isset( $_POST['dufw_nonce'] ) || ! wp_verify_nonce( $_POST['dufw_nonce'], 'dufw_action_nonce' ) ) 
	        	{
	        		$redirect_url = add_query_arg('dufw-msg', 'error',$pluginurl);
		            wp_safe_redirect( $redirect_url);
		            exit();
				} 
				else 
				{	
					$status = 'success';
					# all Plugins
	            	$disable_all_plugin = isset($_POST['dufw-plugin-all-disable']) ? sanitize_text_field($_POST['dufw-plugin-all-disable']) : 'no';
	            	update_option('dufw-plugin-all-disable', sanitize_text_field($disable_all_plugin));

	            	# all themes
	            	$disable_all_theme = isset($_POST['dufw-theme-all-disable']) ? sanitize_text_field($_POST['dufw-theme-all-disable']) : 'no';
	            	update_option('dufw-theme-all-disable', sanitize_text_field($disable_all_theme));

	            	# wordpress core update
	            	$disable_wp_update = isset($_POST['dufw-wp-update-disable']) ? sanitize_text_field($_POST['dufw-wp-update-disable']) : 'no';
	            	update_option('dufw-wp-update-disable', sanitize_text_field($disable_wp_update));

	            	# wordpress auto updates
	            	$disable_auto_update = isset($_POST['dufw-all-auto-update-disable']) ? sanitize_text_field($_POST['dufw-all-auto-update-disable']) : 'no';
	            	update_option('dufw-all-auto-update-disable', sanitize_text_field($disable_auto_update));

	            	# plugins
	            	if(isset($_POST['dufw-plugin-disable']) && !empty($_POST['dufw-plugin-disable']))
	            	{
	            		$selected_plugins = array_map( 'sanitize_text_field', $_POST['dufw-plugin-disable'] );
	                	$save_selected_plugins = !empty($selected_plugins) ? $selected_plugins : array();
	            		update_option('dufw-plugin-disable', $save_selected_plugins);
	            	}
	            	else
	            	{
	            		update_option('dufw-plugin-disable', '');
	            	}

	            	# Themes
	            	if(isset($_POST['dufw-theme-disable']) && !empty($_POST['dufw-theme-disable']))
	            	{
	            		$selected_themes = array_map( 'sanitize_text_field', $_POST['dufw-theme-disable'] );
	                	$save_selected_themes = !empty($selected_themes) ? $selected_themes : array();
	            		update_option('dufw-theme-disable', $save_selected_themes);
	            	}
	            	else
	            	{
	            		update_option('dufw-theme-disable', '');
	            	}

	            	$redirect_url = add_query_arg('dufw-msg',$status,$pluginurl);
	                wp_safe_redirect( $redirect_url);
					exit();
				}
			}
		}
	}

	/**
	 * callback menu page to admin
	 *
	 * @since    1.0.0
	 */
	public function dufw_admin_menu_settings_page_callback() {

		# get options
	    $dufw_all_plugins = get_option('dufw-plugin-all-disable');
	    $dufw_all_plugins = !empty($dufw_all_plugins) ? $dufw_all_plugins : '';

	    $dufw_all_themes = get_option('dufw-theme-all-disable');
	    $dufw_all_themes = !empty($dufw_all_themes) ? $dufw_all_themes : '';

	    $dufw_wp = get_option('dufw-wp-update-disable');
	    $dufw_wp = !empty($dufw_wp) ? $dufw_wp : '';

	    $dufw_auto_wp = get_option('dufw-all-auto-update-disable');
	    $dufw_auto_wp = !empty($dufw_auto_wp) ? $dufw_auto_wp : '';

		# plugins
	    $dufw_plugins = get_option('dufw-plugin-disable');
	    $dufw_plugins = !empty($dufw_plugins) ? $dufw_plugins : array();

	    # themes
	    $dufw_themes = get_option('dufw-theme-disable');
	    $dufw_themes = !empty($dufw_themes) ? $dufw_themes : array();
		?>
		<div class="wrap">
			<div id="dufw-setting-container">
				<div id="dufw-body">
					<form method="post" enctype="multipart/form-data" id="dufw-setting-container-form">
						<div id="dufw-body-content">
							<div class="dufw-cards-wrap">
								<main class="dufw-card">
							        <h3><?php esc_html_e('Disable Updates for Plugins','disable-updates-for-wordpress'); ?></h3>
							        <div class="dufw-plugins-wrap">
								        <?php 
								        if ( ! function_exists( 'get_plugins' ) ) {
										    require_once ABSPATH . 'wp-admin/includes/plugin.php';
										}
								        $all_plugins = get_plugins(); 
								        if(!empty($all_plugins))
								        {
								        	$all_plugin_checked = $dufw_all_plugins == 'yes' ? 'checked' : '';
								        	$all_plugin_status = $dufw_all_plugins == 'yes' ? 'On' : 'Off';
								        	$all_plugin_class = $dufw_all_plugins == 'yes' ? 'on' : '';
								        	?>
								        	<div class="input-row">
										        <label class="dufw-check-all-plugins" for="dufw-check-all-plugins"><?php esc_html_e('Check all plugins','disable-updates-for-wordpress'); ?></label>
									            <div class="toggle <?php echo esc_attr($all_plugin_class); ?>">
									                <input type="checkbox" class="checkedAllplugin" name="dufw-plugin-all-disable" <?php echo esc_attr($all_plugin_checked); ?> value="<?php echo esc_attr('yes'); ?>" id="<?php esc_html_e('dufw-check-all-plugins','disable-updates-for-wordpress'); ?>">
									                <span class="slider"></span>
									                <span class="label"><?php echo esc_attr($all_plugin_status); ?></span>
									            </div>
							        		</div>
							        		<?php
								        	foreach ($all_plugins as $key => $plugin) 
								        	{
								        		$pluginname = isset($plugin['Name']) ? $plugin['Name'] : '';
								        		$TextDomain = isset($plugin['TextDomain']) ? 'dufw-'.$plugin['TextDomain'] : '';
								        		$plugin_checked = in_array($key , $dufw_plugins) ? 'checked' : '';
								        		$plugin_status = in_array($key , $dufw_plugins) ? 'On' : 'Off';
								        		$status_class = in_array($key , $dufw_plugins) ? 'on' : '';
										        ?>
								        		<div class="input-row">
										            	<label for="<?php echo esc_html($TextDomain); ?>"><?php echo esc_html($pluginname); ?></label>
										            <div class="toggle <?php echo esc_attr($status_class); ?>">
										                <input class="checkSingleplugin" type="checkbox" name="dufw-plugin-disable[]" <?php echo esc_attr($plugin_checked); ?> value="<?php echo esc_attr($key); ?>" id="<?php echo esc_html($TextDomain); ?>">
										                <span class="slider"></span>
										                <span class="label"><?php echo esc_attr($plugin_status); ?></span>
										            </div>
								        		</div>
										        <?php 
								           	} 
									    } 
									    ?>
								    </div>
							    </main>
							    <main class="dufw-card">
							        <h3><?php esc_html_e('Disable Updates for Themes','disable-updates-for-wordpress'); ?></h3>
							        <div class="dufw-themes-wrap">
								        <?php 
								        $wp_get_themes = wp_get_themes(); 
								        if(!empty($wp_get_themes))
								        {
								        	$all_theme_checked = $dufw_all_themes == 'yes' ? 'checked' : '';
								        	$all_theme_status = $dufw_all_themes == 'yes' ? 'On' : 'Off';
								        	$all_theme_class = $dufw_all_themes == 'yes' ? 'on' : '';
								        	?>
								        	<div class="input-row">
										        <label class="dufw-check-all-themes" for="dufw-check-all-themes"><?php esc_html_e('Check all themes','disable-updates-for-wordpress'); ?></label>
									            <div class="toggle <?php echo esc_attr($all_theme_class); ?>">
									                <input type="checkbox" class="checkedAlltheme" name="dufw-theme-all-disable" <?php echo esc_attr($all_theme_checked); ?> value="<?php echo esc_attr('yes'); ?>" id="<?php esc_html_e('dufw-check-all-themes','disable-updates-for-wordpress'); ?>">
									                <span class="slider"></span>
									                <span class="label"><?php echo esc_attr($all_theme_status); ?></span>
									            </div>
							        		</div>
								        	<?php
								        	foreach ($wp_get_themes as $key => $get_themes) 
								        	{
								        		//$stylesheet = $get_themes->get('stylesheet');
								        		$theme_name = $get_themes->get('Name');
								        		$theme_td = $get_themes->get('TextDomain');
								        		$themename = isset($theme_name) ? $theme_name : '';
								        		$themetd = isset($theme_td) ? 'dufw-'.$theme_td : '';
								        		$theme_checked = in_array($key , $dufw_themes) ? 'checked' : '';
								        		$theme_status = in_array($key , $dufw_themes) ? 'On' : 'Off';
								        		$theme_status_class = in_array($key , $dufw_themes) ? 'on' : '';
										        ?>
								        		<div class="input-row">
										            <label for="<?php echo esc_html($themetd); ?>"><?php echo esc_html($themename); ?></label>
										            <div class="toggle <?php echo esc_attr($theme_status_class); ?>">
										                <input type="checkbox" class="checkSingletheme" name="dufw-theme-disable[]" <?php echo esc_attr($theme_checked); ?> value="<?php echo esc_attr($key); ?>" id="<?php echo esc_html($themetd); ?>">
										                <span class="slider"></span>
										                <span class="label"><?php echo esc_attr($theme_status); ?></span>
										            </div>
								        		</div>
										        <?php 
								           	} 
									    } 
									    ?>
									</div>
							    </main>
							    <main class="dufw-card">
							        <h3><?php esc_html_e('Disable Updates for WordPress Core','disable-updates-for-wordpress'); ?></h3>
							        <div class="dufw-wp-wrap">
							        	<?php 
							        	$dufw_wp_checked = $dufw_wp == 'yes' ? 'checked' : '';
							        	$dufw_wp_status = $dufw_wp == 'yes' ? 'On' : 'Off';
							        	$dufw_wp_class = $dufw_wp == 'yes' ? 'on' : '';
							        	?>
							        	<div class="input-row">
									        <label class="dufw-check-wp" for="dufw-check-wp"><?php esc_html_e('Wordpress Core Update','disable-updates-for-wordpress'); ?></label>
								            <div class="toggle <?php echo esc_attr($dufw_wp_class); ?>">
								                <input type="checkbox" class="" name="dufw-wp-update-disable" <?php echo esc_attr($dufw_wp_checked); ?> value="<?php echo esc_attr('yes'); ?>" id="<?php esc_html_e('dufw-check-wp','disable-updates-for-wordpress'); ?>">
								                <span class="slider"></span>
								                <span class="label"><?php echo esc_attr($dufw_wp_status); ?></span>
								            </div>
						        		</div>
									</div>
									<h3><?php esc_html_e('Disable All Automatic Updates','disable-updates-for-wordpress'); ?></h3>
							        <div class="dufw-wp-wrap">
							        	<?php 
							        	$dufw_auto_checked = $dufw_auto_wp == 'yes' ? 'checked' : '';
							        	$dufw_auto_status = $dufw_auto_wp == 'yes' ? 'On' : 'Off';
							        	$dufw_auto_class = $dufw_auto_wp == 'yes' ? 'on' : '';
							        	?>
							        	<div class="input-row">
									        <label class="dufw-auto-wp" for="dufw-autp-wp"><?php esc_html_e('Disable All Auto Updates','disable-updates-for-wordpress'); ?></label>
								            <div class="toggle <?php echo esc_attr($dufw_auto_class); ?>">
								                <input type="checkbox" class="" name="dufw-all-auto-update-disable" <?php echo esc_attr($dufw_auto_checked); ?> value="<?php echo esc_attr('yes'); ?>" id="<?php esc_html_e('dufw-check-wp','disable-updates-for-wordpress'); ?>">
								                <span class="slider"></span>
								                <span class="label"><?php echo esc_attr($dufw_auto_status); ?></span>
								            </div>
						        		</div>
									</div>
							    </main>
							</div>
						</div>
					    <div class="wpct-save-button-wrap">
						    <?php wp_nonce_field( 'dufw_action_nonce', 'dufw_nonce' ); ?>
		                    <?php submit_button( 'Save Settings', 'primary', 'disable-updates-for-wp-form-settings'  ); ?>
		                </div>
					</form>
				</div>
			</div>
		</div>
		<?php
	}
}
