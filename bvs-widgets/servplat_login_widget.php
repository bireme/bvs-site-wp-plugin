<?php

if ( ! defined( 'HTTP_HOST' ) ) {
    $path = ( $_SERVER['REDIRECT_URL'] ) ? $_SERVER['REDIRECT_URL'] : '';
    define( 'HTTP_HOST', get_bloginfo('url').$path );
}

/**
 * Services Platform Login Widget
 */
class ServPlat_Login_Widget extends WP_Widget {

    private $servplat_domain;
    private $servplat_client;
    private $servplat_server;

    function __construct() {
        $this->servplat_domain = 'https://platserv.bvsalud.org';
        $this->servplat_client = $this->servplat_domain.'/client';
        $this->servplat_server = $this->servplat_domain.'/server';

        $widget_ops = array('classname' => 'servplat-login', 'description' => __('Adds the Services Platform login on your site', 'vhl') );
        parent::__construct('servplat_login', __('Services Platform Login', 'vhl'), $widget_ops);
        add_action( 'wp_head', array(&$this, 'fix_cookie_delay'), 20, 1 );
        add_action( 'wp_enqueue_scripts', array(&$this, 'servplat_enqueue_style'), 20, 1 );
    }

    function widget($args, $instance) {
        extract($args);

        $current_language = strtolower(get_bloginfo('language'));
        // network lang parameter only accept 2 letters language code (pt, es, en)
        $lang = substr($current_language, 0,2);

        $title = $instance['title'];
        $layout = $instance['layout'];
        $iahx = ( $instance['iahx'] ) ? $instance['iahx'] : 'portal';

        if ( function_exists( 'pll_current_language' ) ) {
            $lang = pll_current_language();
            $title = pll_translate_string($instance['title'], $lang);
        }

        echo $before_widget;
            if( $title ) echo $before_title, $title, $after_title;
            ?>
            <?php if ( 'box' == $layout ) : ?>
                <?php if ( $_COOKIE['userData'] ) : ?>
                    <?php $userData = json_decode(base64_decode($_COOKIE['userData']), true); ?>
                    <div class="bootstrap-iso">
                        <div class="well box logged">
                            <?php if ( $userData['fb_data']['picture']['data']['url'] ) : ?>
                                <img src="<?php echo $userData['fb_data']['picture']['data']['url']; ?>" alt="<?php _e('avatar,', 'vhl'); ?>" class="avatar">
                            <?php elseif ( $userData['google_data']['picture'] ) : ?>
                                <img src="<?php echo $userData['google_data']['picture']; ?>" alt="<?php _e('avatar,', 'vhl'); ?>" class="avatar">
                            <?php endif; ?>
                            <span style="display: grid;">
                                <p><?php _e('Welcome,', 'vhl'); ?> <?php echo $userData['firstName'] ?></p>
                                <p><a href="<?php echo $this->servplat_client.'/controller/authentication/?lang='.$lang; ?>" target="_blank"><?php _e('Go to dashboard', 'vhl'); ?></a></p>
                                <p><a href="<?php echo $this->servplat_client.'/controller/logout/control/business/origin/'.base64_encode(HTTP_HOST).'/?lang='.$lang; ?>" style="color: red;"><?php _e('Logout', 'vhl'); ?></a></p>
                            </span>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="bootstrap-iso">
                        <div class="well box">
                            <form id="loginForm" method="POST" action="<?php echo $this->servplat_client.'/controller/authentication/origin/'.base64_encode(HTTP_HOST); ?>" novalidate="novalidate">
                                <input type="hidden" name="control" value="business" />
                                <input type="hidden" name="action" value="authentication" />
                                <input type="hidden" name="lang" value="<?php echo $lang; ?>" />
                                <input type="hidden" name="iahx" value="<?php echo base64_encode($iahx); ?>" />
                                <div class="form-group">
                                    <input type="text" class="form-control" id="userID" name="userID" maxlenght="50" placeholder="<?php _e('user', 'vhl') ?>">
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" id="userPass" name="userPass" maxlenght="15" placeholder="<?php _e('password', 'vhl') ?>">
                                    <span class="help-block"></span>
                                </div>
                                <?php if ( $_REQUEST['status'] == 'access_denied' ){ ?>
                                    <span class="help-block"><?php _e('access denied', 'vhl') ?></span>
                                <?php } ?>
                                <?php if ( $_REQUEST['status'] == 'false' ){ ?>
                                    <span class="help-block"><?php _e('invalid login', 'vhl') ?></span>
                                <?php } ?>
                                <div class="social-sharing">
                                    <div>
                                        <a href="<?php echo $this->servplat_domain.'/connector/facebook/?origin='.base64_encode(HTTP_HOST).'&iahx='.base64_encode($iahx); ?>" class="btn btn-primary">
                                            <i class="fa fa-facebook"></i>
                                            <span>Facebook</span>
                                        </a>
                                    </div>
                                    <div>
                                        <a href="<?php echo $this->servplat_domain.'/connector/google/?origin='.base64_encode(HTTP_HOST).'&iahx='.base64_encode($iahx); ?>" class="btn btn-danger">
                                            <i class="fa fa-google"></i>
                                            <span>Google</span>
                                        </a>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-default btn-block"><?php _e('Login', 'vhl') ?></button>
                                <p><a href="<?php echo $this->servplat_server.'/pub/userData.php?c='.base64_encode(HTTP_HOST); ?>"><?php _e('registry', 'vhl') ?></a></p>
                                <p><a href="<?php echo $this->servplat_server.'/pub/forgotPassword.php?c='.base64_encode(HTTP_HOST); ?>"><?php _e('forgot my password', 'vhl') ?></a></p>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            <?php elseif ( 'link' == $layout ) : ?>
                <?php if ( $_COOKIE['userData'] ) : ?>
                    <?php $userData = json_decode(base64_decode($_COOKIE['userData']), true); ?>
                    <div class="bootstrap-iso">
                        <div class="well link logged">
                            <p><?php _e('Welcome,', 'vhl'); ?> <?php echo $userData['firstName'] ?></p>
                            <p><a href="<?php echo $this->servplat_client.'/controller/authentication/?lang='.$lang; ?>" target="_blank"><?php _e('Go to dashboard', 'vhl'); ?></a> | <a href="<?php echo $this->servplat_client.'/controller/logout/control/business/origin/'.base64_encode(HTTP_HOST).'/?lang='.$lang; ?>" style="color: red;"><?php _e('Logout', 'vhl'); ?></a></p>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="bootstrap-iso">
                        <div class="well link">
                            <p><a href="<?php echo $this->servplat_client.'/controller/authentication/control/home/origin/'.base64_encode(HTTP_HOST).'/iahx/'.base64_encode($iahx).'/?lang='.$lang; ?>"><?php _e('Login to Services Platform', 'vhl'); ?></a></p>
                            <?php if ( $_REQUEST['status'] == 'access_denied' ){ ?>
                                <p class="help-block"><?php _e('access denied', 'vhl') ?></p>
                            <?php } ?>
                            <?php if ( $_REQUEST['status'] == 'false' ){ ?>
                                <p class="help-block"><?php _e('invalid login', 'vhl') ?></p>
                            <?php } ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php elseif ( 'icon' == $layout ) : ?>
                <?php if ( $_COOKIE['userData'] ) : ?>
                    <?php $userData = json_decode(base64_decode($_COOKIE['userData']), true); ?>
                    <div class="bootstrap-iso">
                        <div class="well icon logged">
                            <p><?php _e('Welcome,', 'vhl'); ?> <?php echo $userData['firstName'] ?></p>
                            <p><a href="<?php echo $this->servplat_client.'/controller/authentication/?lang='.$lang; ?>" target="_blank"><?php _e('Go to dashboard', 'vhl'); ?></a> | <a href="<?php echo $this->servplat_client.'/controller/logout/control/business/origin/'.base64_encode(HTTP_HOST).'/?lang='.$lang; ?>" style="color: red;"><?php _e('Logout', 'vhl'); ?></a></p>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="bootstrap-iso">
                        <div class="well icon">
                            <p><a href="<?php echo $this->servplat_client.'/controller/authentication/control/home/origin/'.base64_encode(HTTP_HOST).'/iahx/'.base64_encode($iahx).'/?lang='.$lang; ?>"><i class="fa fa-user-circle"></i> <span><?php _e('Sign in', 'vhl'); ?></span></a></p>
                            <?php if ( $_REQUEST['status'] == 'access_denied' ){ ?>
                                <p class="help-block"><?php _e('access denied', 'vhl') ?></p>
                            <?php } ?>
                            <?php if ( $_REQUEST['status'] == 'false' ){ ?>
                                <p class="help-block"><?php _e('invalid login', 'vhl') ?></p>
                            <?php } ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <?php
        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['layout'] = strip_tags($new_instance['layout']);
        $instance['iahx'] = strip_tags($new_instance['iahx']);
        return $instance;
    }

    function form($instance) {
        $title = esc_attr($instance['title']);
        $layout= esc_attr($instance['layout']);
        $iahx = esc_attr($instance['iahx']);
        ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>">
                    <?php _e('Title:', 'vhl'); ?>
                    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
                </label>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('iahx'); ?>">
                    <?php _e('VHL Search URL:', 'vhl'); ?>
                    <input class="widefat" id="<?php echo $this->get_field_id('iahx'); ?>" name="<?php echo $this->get_field_name('iahx'); ?>" type="text" value="<?php echo $iahx; ?>" />
                </label>
            </p>
            <p style="font-size: 11px; background: #f7f7f7; border: 1px solid #ebebeb; padding: 4px 6px;"><?php _e('e.g.', 'vhl'); ?> http://pesquisa.bvsalud.org/brasil/</p>
            <p>
                <label>
                    <?php _e('Display layout:', 'vhl'); ?>
                    <select name="<?php echo $this->get_field_name('layout'); ?>" >
                        <option value="box" <?php if ('box' == $layout): echo ' selected="true"'; endif; ?>><?php _e('Box', 'vhl'); ?></option>
                        <option value="link" <?php if ('link' == $layout): echo ' selected="true"'; endif; ?>><?php _e('Link', 'vhl'); ?></option>
                        <option value="icon" <?php if ('icon' == $layout): echo ' selected="true"'; endif; ?>><?php _e('Icon', 'vhl'); ?></option>
                    </select>
                </label>
             </p>
        <?php
    }

    function servplat_enqueue_style() {
        wp_enqueue_style( 'bootstrap-iso', $this->servplat_client.'/vendors/bootstrap/dist/css/bootstrap-iso.css' );
        wp_enqueue_style( 'servplat-style', $this->servplat_client.'/css/plugin.css' );
        wp_enqueue_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
    }

    function fix_cookie_delay() {
        $spauth = ( isset($_GET['spauth']) && true == $_GET['spauth'] ) ? true : false;
        $splogout = ( isset($_GET['splogout']) && true == $_GET['splogout'] ) ? true : false;

        if ( $spauth || $splogout ) {
            echo '<script language="javascript">';
            echo 'window.parent.location = window.parent.location.pathname;';
            echo '</script>';
            exit;
        }
    }

}

?>
