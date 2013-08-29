<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

$mlf_options = get_option('mlf_config');

$current_language = get_bloginfo('language');
$site_lang = substr($current_language, 0,2);

?>

<!DOCTYPE html>
	<!--[if IE 7]>
	<html class="ie ie7" <?php language_attributes(); ?>>
	<![endif]-->
	<!--[if IE 8]>
	<html class="ie ie8" <?php language_attributes(); ?>>
	<![endif]-->
	<!--[if !(IE 7) | !(IE 8)  ]><!-->
	<html <?php language_attributes(); ?>>
	<!--<![endif]-->
	
	<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width" />
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	
	<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
	<![endif]-->
	
	<noscript>Your browser does not support JavaScript!</noscript>
	
	<!-- extract the admin configs -->
	<?php include "bireme_archives/admin_configs.php"; ?>

	<!-- wp_head -->
	<?php wp_head(); ?>

	<!-- block extrahead -->
	<?= stripslashes( $header['extrahead'] ) ?>
	
	</head>

	<body <?php body_class(); ?>>

	<div class="container <?php echo $total_columns;?>_columns">
		<div class="header">
			<div class="bar">
				<div id="otherVersions">
					<?php if(function_exists('mlf_links_to_languages')) { mlf_links_to_languages(); } ?>	
				</div>
				<div id="contact"> 
					<span><a href="/<?php echo ( $site_lang ); ?>/contact/">Contato</a></span>
				</div>
			</div>
	        <div class="top top_<?php echo ($current_language);?>">
	            <div id="parent">
	            	<a href="http://regional.bvsalud.org/php/index.php?lang=<?php echo ( $site_lang ) ?>" alt="Portal Regional da BVS">
		                <img src="<?php echo $logo;?>" alt="BVS LOGO"/>
	        		</a>
	            </div>
	           	<?php if ($title == true) {	?>
		            <div class="site_name">
						<h1><a title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" href="<?php echo esc_url( home_url( '/'.( $site_lang ) ) ); ?>"><span><?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?></span></a></h1>            
		            </div>
				<?php } ?>
				<div class="headerWidget">
					<?php dynamic_sidebar( 'header' ); ?>
				</div>
	        </div>
			<div class="spacer"></div>	
		</div>
