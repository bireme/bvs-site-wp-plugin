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
            $levels = $instance['levels'];
            $columns = $instance['columns'];
            $show_link = $instance['show_link'];
            $order_by = $instance['order_by'];

            // add subclass thumbnail when collection first level item have featured image associated
            if ( current_theme_supports('post-thumbnails') && has_post_thumbnail($instance['collection_id']) ) {
                $before_widget = str_replace('class="', 'class="thumbnail ',$before_widget);
            }
            // add subclass that inform columns selected for the widget
            $before_widget = str_replace('class="', 'class="' . $columns .' ',$before_widget);

            echo $before_widget;

            if( $instance['title'] ){
                $col_title = $instance['title'];
            }else{
                $col_title = get_the_title($instance['collection_id']);
            }

            if( $show_link ){
                $before_title .= '<a href="' . get_permalink($instance['collection_id']) . '" title="' . $col_title . '">';
                $after_title = '</a>' . $after_title;
            }                

            echo $before_title, $col_title, $after_title;

            if ( current_theme_supports('post-thumbnails') && has_post_thumbnail($instance['collection_id']) ) {
                echo '<div class="vhl_collection_thumb">';
                //echo get_the_post_thumbnail($instance['collection_id'], 'thumbnail');
		        echo "<a href='" . get_permalink($instance['collection_id']) . "' title='$col_title'>" . get_the_post_thumbnail($instance['collection_id'], 'thumbnail') . "</a>";
                echo '</div>';
            }

            if ($columns == "twocolumn") {
                echo "<ul class='double'>";
            } else {
                echo "<ul>";
            }

            wp_list_pages('post_type=' . $post_type_name . '&depth=' . $levels . '&title_li=&child_of=' . $instance['collection_id'] . '&sort_column=' . $order_by);
	        echo "</ul>";
            echo "<div class='spacer'></div>";
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
        $instance['columns'] = strip_tags($new_instance['columns']);
        $instance['show_link'] = strip_tags($new_instance['show_link']);
        $instance['order_by'] = strip_tags($new_instance['order_by']);
        return $instance;
    }
    
    function form($instance) {        
        $title = esc_attr($instance['title']);
        $collection_id = esc_attr($instance['collection_id']);
        $levels = esc_attr($instance['levels']);
        $columns = esc_attr($instance['columns']);
        $show_link = esc_attr($instance['show_link']);
        $order_by = esc_attr($instance['order_by']);
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
                    <input type="checkbox" name="<?php echo $this->get_field_name('show_link'); ?>" value="true" <?php if ($show_link == 'true'): echo ' checked="true"'; endif?> ><?php _e('Show link to collection page', 'vhl'); ?>
                </label>
             </p>
             <p>
                <label>
                    <?php _e('Number of levels to display:', 'vhl'); ?>
                    <input id="<?php echo $this->get_field_id('levels'); ?>" name="<?php echo $this->get_field_name('levels'); ?>" type="text" value="<?php echo $levels; ?>" size="3"/>
                </label>
             </p>
             <p>
                <label>
                    <?php _e('Number of columns:', 'vhl'); ?>                    
                    <select name="<?php echo $this->get_field_name('columns'); ?>" > 
                        <option value="onecolumn" <?php if ($columns == 'onecolumn'): echo ' selected="true"'; endif?> >1</option>
                        <option value="twocolumn" <?php if ($columns == 'twocolumn' || $columns == ''): echo ' selected="true"'; endif?> >2</option>
                    </select>
                </label>
             </p>
             <p>
                <label>
                    <?php _e('Order by:', 'vhl'); ?>
                    <select name="<?php echo $this->get_field_name('order_by'); ?>" >
                        <option value="post_title"<?php if ($order_by == 'post_title' || $order_by == '') echo ' selected'; ?> ><?php _e('Title', 'vhl'); ?></option>
                        <option value="menu_order"<?php if ($order_by == 'menu_order') echo ' selected'; ?>><?php _e('Order field', 'vhl'); ?></option>
                        <option value="post_date"<?php if ($order_by == 'post_date') echo ' selected'; ?>><?php _e('Published date', 'vhl'); ?></option>
                        <option value="post_modified"<?php if ($order_by == 'post_modified') echo ' selected'; ?>><?php _e('Last modified', 'vhl'); ?></option>
                        <option value="ID"<?php if ($order_by == 'ID') echo ' selected'; ?>><?php _e('ID', 'vhl'); ?></option>
                        <option value="post_author"<?php if ($order_by == 'post_author') echo ' selected'; ?>><?php _e('Author', 'vhl'); ?></option>
                        <option value="post_name"<?php if ($order_by == 'post_name') echo ' selected'; ?>><?php _e('Slug', 'vhl'); ?></option>
                    </select>
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
