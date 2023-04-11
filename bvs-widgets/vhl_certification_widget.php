<?php

/*** VHL Certification Widget ****************/
class VHL_Certification_Widget extends WP_Widget {

    var $service_url = 'http://cert.bvsalud.org/';

    function __construct() {
        $widget_ops = array('classname' => 'vhl-certification', 'description' => __('Adds a VHL certification on your site', 'vhl') );
        parent::__construct('vhl_certification', __('VHL Certification', 'vhl'), $widget_ops);
    }
 
    function widget($args, $instance) {
        $current_language = strtolower(get_bloginfo('language'));
        $bvs_url = substr(get_bloginfo('home'),7);      //remove http:// from url
        $lng = substr($current_language, 0,2); //use first 2 letters of language code (pt-BR = pt)

        extract($args);

        $title = $instance['title'];

        if ( function_exists( 'pll_current_language' ) ) {
            $lng = pll_current_language();
            $title = pll_translate_string($instance['title'], $lng);
        }
        
        echo $before_widget;
            $blank = ($instance['target'] == 'sim') ? '_blank' : '';
            
            if( $title ) echo $before_title, $title, $after_title;

            echo '<script type="text/javascript" src="' . $this->service_url . 'code.php?bvs_url=' .$bvs_url . '&amp;lang=' . $lng . '">/* certification */</script>';
        echo $after_widget;
    }
 
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['params'] = strip_tags($new_instance['params']);
        return $instance;
    }
    
    function form($instance) {
        $title = esc_attr($instance['title']);
        $params= esc_attr($instance['params']);
        ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>">
                    <?php _e('Title:'); ?> 
                    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
                </label>
            </p>
            <!--
            <p>
                <label for="<?php echo $this->get_field_id('params'); ?>">
                    <?php _e('Parameters:', 'vhl'); ?> 
                    <input class="widefat" id="<?php echo $this->get_field_id('params'); ?>" name="<?php echo $this->get_field_name('params'); ?>" type="text" value="<?php echo $params; ?>" />
                </label>
            </p>
            -->
        <?php 
    }
}
?>
