<?php
include_once (ABSPATH . WPINC . '/feed.php');

/*** VHL Network Widget ****************/
class VHL_Network_Widget extends WP_Widget {

    var $service_url = 'http://srv.bvsalud.org/bvsnet/rss?bvs=regional.bvsalud.org';

    function VHL_Network_Widget() {
        $widget_ops = array('classname' => 'vhl-network', 'description' => __('Adds a VHL network on your site') );
        parent::WP_Widget('vhl_network', __('VHL Network'), $widget_ops);
    }
 
    function widget($args, $instance) {
        extract($args);
        echo $before_widget;
            $blank = ($instance['target'] == 'sim') ? '_blank' : '';
            if($instance['title']) echo $before_title, $instance['title'], $after_title;

            $rss_url = $this->service_url . '&' . $instance['params'];
            
            $rss = fetch_feed($rss_url);
            $rss->strip_htmltags(false);
            $rss->strip_attributes(false);
            
            if (!is_wp_error( $rss ) ) { // Checks that the object is created correctly 
                $item = $rss->get_item(0);
                echo $item->get_description();
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
                    <?php _e('Parâmetros adicionais:'); ?> 
                    <input class="widefat" id="<?php echo $this->get_field_id('params'); ?>" name="<?php echo $this->get_field_name('params'); ?>" type="text" value="<?php echo $params; ?>" />
                </label>
            </p>

        <?php 
    }
}
?>
