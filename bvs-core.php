<?php

/* Register BVS themes contained within the bp-theme folder */
if ( function_exists( 'register_theme_directory') )
	register_theme_directory( WP_PLUGIN_DIR . '/bvs-site/bvs-themes' );


function bvs_core_add_admin_menu() {
	/* Add the administration tab under the "Site Admin" tab for site administrators */
	bvs_core_add_admin_menu_page( array(
		'menu_title' => __( 'BVS Site', 'bvs-site' ),
		'page_title' => __( 'BVS Site', 'bvs-site' ),
		'access_level' => 10, 'file' => 'bvs-general-settings',
		'function' => 'bvs_core_admin_settings',
		'position' => 2
	) );

	add_submenu_page( 'bvs-general-settings', __( 'General Settings', 'bvs-site'), __( 'General Settings', 'bvs-site' ), 'manage_options', 'bvs-general-settings', 'bvs_core_admin_settings' );
	add_submenu_page( 'bvs-general-settings', __( 'Component Setup', 'bvs-site'), __( 'Component Setup', 'bvs-site' ), 'manage_options', 'bvs-component-setup', 'bvs_core_admin_component_setup' );
}

add_action( 'admin_menu', 'bvs_core_add_admin_menu' );


/**
 * bvs_core_add_admin_menu_page()
 *
 * A better version of add_admin_menu_page() that allows positioning of menus.
 */
function bvs_core_add_admin_menu_page( $args = '' ) {
	global $menu, $admin_page_hooks, $_registered_pages;

	$defaults = array(
		'page_title' => '',
		'menu_title' => '',
		'access_level' => 2,
		'file' => false,
		'function' => false,
		'icon_url' => false,
		'position' => 100
	);

	$r = wp_parse_args( $args, $defaults );
	extract( $r, EXTR_SKIP );

	$file = plugin_basename( $file );

	$admin_page_hooks[$file] = sanitize_title( $menu_title );

	$hookname = get_plugin_page_hookname( $file, '' );
	if (!empty ( $function ) && !empty ( $hookname ))
		add_action( $hookname, $function );

	if ( empty($icon_url) )
		$icon_url = 'images/generic.png';
	elseif ( is_ssl() && 0 === strpos($icon_url, 'http://') )
		$icon_url = 'https://' . substr($icon_url, 7);

	do {
		$position++;
	} while ( !empty( $menu[$position] ) );

	$menu[$position] = array ( $menu_title, $access_level, $file, $page_title, 'menu-top ' . $hookname, $hookname, $icon_url );

	$_registered_pages[$hookname] = true;

	return $hookname;
}

?>
