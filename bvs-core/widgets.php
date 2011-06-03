<?php

/*** VHL Collection Widget ****************/
class VHL_Widget_Collection extends WP_Widget {

    function VHL_Widget_Collection() {
        $widget_ops = array('classname' => __('VHL Collection'), 'description' => __('Adds a vhl collection on page') );
        parent::WP_Widget('vhl_collection', __('VHL Collection'), $widget_ops);
    }
 
    function widget($args, $instance) {
        extract($args);
        echo $before_widget;
            $blank = ($instance['target'] == 'sim') ? '_blank' : '';
            if($instance['title']) echo $before_title, $instance['title'], $after_title;
            
            echo '<ul>';
            wp_list_pages('post_type=vhl_collection&title_li=&child_of=' . $instance['collection_id']);
            echo '</ul>';
            
        echo $after_widget;
    }
 
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['collection_id'] = strip_tags($new_instance['collection_id']);
        return $instance;
    }
    
    function form($instance) {        
        $title = esc_attr($instance['title']);
        $collection_id = esc_attr($instance['collection_id']);
        
        ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>">
                    <?php _e('Title:'); ?> 
                    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
                </label>
            </p>
            <p>
                <label>
                    <?php _e('Collection id:'); ?> 
                    <input size="3" id="<?php echo $this->get_field_id('collection_id'); ?>" name="<?php echo $this->get_field_name('collection_id'); ?>" type="text" value="<?php echo $collection_id; ?>" />
                </label>
            </p>
        <?php 

    }
 
}
function register_vhl_widgets() {
    register_widget("VHL_Widget_Collection");
}

add_action('widgets_init', 'register_vhl_widgets');

?>
