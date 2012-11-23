<?php
	
	/*
	Plugin Name: akibox admin
	Plugin URI: http://akibox.com
	Description: admin options by akiprod.
	Version: 0.9.3
	Date: 2012-10-30
	Author: ced - aki production
	Author URI: http://www.akiproduction.com
	
	http://wordpress.stackexchange.com/questions/1567/best-collection-of-code-for-your-functions-php-file
	*/
	
	
	/* —————————————————————————————————————————————————————————————————————————————————————
	 * 	DISABLE plugin desactivation
	 * ————————————————————————————————————————————————————————————————————————————————————— */
	
		if (!function_exists('lock_plugins')):
				function lock_plugins($actions, $plugin_file, $plugin_data, $context) {
						// Remove edit link for all
						if (array_key_exists('edit', $actions))
								unset($actions['edit']);
						// Remove deactivate link for crucial plugins
						if (array_key_exists('deactivate', $actions) && in_array($plugin_file, array(
								'akibox-admin/akibox-admin.php'
						)))
								unset($actions['deactivate']);
						return $actions;
				}
				
				add_filter('plugin_action_links', 'lock_plugins', 10, 4);
		endif; // lock_plugins
	
	
	/* —————————————————————————————————————————————————————————————————————————————————————
	 *
	 *
	 * 	LOGIN
	 *
	 *
	 * ————————————————————————————————————————————————————————————————————————————————————— */
	 
	 
	/* —————————————————————————————————————————————————————————————————————————————————————
	 * 	CUSTOM LOGIN
	 * ————————————————————————————————————————————————————————————————————————————————————— */
	
		if (!function_exists('custom_login')):
				function custom_login() {
						echo '<link rel="stylesheet" type="text/css" href="' . plugins_url('/css/login.css"/>', __FILE__);
				}
				
				//change le lien vers lequel pointe le logo
				function the_site_url($url) {
						return get_bloginfo('url');
				}
				
				//change l'intitulé de l'infobulle
				function the_site_name($name) {
						return get_bloginfo('name');
				}
				
				add_filter('login_headerurl', 'the_site_url');
				add_filter('login_headertitle', 'the_site_name');
				
				add_action('login_head', 'custom_login');
		endif; // custom_login


	/* —————————————————————————————————————————————————————————————————————————————————————
	 * 	REDIRECT LOGOUT to Homepage
	 * ————————————————————————————————————————————————————————————————————————————————————— */


		if (!function_exists('logout_home')):
	 
			function logout_home($logouturl, $redir) {
				$redir = get_option('siteurl');
				return $logouturl . '&amp;redirect_to=' . urlencode($redir);
				}
	
				add_filter('logout_url', 'logout_home', 10, 2);
		endif; // logout_home
	
		

	/* —————————————————————————————————————————————————————————————————————————————————————
	 *
	 *
	 * 	PAGES / POSTS
	 *
	 *
	 * ————————————————————————————————————————————————————————————————————————————————————— */
	 
	 	
	/* —————————————————————————————————————————————————————————————————————————————————————
	 * 	ADD THUMBNAILS IN POSTS/PAGES LIST
	 * ————————————————————————————————————————————————————————————————————————————————————— */
		
		if (!function_exists('AddThumbColumn') && function_exists('add_theme_support')):
				function AddThumbColumn($cols) {
						$cols['thumbnail'] = __('Featured Image');
						return $cols;
				}
				
				function AddThumbValue($column_name, $post_id) {
						$width  = (int) 60;
						$height = (int) 60;
						if ('thumbnail' == $column_name) {
								// thumbnail of WP 2.9
								$thumbnail_id = get_post_meta($post_id, '_thumbnail_id', true);
								// image from gallery
								$attachments  = get_children(array(
										'post_parent' => $post_id,
										'post_type' => 'attachment',
										'post_mime_type' => 'image'
								));
								if ($thumbnail_id)
										$thumb = wp_get_attachment_image($thumbnail_id, array(
												$width,
												$height
										), true);
								elseif ($attachments) {
										foreach ($attachments as $attachment_id => $attachment) {
												$thumb = wp_get_attachment_image($attachment_id, array(
														$width,
														$height
												), true);
										}
								}
								if (isset($thumb) && $thumb) {
										echo $thumb;
								} else {
										echo __('None');
								}
						}
				}
				
				// for posts
				add_filter('manage_posts_columns', 'AddThumbColumn');
				add_action('manage_posts_custom_column', 'AddThumbValue', 10, 2);
				// for pages
				add_filter('manage_pages_columns', 'AddThumbColumn');
				add_action('manage_pages_custom_column', 'AddThumbValue', 10, 2);
				
				// for post and page
				add_theme_support('post-thumbnails', array(
						'post',
						'page'
				));
		endif; // custom_login
	

	/* —————————————————————————————————————————————————————————————————————————————————————
	 * 	COLORED POST STATUS
	 * ————————————————————————————————————————————————————————————————————————————————————— */
	
		if (!function_exists('color_code_post_status')):
				function color_code_post_status() {
						?>
						<style>
						.status-draft{background: #FFFF99 !important;}
						.status-pending{background: #87C5D6 !important;}
						.status-publish{/* no background - keep alternating rows */}
						.status-future{background: #CCFF99 !important;}
						.status-private{background:#FFCC99;}
						</style>
						<?php
						
				}
				
				add_action('admin_head', 'color_code_post_status');
		endif; // color_code_post_status
	
	
	/* —————————————————————————————————————————————————————————————————————————————————————
	 *
	 *
	 * 	ADMIN
	 *
	 *
	 * ————————————————————————————————————————————————————————————————————————————————————— */
	 
	 	
	/* —————————————————————————————————————————————————————————————————————————————————————
	 * 	CHANGE ADMIN FOOTER
	 * ————————————————————————————————————————————————————————————————————————————————————— */
	
		if (!function_exists('remove_footer_admin')):
				function remove_footer_admin() {
						echo "Powered by <a href='http://www.akiproduction.com'>aki production</a> | <a href='http://akibox.com'>akibox</a>";
				}
				
				add_filter('admin_footer_text', 'remove_footer_admin');
		endif; // remove_footer_admin
		
	
	/* —————————————————————————————————————————————————————————————————————————————————————
	 * 	REMOVE ADMIN MENU ITEMS based on username  
	 * ————————————————————————————————————————————————————————————————————————————————————— */
	
		if (!function_exists('remove_admin_menu')):
				function remove_admin_menu() {
						global $current_user;
						get_currentuserinfo();
						
						if (($current_user->user_login != 'xavieradmin') && ($current_user->user_login != 'cedadmin')) {
								//remove_menu_page('index.php'); // Dashboard
								remove_submenu_page('index.php', 'index.php');
								remove_submenu_page('index.php', 'update-core.php');
								//remove_menu_page('edit.php'); // Posts
								//remove_menu_page('upload.php'); // Media
								remove_menu_page('link-manager.php'); // Links
								//remove_menu_page('edit.php?post_type=page'); // Pages
								remove_menu_page('edit-comments.php'); // Comments
								remove_menu_page('themes.php'); // Appearance
								remove_submenu_page( 'themes.php', 'widgets.php' ); // Widgets
								remove_submenu_page('themes.php', 'theme-editor.php'); // Theme editor
								remove_menu_page('plugins.php'); // Plugins
								remove_menu_page('users.php'); // Users
								remove_menu_page('profile.php');
								remove_menu_page('tools.php'); // Tools
								remove_menu_page('options-general.php'); // Settings
								remove_menu_page('edit.php?post_type=acf'); //ACF
								
						} //end if
				}
				
				// end function
				
				add_action('admin_init', 'remove_admin_menu');
		endif; // remove_admin_menu
	
	
	/* —————————————————————————————————————————————————————————————————————————————————————
	 * 	REMOVE ADMIN BAR ITEMS
	 * ————————————————————————————————————————————————————————————————————————————————————— */
		
		if (!function_exists('remove_adminbar_items')):
				function remove_adminbar_items($wp_admin_bar) {
						$wp_admin_bar->remove_node('wp-logo');
						$wp_admin_bar->remove_node('comments');
						$wp_admin_bar->remove_node('new-link');
						$wp_admin_bar->remove_node('new-media');
						$wp_admin_bar->remove_node('new-user');
						$wp_admin_bar->remove_node('background');
						$wp_admin_bar->remove_node('themes');
						$wp_admin_bar->remove_node('customize');
						$wp_admin_bar->remove_node('menus');
						$wp_admin_bar->remove_node('widgets');
						$wp_admin_bar->remove_node('header');
						$wp_admin_bar->remove_node('edit-profile');
						
						//use the text to the left of li id="wp-admin-bar-###" 
				}
				
				add_action('admin_bar_menu', 'remove_adminbar_items', 999);
		endif; // remove_adminbar_items
	
	
	/* —————————————————————————————————————————————————————————————————————————————————————
	 * 	MOVE ADMIN BAR TO BOTTOM OF PAGE
	 * ————————————————————————————————————————————————————————————————————————————————————— */
	
		if (!function_exists('move_admin_bar_bottom')):
				function move_admin_bar_bottom() {
						if (is_user_logged_in()) {
								echo "
								<style type='text/css'>
								* html body { margin-top: 0 !important; }
								body.admin-bar { margin-top: -28px; padding-bottom: 28px; }
								body.wp-admin #footer { padding-bottom: 28px; }
								#wpadminbar { top: auto !important; bottom: 0; }
								#wpadminbar .quicklinks .ab-sub-wrapper { bottom: 28px; }
								#wpadminbar .quicklinks .ab-sub-wrapper ul .ab-sub-wrapper { bottom: -7px; }
								</style>
								";
						}
				}
				
				add_action('wp_head', 'move_admin_bar_bottom');
		endif; // move_admin_bar_bottom
	
	

	

	
	/* —————————————————————————————————————————————————————————————————————————————————————
	 *
	 *
	 * 	THEME
	 *
	 *
	 * ————————————————————————————————————————————————————————————————————————————————————— */
	
	
	/* —————————————————————————————————————————————————————————————————————————————————————
	 * 	REMOVE WP GENERATOR
	 * ————————————————————————————————————————————————————————————————————————————————————— */
	
		if (!function_exists('remove_wp_version')):
				function remove_wp_version() {
						return ''; //returns nothing, exactly the point.
				}
				
				add_filter('the_generator', 'remove_wp_version');
		endif; // remove_wp_version
	
	
	/* —————————————————————————————————————————————————————————————————————————————————————
	 * 	GALLERY with No Image Links
	 * ————————————————————————————————————————————————————————————————————————————————————— */
	
		if (!function_exists('new_gallery_shortcode')):
				function new_gallery_shortcode($attr) {
						global $post, $wp_locale;
						
						$output = gallery_shortcode($attr);
						
						// remove link
						if ($attr['link'] == "none") {
								$output = preg_replace(array(
										'/<a[^>]*>/',
										'/<\/a>/'
								), '', $output);
						}
						
						return $output;
				}
				
				add_shortcode('gallery', 'new_gallery_shortcode');
		endif; // new_gallery_shortcode
	
	
	/* —————————————————————————————————————————————————————————————————————————————————————
	 * 	ADD MULTI GALLERIES
	 * —————————————————————————————————————————————————————————————————————————————————————*/
		
		if (!function_exists('add_multiple_galleries')):
				function add_multiple_galleries($where) {
						if ($where == 'media-upload-popup')
								wp_enqueue_script('akibox-admin', plugins_url('akibox-admin/js/multiple-galleries.js'), array(
										'jquery',
										'media-upload',
										'utils',
										'admin-gallery'
								));
				}
				
				add_action('admin_enqueue_scripts', 'add_multiple_galleries');
		endif; // add_multiple_galleries
		
	
	
	
	
	/* —————————————————————————————————————————————————————————————————————————————————————
	 *
	 *
	 * 	OPTIONS A ADAPTER
	 *
	 *
	 * ————————————————————————————————————————————————————————————————————————————————————— */

	
	/* —————————————————————————————————————————————————————————————————————————————————————
	 * 	REMOVE METABOXES FROM POST / PAGES
	 * ————————————————————————————————————————————————————————————————————————————————————— */
	 
		if (!function_exists('remove_meta_boxes')):
			function remove_meta_boxes() {
				
				//POSTS
				remove_meta_box( 'submitdiv', 'post', 'normal' ); // Publish meta box
				remove_meta_box( 'commentsdiv', 'post', 'normal' ); // Comments meta box
				remove_meta_box( 'revisionsdiv', 'post', 'normal' ); // Revisions meta box
				remove_meta_box( 'authordiv', 'post', 'normal' ); // Author meta box
				remove_meta_box( 'slugdiv', 'post', 'normal' );	// Slug meta box
				remove_meta_box( 'tagsdiv-post_tag', 'post', 'side' ); // Tags meta box
				remove_meta_box( 'categorydiv', 'post', 'side' ); // Category meta box
				remove_meta_box( 'postexcerpt', 'post', 'normal' ); // Excerpt meta box
				remove_meta_box( 'formatdiv', 'post', 'normal' ); // Format meta box
				remove_meta_box( 'trackbacksdiv', 'post', 'normal' ); // Trackbacks meta box
				remove_meta_box( 'postcustom', 'post', 'normal' ); // Custom fields meta box
				remove_meta_box( 'commentstatusdiv', 'post', 'normal' ); // Comment status meta box
				remove_meta_box( 'postimagediv', 'post', 'side' ); // Featured image meta box
				
				// PAGES
				remove_meta_box( 'pageparentdiv', 'page', 'side' ); // Attributes meta box
				remove_meta_box( 'postcustom', 'page', 'normal'); // Custom fields meta box
				remove_meta_box( 'commentstatusdiv', 'page', 'normal'); // Comment status meta box
				remove_meta_box( 'trackbacksdiv', 'page', 'normal'); // Trackbacks meta box
				remove_meta_box( 'commentsdiv', 'page', 'normal'); // Comments meta box
				remove_meta_box( 'slugdiv', 'page', 'normal'); // Slug meta box
				remove_meta_box( 'authordiv', 'page', 'normal'); // Author meta box
							
			}
		add_action( 'admin_menu', 'remove_meta_boxes' );
		endif; // remove_meta_boxes

	
	/* —————————————————————————————————————————————————————————————————————————————————————
	 * 	REMOVE DASHBOARD WIDGETS
	 * ————————————————————————————————————————————————————————————————————————————————————— */
	
		if (!function_exists('remove_dashboard_widgets')):
			function remove_dashboard_widgets() {
					remove_meta_box('dashboard_right_now', 'dashboard', 'normal');   // right now
					remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal'); // recent comments
					remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal'); // incoming links
					remove_meta_box('dashboard_plugins', 'dashboard', 'normal'); // plugins
					remove_meta_box('dashboard_quick_press', 'dashboard', 'normal'); // quick press
					remove_meta_box('dashboard_recent_drafts', 'dashboard', 'normal'); // recent drafts
					remove_meta_box('dashboard_primary', 'dashboard', 'normal'); // wordpress blog
					remove_meta_box('dashboard_secondary', 'dashboard', 'normal'); // other wordpress news
			}
				
				add_action('admin_init', 'remove_dashboard_widgets');
		endif; // remove_dashboard_widgets	
	
	
	/* —————————————————————————————————————————————————————————————————————————————————————
	 * 	RENAME ADMIN MENU ITEMS
	 * ————————————————————————————————————————————————————————————————————————————————————— */
	
		if (!function_exists('rename_admin_menu_items')):
				function rename_admin_menu_items($menu) {
						// $menu = str_ireplace( 'original name', 'new name', $menu );
						$menu = str_ireplace('WooCommerce', 'Shop', $menu);
						// $menu = str_ireplace( 'Tableau de bord', 'Home', $menu );
						// return $menu array
						return $menu;
				}
				
				add_filter('gettext', 'rename_admin_menu_items');
				add_filter('ngettext', 'rename_admin_menu_items');
		endif; // rename_admin_menu_items
	
	
	/* —————————————————————————————————————————————————————————————————————————————————————
	 * 	CHANGE DEFAULT FROM EMAIL
	 * ————————————————————————————————————————————————————————————————————————————————————— */
		
		if (!function_exists('new_mail_from')):
				function new_mail_from($old) {
						return 'cedric@akiprod.com';
				}
				
				function new_mail_from_name($old) {
						return 'Webmaster';
				}
				
				add_filter('wp_mail_from', 'new_mail_from');
				add_filter('wp_mail_from_name', 'new_mail_from_name');
		endif; // new_mail_from

	
	/* —————————————————————————————————————————————————————————————————————————————————————
	 * 	LOAD SCRIPT ONLY ON REQUIRED PAGE
	 * ————————————————————————————————————————————————————————————————————————————————————— */
	 
		if (!function_exists('deregister_javascript')):
		
			function deregister_javascript() {
	  			if ( !is_page('Contact') ) {
					wp_deregister_script( 'contact-form-7' );
	  			}
				if ( !is_page('Portfolio') ) {
					wp_deregister_script( 'wp-jquery-lightbox');
				}
				if ( !is_single() ) {
					wp_deregister_script( 'comment-reply');
				}
			}
				add_action( 'wp_print_scripts', 'deregister_javascript', 100 );
		endif; // deregister_javascript
	
	
	
	
	
	/* —————————————————————————————————————————————————————————————————————————————————————
	 * 	UNREGISTER ALL DEFAULT WP WIDGETS
	 * ————————————————————————————————————————————————————————————————————————————————————— */	
	 
		 if (!function_exists('unregister_widgets')):
		
			function unregister_widgets() {
			    unregister_widget('WP_Widget_Pages');
			    unregister_widget('WP_Widget_Calendar');
			    unregister_widget('WP_Widget_Archives');
			    unregister_widget('WP_Widget_Links');
			    unregister_widget('WP_Widget_Meta');
			    unregister_widget('WP_Widget_Search');
			    unregister_widget('WP_Widget_Text');
			    unregister_widget('WP_Widget_Categories');
			    unregister_widget('WP_Widget_Recent_Posts');
			    unregister_widget('WP_Widget_Recent_Comments');
			    unregister_widget('WP_Widget_RSS');
			    unregister_widget('WP_Widget_Tag_Cloud');
			}
			add_action('widgets_init', 'unregister_widgets', 1);
		endif; // unregister_widgets

	






	/* —————————————————————————————————————————————————————————————————————————————————————
	 *
	 *
	 * 	HELP
	 *
	 *
	 * ————————————————————————————————————————————————————————————————————————————————————— */
	 
	  /* —————————————————————————————————————————————————————————————————————————————————————
	  * HELP MENU
	  * ————————————————————————————————————————————————————————————————————————————————————— */
	 

	// aki_add_pages() is the sink function for the 'admin_menu' hook
	function aki_add_pages() {
	// Add a new top-level menu:
	// The first parameter is the Page name(Site Help), second is the Menu name(Help) 
	//and the number(5) is the user level that gets access
	add_menu_page('Site Help', 'Question / Bug ?', 'publish_posts', __FILE__, 'aki_toplevel_page');
	}

	// aki_toplevel_page() displays the page content for the custom Test Toplevel menu
	function aki_toplevel_page() {
	echo '
	<div class="wrap">
		<h2>Contactez-nous</h2>
		<br />
		<h4>aki production</h4>
		Cédric Piccino<br />
		email: <a href="mailto:cedric@akiprod.com?subject=Contact from ';
	echo(bloginfo('name')); // contains echos
	echo '">cedric@akiprod.com</a><br />
		mobile: +33 6 80 60 86 14
		<br /><br />
		<a href="http://www.akiproduction.com">akiproduction.com</a><br />
		<a href="http://akibox.com">akibox.com</a>
    </div>
	';
	}

	// Insert the aki_add_pages() sink into the plugin hook list for 'admin_menu'
	add_action('admin_menu', 'aki_add_pages');
	
	
	


// Function that output's the contents of the dashboard widget
function dashboard_widget_function() {
		echo '<a href="http://akibox.com/"><img align="right" border="0" src="' . plugins_url( '/images/logo-aki-prod.png"/>', __FILE__ ). ' </a> ';
	echo "<a href='http://akibox.com/'><img align='right' border='0' src='" . plugins_url( "images/logo-aki-prod.png" , __FILE__ ). "' ></a> ";
	
	
	echo "<b>Cédric Piccino</b><br />email: <a href='mailto:cedric@akiprod.com?subject=Contact from ";
	echo(bloginfo('name')); // contains echos
	echo "'>cedric@akiprod.com</a><br />
		mobile: +33 6 80 60 86 14
		<br /><br />
		> <a href='http://www.akiproduction.com'>akiproduction.com</a><br />
		> <a href='http://akibox.com'>akibox.com</a>";
	} 

 
// Function that beeng used in the action hook
function add_dashboard_widgets() {
	wp_add_dashboard_widget('dashboard_widget', 'Example Dashboard Widget', 'dashboard_widget_function');
}
 
// Register the new dashboard widget into the 'wp_dashboard_setup' action
add_action('wp_dashboard_setup', 'add_dashboard_widgets' );









// supprimer les notifications de plugins
remove_action( 'load-update-core.php', 'wp_update_plugins' );
add_filter( 'pre_site_transient_update_plugins', create_function( '$a', "return null;" ) );

// supprimer les notifications de thèmes
remove_action( 'load-update-core.php', 'wp_update_themes' );
add_filter( 'pre_site_transient_update_themes', create_function( '$a', "return null;" ) );

// supprimer les notifications du core
add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) );
	
	/* —————————————————————————————————————————————————————————————————————————————————————
	 *
	 *
	 * 	DEBUG
	 *
	 *
	 * ————————————————————————————————————————————————————————————————————————————————————— */
	
		function aki_PHPErrorsWidget() {
		/* What is my absolute path?
		<?php
		$path = getcwd();
		echo "Your Absoluthe Path is: ";
		echo $path;
		?>
		*/
				$logfile            = '/var/www/vhosts/akiprod.com/httpdocs/sandbox/wp-content/debug.log'; // Enter the server path to your logs file here

				$displayErrorsLimit = 100; // The maximum number of errors to display in the widget
				$errorLengthLimit   = 600; // The maximum number of characters to display for each error
				$fileCleared        = false;
				$userCanClearLog    = current_user_can('manage_options');
				// Clear file?
				if ($userCanClearLog && isset($_GET["aki-php-errors"]) && $_GET["aki-php-errors"] == "clear") {
						$handle = fopen($logfile, "w");
						fclose($handle);
						$fileCleared = true;
				}
				// Read file
				if (file_exists($logfile)) {
						$errors = file($logfile);
						$errors = array_reverse($errors);
						if ($fileCleared)
								echo '<p><em>File cleared.</em></p>';
						if ($errors) {
								echo '<p>' . count($errors) . ' error';
								if ($errors != 1)
										echo 's';
								echo '.';
								if ($userCanClearLog)
										echo ' [ <b><a href="' . get_admin_url() . '?aki-php-errors=clear" onclick="return confirm(\'Are you sure?\');">CLEAR LOG FILE</a></b> ]';
								echo '</p>';
								echo '<div id="aki-php-errors" style="height:250px;overflow:scroll;padding:2px;background-color:#faf9f7;border:1px solid #ccc;">';
								echo '<ol style="padding:0;margin:0;">';
								$i = 0;
								foreach ($errors as $error) {
										echo '<li style="padding:2px 4px 6px;border-bottom:1px solid #ececec;">';
										$errorOutput = preg_replace('/\[([^\]]+)\]/', '<b>[$1]</b>', $error, 1);
										if (strlen($errorOutput) > $errorLengthLimit) {
												echo substr($errorOutput, 0, $errorLengthLimit) . ' [...]';
										} else {
												echo $errorOutput;
										}
										echo '</li>';
										$i++;
										if ($i > $displayErrorsLimit) {
												echo '<li style="padding:2px;border-bottom:2px solid #ccc;"><em>More than ' . $displayErrorsLimit . ' errors in log...</em></li>';
												break;
										}
								}
								echo '</ol></div>';
						} else {
								echo '<p>No errors currently logged.</p>';
						}
				} else {
						echo '<p><em>There was a problem reading the error log file.</em></p>';
				}
		}
		
		// Add widgets
		function aki_dashboardWidgets() {
				global $current_user;
				get_currentuserinfo();
				// only for xav et ced
				if (($current_user->user_login == 'xavieradmin') || ($current_user->user_login == 'cedadmin')) {
						wp_add_dashboard_widget('aki-php-errors', 'PHP errors', 'aki_PHPErrorsWidget');
				} //only for xav et ced
		}
		
		add_action('wp_dashboard_setup', 'aki_dashboardWidgets');
?>