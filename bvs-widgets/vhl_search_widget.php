<?php

/*** VHL Search Widget ****************/
class VHL_Search_Widget extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'vhl-search', 'description' => __('Adds a VHL search on your site', 'vhl') );
        parent::__construct('vhl_search', __('VHL Search', 'vhl'), $widget_ops);
        add_action( 'wp_footer', array(&$this, 'footer'), 20, 1 );
    }
 
    function widget($args, $instance) {
        extract($args);

        $current_language = strtolower(get_bloginfo('language'));
        // network lang parameter only accept 2 letters language code (pt, es, en)
        $lng = substr($current_language, 0,2);

        $title = $instance['title'];

        if ( function_exists( 'pll_current_language' ) ) {
            $lng = pll_current_language();
            $title = pll_translate_string($instance['title'], $lng);
        }

        echo $before_widget;
            if( $title ) echo $before_title, $title, $after_title;

             echo '<form action="' . $instance['action'] . '" method="get" id="searchForm" >';
             echo '   <input type="hidden" name="lang" value="' . $lng . '" />';
             echo '   <input type="hidden" name="home_url" value="' . get_bloginfo('home') . '" />';
             echo '   <input type="hidden" name="home_text" value="' . get_bloginfo('name') . '" />';
             echo '   <label for="vhl-search-input" style="display: none;">' .__('Search', 'vhl'). '</label>';
             echo '   <input type="text" id="vhl-search-input" class="vhl-search-input" name="q" value="' .__('Search', 'vhl') . '" />';
             echo '   <input type="submit" class="vhl-search-submit submit" name="submit" value="' .__('Search', 'vhl') .'" />';
             echo '</form>';
        echo $after_widget;
    }
 
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['action'] = strip_tags($new_instance['action']);
        return $instance;
    }
    
    function form($instance) {
        $title = esc_attr($instance['title']);
        $action= esc_attr($instance['action']);
        ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>">
                    <?php _e('Title:', 'vhl'); ?> 
                    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
                </label>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('action'); ?>">
                    <?php _e('Search URL:', 'vhl'); ?> 
                    <input class="widefat" id="<?php echo $this->get_field_id('action'); ?>" name="<?php echo $this->get_field_name('action'); ?>" type="text" value="<?php echo $action; ?>" />
                </label>
            </p>

        <?php 
    }

    function footer( $instance = null ){

        echo "\n<script type=\"text/javascript\">/* <![CDATA[ */";
        echo "\n$ = jQuery;";
        echo "\n$(document).ready(function() { \n";
                  
    ?>
            inputval = $( ".vhl-search-submit" ).val();
            $( "#vhl-search-input", this).focus(function() {
                if (inputval == $(this).val())
                {
                    $(this).attr('value', '');
                }
            });
            $( "#vhl-search-input", this ).blur(function() {
                if (!$(this).val())
                {
                    $(this).attr('value', inputval);
                }
            });
            $( "#searchForm", this ).submit(function() {
                if ($(this).children('#vhl-search-input').val() == inputval)
                {
                    $(this).children('#vhl-search-input').attr('value', '');
                }
            });
    <?php

        echo "\n});";
        echo "\n/* ]]> */</script>";
        echo "\n<noscript>Your browser does not support JavaScript!</noscript>";
        echo "\n\n ";

    }
}
?>
