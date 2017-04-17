<?php

/*** Services Platform Login Widget ****************/
class ServPlat_Login_Widget extends WP_Widget {

    function ServPlat_Login_Widget() {
        define('CRYPT_PUBKEY','biremepublicckey');
        define('SERVICES_PLATFORM_DOMAIN', 'http://platserv2.teste.bvsalud.org');
        define('SERVICES_PLATFORM_CLIENT', SERVICES_PLATFORM_DOMAIN.'/client');
        define('SERVICES_PLATFORM_SERVER', SERVICES_PLATFORM_DOMAIN.'/server');
        define('HTTP_HOST', get_bloginfo('url'));

        $widget_ops = array('classname' => 'servplat-login', 'description' => __('Adds the Services Platform login on your site', 'vhl') );
        parent::WP_Widget('servplat_login', __('Services Platform Login', 'vhl'), $widget_ops);
        add_action( 'wp_head', array(&$this, 'header'), 20, 1 );

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
                            <p><a href="<?php echo SERVICES_PLATFORM_CLIENT.'/controller/authentication'; ?>"><?php _e('Go to dashboard', 'vhl'); ?></a></p>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="bootstrap-iso">
                        <div class="well box">
                            <form id="loginForm" method="POST" action="<?php echo SERVICES_PLATFORM_CLIENT.'/controller/authentication/origin/'.base64_encode(HTTP_HOST); ?>" novalidate="novalidate">
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
                                        <a href="<?php echo SERVICES_PLATFORM_DOMAIN.'/connector/facebook/?origin='.base64_encode(HTTP_HOST); ?>" class="btn btn-primary">
                                            <i class="fa fa-facebook"></i>
                                            <span>Facebook</span>
                                        </a>
                                    </div>
                                    <div>
                                        <a href="<?php echo SERVICES_PLATFORM_DOMAIN.'/connector/google/?origin='.base64_encode(HTTP_HOST); ?>" class="btn btn-danger">
                                            <i class="fa fa-google"></i>
                                            <span>Google</span>
                                        </a>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-default btn-block"><?php _e('Login', 'vhl') ?></button>
                                <p><a href="<?php echo SERVICES_PLATFORM_SERVER.'/pub/userData.php?c='.base64_encode(HTTP_HOST); ?>"><?php _e('registry', 'vhl') ?></a></p>
                                <p><a href="<?php echo SERVICES_PLATFORM_SERVER.'/pub/forgotPassword.php?c='.base64_encode(HTTP_HOST); ?>"><?php _e('forgot my password', 'vhl') ?></a></p>
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

    function header( $instance = null ){                  
    ?>
        <link rel='stylesheet' id='bootstrap-iso' href='https://formden.com/static/assets/demos/bootstrap-iso/bootstrap-iso/bootstrap-iso.css' type='text/css' media='all' />
        <link rel='stylesheet' id='font-awesome' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css' type='text/css' media='all' />
        <style type="text/css">
            .bootstrap-iso .box {
                font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
                line-height: 1.42857143;
                color: #333;
                /*font-size: 14px*/
                /*background-color: #fff;*/
            }

            .bootstrap-iso .box div {
                padding-bottom: 0;
            }

            .bootstrap-iso .box .social-sharing {
                display: flex;
                justify-content: center;
                padding: 0 0 25px;
            }

            .bootstrap-iso .box button {
                margin-bottom: 10px;
            }

            .bootstrap-iso .box .help-block {
                color: red;
                text-align: center;
                display: block;
                margin-bottom: 5px;
            }

            .bootstrap-iso .box p {
                margin: 5px 0 0;
            }

            .bootstrap-iso .box img.avatar {
                float: left;
                margin-top: -2px;
                margin-right: 10px;
                width: 50px;
                height: 50px;
            }

            .bootstrap-iso .box .btn,
            .bootstrap-iso .box .form-control {
                font-size: 13px;
            }

            .bootstrap-iso a:visited {
                color: #337ab7;
            }

            .bootstrap-iso .social-sharing a:visited {
                color: #ffffff;
            }

            .bootstrap-iso .well.box {
                margin-bottom: 0;
            }
        </style>
    <?php
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
