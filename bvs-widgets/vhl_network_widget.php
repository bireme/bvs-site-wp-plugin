<?php
/*** VHL Network Widget ****************/
class VHL_Network_Widget extends WP_Widget {

    function VHL_Network_Widget() {
        $widget_ops = array('classname' => __('VHL Network'), 'description' => __('Adds a VHL network on your site') );
        parent::WP_Widget('vhl_network', __('VHL Network'), $widget_ops);
    }
 
    function widget($args, $instance) {
        extract($args);
        echo $before_widget;
            $blank = ($instance['target'] == 'sim') ? '_blank' : '';
            if($instance['title']) echo $before_title, $instance['title'], $after_title;
            
            
        echo $after_widget;
    }
 
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        
        $instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }
    
    function form($instance) {        
        $title = esc_attr($instance['title']);
        ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>">
                    <?php _e('Title:'); ?> 
                    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
                </label>
            </p>
        <?php 
    }
}
?>
