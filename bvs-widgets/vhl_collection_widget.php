<?php

/*** VHL Collection Widget ****************/
class VHL_Collection_Widget extends WP_Widget {

    function VHL_Collection_Widget() {
        $widget_ops = array('classname' => 'vhl-collection', 'description' => __('Adds a VHL collection on your site', 'vhl') );
        parent::WP_Widget('vhl_collection', __('VHL Collection', 'vhl'), $widget_ops);
    }
 
    function widget($args, $instance) {
        extract($args);

       if ( $instance['collection_id'] != '' ){
            $post_type_name = $this->get_post_type_name();

            // add subclass thumbnail when collection first level item have featured image associated
            if ( current_theme_supports('post-thumbnails') && has_post_thumbnail($instance['collection_id']) ) {
                echo str_replace('class="', 'class="thumbnail ',$before_widget);
            }else{
                echo $before_widget;
            }

            $blank = ($instance['target'] == 'sim') ? '_blank' : '';
            if( $instance['title'] ){
                echo $before_title, $instance['title'], $after_title;
            }else{
                echo $before_title, get_the_title($instance['collection_id']), $after_title;
            }
            $levels = $instance['levels'];

            if ( current_theme_supports('post-thumbnails') && has_post_thumbnail($instance['collection_id']) ) {
                echo '<div class="vhl_collection_thumb">';
                echo get_the_post_thumbnail($instance['collection_id'], 'thumbnail');
                echo '</div>';
            }
            echo "<ul>";
            wp_list_pages('post_type=' . $post_type_name . '&depth=' . $levels . '&title_li=&child_of=' . $instance['collection_id']);
            echo '</ul>';
            echo $after_widget;
       }
    }

    
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        
        if ( $new_instance['title'] == '' ){
            $instance['title'] = get_the_title($instance['collection_id']);
        }else{
            $instance['title'] = strip_tags($new_instance['title']);
        }
       
        $instance['collection_id'] = strip_tags($new_instance['collection_id']);
        $instance['levels'] = strip_tags($new_instance['levels']);
        return $instance;
    }
    
    function form($instance) {        
        $title = esc_attr($instance['title']);
        $collection_id = esc_attr($instance['collection_id']);
        $levels = esc_attr($instance['levels']);
        $post_type_name = $this->get_post_type_name();

        ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>">
                    <?php _e('Title:', 'vhl'); ?>
                    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
                </label>
            </p>
            <p>
                <label>
                    <?php _e('Collection:', 'vhl'); ?>
                    <select id="<?php echo $this->get_field_id('collection_id'); ?>" name="<?php echo $this->get_field_name('collection_id'); ?>" class="widefat"> 
                        <option value="">
                            <?php echo attribute_escape(__('Select a collection', 'vhl')); ?>
                        </option> 
                        <?php 
                          $collection_list = get_pages('post_type=' . $post_type_name .'&parent=0'); 
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
             <p>
                <label>
                    <?php _e('Number of levels to display:', 'vhl'); ?>
                    <input id="<?php echo $this->get_field_id('levels'); ?>" name="<?php echo $this->get_field_name('levels'); ?>" type="text" value="<?php echo $levels; ?>" size="3"/>
                </label>

             </p>   
        <?php 
    }

    function get_post_type_name(){
        //default post_type_name
        $post_type_name = 'vhl_collection';
        
        // check for Multi Language Framework plugin options
        $mlf_options = get_option('mlf_config');
        if ( is_array($mlf_options) ) {
            $mlf_options = get_option('mlf_config');
            $current_language = strtolower(get_bloginfo('language'));

            // mlf register the translation post_type using only first 2 letters of language code (pt-BR = pt)
            $lng = substr($current_language, 0,2);
            if ($mlf_options['default_language'] != $lng){
                $post_type_name = 'vhl_collection_t_' . $lng;
            }
        }    
        return $post_type_name;
    }

   function vhl_list_title($title) {
        $dash = strpos($title, '&#8211;');

        if ($dash !== false)
            $title = substr($title, 0, $dash);

        return $title;
    }
}
?>
