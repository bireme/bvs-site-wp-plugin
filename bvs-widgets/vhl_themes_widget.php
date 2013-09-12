<?php

if (function_exists('add_theme_support')) {
    add_theme_support('post-thumbnails');
    add_image_size('vhl-themes', 50, 50, true);
}

/*** VHL Themes Widget ****************/
class VHL_Themes_Widget extends WP_Widget {

    function VHL_Themes_Widget() {
        $widget_ops = array('classname' => 'vhl-themes', 'description' => __('Adds a VHL theme on your site', 'vhl') );
        parent::WP_Widget('vhl_themes', __('VHL Themes', 'vhl'), $widget_ops);
    }

    function widget($args, $instance) {

        extract($args);

        if ( $instance['collection_id'] != '' ){
            extract($instance);

            $post_type_name = $this->get_post_type_name();
            $id = $collection_id;

            echo $before_widget;

            // title
            $col_title = get_the_title($id);
            if( $show_link ){
                $before_title .= '<a href="' . get_permalink($instance['collection_id']) . '" title="' . $col_title . '">';
                $after_title .= '</a>';
            }

            echo $before_title, $col_title, $after_title;

            if ($two_columns) {
                echo "<ul class='double'>";
            } else {
                echo "<ul>";
            }

            foreach(get_children(array('post_type' => 'vhl_collection', 'post_parent' =>$id, 'orderby' => 'menu_order', 'order' => 'ASC')) as $child) {

                if ($child->post_status == "publish") {

                    $cur_title = get_the_title($child->ID);
                    $permalink = get_permalink($child->ID);

                    print "<li>";

                    print "<strong><a href='$permalink' title='$cur_title'>" . get_the_post_thumbnail($child->ID, 'vhl-themes') . $cur_title . "</a></strong>";

                    print '</li>';
                    $count = 1;
                }
            }

	    print '<div class="spacer"></div>';
            echo $after_widget;
            echo "</ul>";
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
        $instance['two_columns'] = strip_tags($new_instance['two_columns']);
        $instance['show_link'] = strip_tags($new_instance['show_link']);
        return $instance;
    }

    function form($instance) {
        $collection_id = esc_attr($instance['collection_id']);
        $two_columns = esc_attr($instance['two_columns']);
        $show_link = esc_attr($instance['show_link']);
        $post_type_name = $this->get_post_type_name();

        ?>
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
                    <input type="checkbox" name="<?php echo $this->get_field_name('two_columns'); ?>" value="true" <?php if ($two_columns == 'true'): echo ' checked="true"'; endif?> ><?php _e('Display in two columns?', 'vhl'); ?>
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
