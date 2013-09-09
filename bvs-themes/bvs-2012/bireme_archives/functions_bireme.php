<?php
/**
 *
 * DEfinições específicas para BIREME
 *
 */
 /* Load up our theme options page and related code. */
$current_language = strtolower(get_bloginfo('language'));

load_plugin_textdomain( 'vhl', false,  BVS_PLUGIN_DIR . '/languages' );

if ( is_admin() ) require_once( TEMPLATEPATH . '/bireme_archives/admin_settings.php' );

$settings = get_option( "wp_bvs_theme_settings" );
$layout = $settings['layout'];
$total_columns = $layout['total'];
$top_sidebar = $layout['top-sidebar'];
$footer_sidebar = $layout['footer-sidebar'];

// sidebars do template
register_sidebar( array(
    'name' => __('Header','vhl'),
    'id' => 'header_' . $current_language,
    'description' => '',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<strong class="widget-title">',
    'after_title' => '</strong>',
) );

//SideBar Auxiliar Top só aparece se ativado
if ($top_sidebar == true){
    register_sidebar( array(
        'name' => __('Top Auxiliary SideBar','vhl'),
        'id' => 'top_sidebar_' . $current_language,
        'description' => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<strong class="widget-title">',
        'after_title' => '</strong>',
    ) );
}

// gerando as sidebars dinamicamente
for($i=1; $i <= $total_columns; $i++) {

    register_sidebar( array(
        'name' => __('Column', 'vhl') . ' ' . $i,
        'id' => 'column-' . $i,'_' . $current_language,
        'description' => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<strong class="widget-title">',
        'after_title' => '</strong>',
    ) );

}
//SideBar Auxiliar Footer só aparece se ativado
if ($footer_sidebar == true){
    register_sidebar( array(
        'name' => __('Footer Auxiliary SideBar','vhl'),
        'id' => 'footer_sidebar_' . $current_language,
        'description' => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<strong class="widget-title">',
        'after_title' => '</strong>',
    ) );
}

register_sidebar( array(
    'name' => __('Footer','vhl'),
    'id' => 'footer_' . $current_language,
    'description' => '',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget' => '</aside>',
    'before_title' => '<strong class="widget-title">',
    'after_title' => '</strong>',
) );

register_sidebar( array(
    'name' => __('Level 2','vhl'),
    'id' => 'level2_' . $current_language,
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget' => '</aside>',
    'before_title' => '<strong class="widget-title">',
    'after_title' => '</strong>',
) );

$custom_header_file = TEMPLATEPATH . '/bireme_archives/custom/custom-header.php';

if(file_exists($custom_header_file)) {
    add_action('wp_head', 'add_custom_header');
}

function add_custom_header(){
    include_once(TEMPLATEPATH . '/bireme_archives/custom/custom-header.php');
}

$custom_include_file = TEMPLATEPATH . '/bireme_archives/custom/include.php';
if(file_exists($custom_include_file)) {
    require_once($custom_include_file);
}

add_filter('widget_text', 'do_shortcode');
?>
