=== akibox admin ===
Contributors: ced, xav
Tags: options, plug
Requires at least: 3.0
Tested up to: 3.3
Stable tag: 0.1


== Description ==
http://wp.smashingmagazine.com/2011/03/08/ten-things-every-wordpress-plugin-developer-should-know/
Description ici…; bla bla


== Installation ==

Upload the akibox-admin folder plugin to your blog, Activate it.

1, 2, 3: You're done!

== Changelog ==

= 0.2 =

* more...

= 0.1 =

* First options
* à suivre



== Notes ==
// The path to the theme directory
if (!defined('MYPLUGIN_THEME_DIR'))
    define('MYPLUGIN_THEME_DIR', ABSPATH . 'wp-content/themes/' . get_template());
// The name of the plugin
if (!defined('MYPLUGIN_PLUGIN_NAME'))
    define('MYPLUGIN_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));
// The path to the plugin directory
if (!defined('MYPLUGIN_PLUGIN_DIR'))
    define('MYPLUGIN_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . MYPLUGIN_PLUGIN_NAME);
// The url of the plugin
if (!defined('MYPLUGIN_PLUGIN_URL'))
    define('MYPLUGIN_PLUGIN_URL', WP_PLUGIN_URL . '/' . MYPLUGIN_PLUGIN_NAME);
    
//Having these global paths defined lets me write the code below in my plugin anywhere I need to, and I know it will resolve correctly for any website that uses the plugin:   
$image = MYPLUGIN_PLUGIN_URL . '/images/my-image.jpg';
$style = MYPLUGIN_PLUGIN_URL . '/css/my-style.css';
$script = MYPLUGIN_PLUGIN_URL . '/js/my-script.js';
