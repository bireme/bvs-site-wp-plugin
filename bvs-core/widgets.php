<?php

/*** VHL Collection Widget ****************/
class VHL_Widget_Collection extends WP_Widget {

    function VHL_Widget_Collection() {
        $widget_ops = array('classname' => __('VHL Collection'), 'description' => __('Adds a vhl collection on your site') );
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
                    <?php _e('Collection: '); ?>
                    <select id="<?php echo $this->get_field_id('collection_id'); ?>" name="<?php echo $this->get_field_name('collection_id'); ?>" class="widefat"> 
                        <option value="">
                            <?php echo attribute_escape(__('Select a collection')); ?>
                        </option> 
                        <?php 
                          $collection_list = get_pages('post_type=vhl_collection&parent=0'); 
                          foreach ($collection_list as $col) {
                            // check if the instance have a value for collection_id  
                            $selected = ($col->ID == $collection_id ? 'selected="true"' : '');
                              
                            $option = '<option value="'. $col->ID.'" ' . $selected . '>';
                            $option .= $col->post_title;
                            $option .= '</option>';
                            echo $option;
                         }
                        ?>
                    </select>
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
