<?php

if ( ! defined( 'CRYPT_PUBKEY' ) ) {
        define( 'CRYPT_PUBKEY', 'biremepublicckey' );
}

if ( ! defined( 'HTTP_HOST' ) ) {
        define( 'HTTP_HOST', get_bloginfo('url') );
}

/**
 * Services Platform Login Widget
 */
class ServPlat_Login_Widget extends WP_Widget {

    private $servplat_domain;
    private $servplat_client;
    private $servplat_server;

    public function __construct() {
        $this->servplat_domain = 'http://platserv2.teste.bvsalud.org';
        $this->servplat_client = $this->servplat_domain.'/client';
        $this->servplat_server = $this->servplat_domain.'/server';

        $widget_ops = array('classname' => 'servplat-login', 'description' => __('Adds the Services Platform login on your site', 'vhl') );
        parent::WP_Widget('servplat_login', __('Services Platform Login', 'vhl'), $widget_ops);
        add_action( 'wp_enqueue_scripts', array(&$this, 'servplat_enqueue_style'), 20, 1 );

        if ( isset($_REQUEST['userID']) && !empty($_REQUEST['userID']) ) {
            $userID = urlencode($_REQUEST['userID']);
            $userID = str_replace("+", "%2B",$userID);
            $userID = urldecode($userID);
            $data = $this->unmakeUserTK($userID);

            // Fix cookie delay
            if ( $data ) {
                wp_redirect( HTTP_HOST );
                exit;
            }
        }
    }
 
    function widget($args, $instance) {
        extract($args);

        $current_language = strtolower(get_bloginfo('language'));
        // network lang parameter only accept 2 letters language code (pt, es, en)
        $lng = substr($current_language, 0,2);

        $title = $instance['title'];
        $layout = $instance['layout'];

        if ( function_exists( 'pll_current_language' ) ) {
            $lng = pll_current_language();
            $title = pll_translate_string($instance['title'], $lng);
        }

        echo $before_widget;
            if( $title ) echo $before_title, $title, $after_title;
            ?>
            <?php if ( 'box' == $layout ) : ?>
                <?php if ( $_COOKIE['userData'] ) : ?>
                    <?php $userData = json_decode(base64_decode($_COOKIE['userData']),true); ?>
                    <div class="bootstrap-iso">
                        <div class="well box">
                            <?php if ( $userData['fb_data']['picture']['data']['url'] ) : ?>
                                <img src="<?php echo $userData['fb_data']['picture']['data']['url']; ?>" alt="<?php _e('avatar,', 'vhl'); ?>" class="avatar">
                            <?php elseif ( $userData['google_data']['picture'] ) : ?>
                                <img src="<?php echo $userData['google_data']['picture']; ?>" alt="<?php _e('avatar,', 'vhl'); ?>" class="avatar">
                            <?php endif; ?>
                            <p><?php _e('Welcome,', 'vhl'); ?> <?php echo $userData['firstName'] ?></p>
                            <p><a href="<?php echo $this->servplat_client.'/controller/authentication'; ?>"><?php _e('Go to dashboard', 'vhl'); ?></a></p>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="bootstrap-iso">
                        <div class="well box">
                            <form id="loginForm" method="POST" action="<?php echo $this->servplat_client.'/controller/authentication/origin/'.base64_encode(HTTP_HOST); ?>" novalidate="novalidate">
                                <input type="hidden" name="control" value="business" />
                                <input type="hidden" name="action" value="authentication" />
                                <input type="hidden" name="lang" value="<?php echo $lng; ?>" />
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
                                <? } ?>
                                <?php if ( $_REQUEST['status'] == 'false' ){ ?>
                                    <span class="help-block"><?php _e('invalid login', 'vhl') ?></span>
                                <? } ?>
                                <div class="social-sharing">
                                    <div>
                                        <a href="<?php echo $this->servplat_domain.'/connector/facebook/?origin='.base64_encode(HTTP_HOST); ?>" class="btn btn-primary">
                                            <i class="fa fa-facebook"></i>
                                            <span>Facebook</span>
                                        </a>
                                    </div>
                                    <div>
                                        <a href="<?php echo $this->servplat_domain.'/connector/google/?origin='.base64_encode(HTTP_HOST); ?>" class="btn btn-danger">
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
            <?php endif; ?>
            <?php
        echo $after_widget;
    }
 
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['layout'] = strip_tags($new_instance['layout']);
        return $instance;
    }
    
    function form($instance) {
        $title = esc_attr($instance['title']);
        $layout= esc_attr($instance['layout']);
        ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>">
                    <?php _e('Title:', 'vhl'); ?> 
                    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
                </label>
            </p>
            <p>
                <label>
                    <?php _e('Display layout:', 'vhl'); ?>                    
                    <select name="<?php echo $this->get_field_name('layout'); ?>" > 
                        <option value="box" <?php if ($layout == 'box'): echo ' selected="true"'; endif; ?>><?php _e('Box', 'vhl'); ?></option>
                        <option value="link" <?php if ($layout == 'link'): echo ' selected="true"'; endif; ?>><?php _e('Link', 'vhl'); ?></option>
                        <option value="icon" <?php if ($layout == 'icon'): echo ' selected="true"'; endif; ?>><?php _e('Icon', 'vhl'); ?></option>
                    </select>
                </label>
             </p>

        <?php 
    }

    function servplat_enqueue_style() {
        wp_enqueue_style( 'bootstrap-iso', $this->servplat_client.'/vendors/bootstrap/dist/css/bootstrap-iso.css' ); 
        wp_enqueue_style( 'servplat-style', $this->servplat_client.'/css/plugin.css' ); 
        wp_enqueue_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css' ); 
    }

    function decrypt($text,$cKey=CRYPT_PUBKEY){
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256,
                $cKey, base64_decode($text), MCRYPT_MODE_ECB,
                mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256,
                        MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }

    function unmakeUserTK($userTK, $force=null){
        $retValue = false;
        $tmp1 = explode('%+%',$this->decrypt($userTK, CRYPT_PUBKEY));
        $valid_email = filter_var($tmp1[0], FILTER_VALIDATE_EMAIL);

        if(($force === true || $valid_email) && count($tmp1) < 3){
            $tmp2['userID'] = $tmp1[0];
            $tmp2['userPass'] = $tmp1[1];
            $retValue = $tmp2;
        }elseif($tmp1[2] && in_array($tmp1[2], array('facebook', 'google'))){
            $tmp2['userID'] = $tmp1[0];
            $tmp2['userPass'] = $tmp1[1];
            $tmp2['socialMedia'] = $tmp1[2];
            $retValue = $tmp2;
        }

        return $retValue;
    }
}
?>
