<?php
/*
Plugin Name: BVS Site
Plugin URI: http://reddes.bvsalud.org/projects/bvs-site/
Description: BVS Portal
Author: BIREME/OPAS/OMS
Version: 0.1
Author URI: http://reddes.bvsalud.org/
Site Wide Only: true
*/

define('BVS_VERSION', '0.3' );

define('BVS_PLUGIN_PATH',  plugin_dir_path(__FILE__) );
define('BVS_PLUGIN_DIR',   plugin_basename( dirname(__FILE__) ) );
define('BVS_PLUGIN_URL',   plugin_dir_url(__FILE__) );

// Load plugin files

require_once(BVS_PLUGIN_PATH . '/bvs-core/widgets.php');
require_once(BVS_PLUGIN_PATH . '/bvs-core/post_types.php');
require_once(BVS_PLUGIN_PATH . '/bvs-core/page-links-to.php');
require_once(BVS_PLUGIN_PATH . '/bvs-core/settings.php');

function vhl_init() {

    if (is_admin()) {        
        wp_enqueue_script('vhl-edit', BVS_PLUGIN_URL . 'js/scripts.js');
        wp_enqueue_style ('vhl-edit', BVS_PLUGIN_URL . 'css/styles.css');
    }  

    new VHL_PageLinksTo;

    register_theme_directory( BVS_PLUGIN_PATH . '/bvs-themes' );
}

function vhl_load_translation(){
    // Translations
    load_plugin_textdomain( 'vhl', false,  BVS_PLUGIN_DIR . '/languages' );
}

function vhl_add_admin_menu() {

    add_submenu_page( 'options-general.php', __('VHL Site Settings', 'vhl'), __('VHL Site', 'vhl'), 'manage_options', 'vhl', 
                      'vhl_page_admin');

    //call register settings function
    add_action( 'admin_init', 'vhl_register_settings' );

}

function vhl_register_settings(){
    
    register_setting('vhl-settings-group', 'vhl_config');

}

function vhl_google_analytics_code(){
    $vhl_config = get_option('vhl_config');
?>

<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?php echo $vhl_config['google_analytics_code'] ?>']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

<?php
}

add_action( 'init', 'vhl_load_translation' );
add_action( 'admin_menu', 'vhl_add_admin_menu');
add_action( 'plugins_loaded','vhl_init' );
add_action( 'wp_head', 'vhl_google_analytics_code');

?>
