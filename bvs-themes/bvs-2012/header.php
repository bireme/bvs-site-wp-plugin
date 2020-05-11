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
$current_language = strtolower(get_bloginfo('language'));
$site_lang = substr($current_language, 0,2);

if ($current_language != '') {
    $current_language = '_' . $current_language;
}

$top = "header";

if (is_plugin_active('multi-language-framework/multi-language-framework.php')) {
    $top .= $current_language;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:<?php language_attributes(); ?> <?php language_attributes(); ?> >
<!--<![endif]-->

    <head>
    <meta http-equiv="content-type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, user-scalable=no" />
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

    <?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
    <!--[if lt IE 9]>
    <script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
    <![endif]-->
    <!-- extract the admin configs -->
    <?php include "bireme_archives/admin_configs.php"; ?>
    <!-- wp_head -->
    <?php wp_head(); ?>
    <!-- block extrahead -->
    <?= stripslashes( $header['extrahead'] ) ?>
    <!-- block extra files -->
    </head>

    <body <?php body_class(); ?>>

        <?php wp_body_open(); ?>

        <div class="container <?php echo $total_columns;?>_columns">
        <div class="header">
            <div class="bar">
                <div id="otherVersions">
                    <?php
                        if ( function_exists( 'mlf_links_to_languages' ) ) {
                            mlf_links_to_languages();
                        } elseif ( function_exists( 'pll_the_languages' ) ) {
                            $args = array(
                                'dropdown' => 0,
                                'show_names' => 1,
                                'display_names_as' => 'name',
                                'show_flags' => 0,
                                'hide_if_empty' => 1,
                                'force_home' => 0,
                                'echo' => 0,
                                'hide_if_no_translation' => 1,
                                'hide_current' => 1,
                                'post_id' => null,
                                'raw' => 0
                            );

                            echo '<ul>' . pll_the_languages( $args ) . '</ul>';
                        }
                    ?>
                </div>

                <!-- Conditional to show contact link. -->
                <?php if(is_plugin_active('contact-form-7/wp-contact-form-7.php') && isset($contactPage) && !empty($contactPage)) : ?>
                    <div id="contact">
                        <?php if ( function_exists( 'pll_get_post' ) ) : ?>
                            <span><a href="<?php echo get_permalink(pll_get_post($contactPage)); ?>"><?php echo get_the_title(pll_get_post($contactPage)); ?></a></span>
                        <?php else : ?>
                            <span><a href="<?php echo get_permalink($contactPage); ?>"><?php echo get_the_title($contactPage); ?></a></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php
                    // Conditional to show Login BVS option
                    if(isset($login_menu)){
                        if ( ! defined( 'HTTP_HOST' ) ) {
                            $path = ( $_SERVER['REDIRECT_URL'] ) ? $_SERVER['REDIRECT_URL'] : '';
                            define( 'HTTP_HOST', get_bloginfo('url').$path );
                        }

                        $current_language = strtolower(get_bloginfo('language'));
                        $lang = substr($current_language, 0,2);
                        $portal = 'https://platserv.bvsalud.org/client';

                        if ( $_COOKIE['userData'] ) :
                            $userData = json_decode(base64_decode($_COOKIE['userData']), true); ?>
                            <div id="loginMenu">
                                <p class="welcome"><?php _e('Welcome,', 'vhl'); ?> <?php echo $userData['firstName'] ?>!</p>
                                <p><a class="logout" href="<?php echo $portal.'/controller/authentication/?lang='.$lang; ?>" target="_blank"><?php _e('Dashboard', 'vhl'); ?></a> | <a class="login" href="<?php echo $portal.'/controller/logout/control/business/origin/'.base64_encode(HTTP_HOST).'/?lang='.$lang; ?>" style="color: red;"><?php _e('Logout', 'vhl'); ?></a></p>
                            </div>
                        <?php else : ?>
                            <div id="loginMenu">
                                <p><a class="logout" href="<?php echo $portal.'/controller/authentication/control/home/origin/'.base64_encode(HTTP_HOST).'/iahx/'.base64_encode($iahx).'/?lang='.$lang; ?>"><?php _e('Login to Services Platform', 'vhl'); ?></a></p>
                                <?php if ( $_REQUEST['status'] == 'access_denied' ){ ?>
                                    <p class="help-block"><?php _e('access denied', 'vhl') ?></p>
                                <?php } ?>
                                <?php if ( $_REQUEST['status'] == 'false' ){ ?>
                                    <p class="help-block"><?php _e('invalid login', 'vhl') ?></p>
                                <?php } ?>
                            </div>
                        <?php endif;
                    }
                ?>

                <?php if ($headerMenu != true) wp_nav_menu( array( 'fallback_cb' => 'false' ) ); ?>
            </div>
            <div class="top top_<?php echo ($current_language);?>">
                <?php if($logo) { ?>
                    <div id="parent">
                        <a href="<?php echo $linkLogo;?>" title="<?php echo __('Virtual Health Library','vhl');?>">
                            <img src="<?php echo $logo;?>" alt="<?php echo __('VHL Logo','vhl');?>"/>
                        </a>
                    </div>
                <?php } ?>
                   <?php if ($title == true) {    ?>
                    <div class="site_name">
                        <h1><a title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" href="<?php echo $bannerLink;?>"><span><?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?></span></a></h1>
                    </div>
                <?php } ?>
                <div class="headerWidget">
                    <?php dynamic_sidebar( $top ); ?>
                </div>
            </div>
            <div class="spacer"></div>
        </div>
