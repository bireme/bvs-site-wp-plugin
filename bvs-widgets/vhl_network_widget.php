<?php
include_once (ABSPATH . WPINC . '/feed.php');

/*** VHL Network Widget ****************/
class VHL_Network_Widget extends WP_Widget {

    var $service_url = 'http://srv.bvsalud.org/bvsnet/rss?bvs=bvsalud.org';

    function VHL_Network_Widget() {
        $widget_ops = array('classname' => 'vhl-network', 'description' => __('Adds a VHL network on your site', 'vhl') );
        parent::WP_Widget('vhl_network', __('VHL Network','vhl'), $widget_ops);
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
            $blank = ($instance['target'] == 'sim') ? '_blank' : '';
            
            if( $title ) echo $before_title, $title, $after_title;

            $rss_url = $this->service_url . '&lang=' . $lng . '&' . $instance['params'];

	    $rss = fetch_feed($rss_url);

            if (!is_wp_error( $rss ) ) { // Checks that the object is created correctly
                $rss->strip_htmltags(false);
                $rss->strip_attributes(false);
                $item = $rss->get_item(0);
                //echo $item->get_description();
                echo html_tidy($item->get_description());
            }
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
            <p>
                <label for="<?php echo $this->get_field_id('params'); ?>">
                    <?php _e('Parameters:','vhl'); ?>
                    <input class="widefat" id="<?php echo $this->get_field_id('params'); ?>" name="<?php echo $this->get_field_name('params'); ?>" type="text" value="<?php echo $params; ?>" />
                </label>
            </p>

        <?php
    }
}
?>
