<?php

if (function_exists('add_theme_support')) {
    add_theme_support('post-thumbnails');
    add_image_size('vhl-themes', 50, 50, true);
    add_image_size('vhl-themes-full', 116, 116, true);
    add_image_size('vhl-themes-extra-full', 240, 240, true);
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

            $collection_id = $instance['collection_id'];
            $post_type_name = $this->get_post_type_name();

            if ( function_exists( 'pll_current_language' ) ) {
                global $polylang;
                
                $lang = pll_current_language();
                $default_language = pll_default_language();
                $post_ids = $polylang->model->get_translations($post_type_name, $collection_id);

                if ( $post_ids[$lang] ) $collection_id = $post_ids[$lang];
            }

            $id = $collection_id;

            echo "<div class='spacer clear'></div>";
            echo $before_widget;
            echo "<div class='$extra_css'>";

            // title
            $col_title = get_the_title($id);
            if( $show_link ){
                $before_title .= '<a href="' . get_permalink($instance['collection_id']) . '" title="' . $col_title . '">';
                $after_title = '</a>' . $after_title;
            }

            echo $before_title, $col_title, $after_title;

            if ($two_columns) {
                echo "<ul class='double'>";
            } else {
                echo "<ul>";
            }

            foreach(get_children(array('post_type' => 'vhl_collection', 'post_parent' => $id, 'orderby' => 'menu_order', 'order' => 'ASC')) as $child) {

                if ($child->post_status == "publish") {

                    $cur_title = get_the_title($child->ID);
                    $permalink = get_permalink($child->ID);
                    $cur_excerpt = $child->post_excerpt; 

                    if ($thumb_size) {
                        print "<li class='thumb_120'>";
                    } else {
                        print "<li>";
                    }

                    if ($thumb_size == 'large') {
                        print "<strong><a href='$permalink' title='$cur_title'>" . get_the_post_thumbnail($child->ID, 'vhl-themes-full') . $cur_title . "</a></strong>";
                    } elseif ($thumb_size == 'extra-large') {
                        print "<strong><a href='$permalink' title='$cur_title'>" . get_the_post_thumbnail($child->ID, 'vhl-themes-extra-full') . $cur_title . "</a></strong>";
                    } elseif ($thumb_size == 'full') {
                        print "<strong><a href='$permalink' title='$cur_title'>" . get_the_post_thumbnail($child->ID) . $cur_title . "</a></strong>";
                    } else {
                        print "<strong><a href='$permalink' title='$cur_title'>" . get_the_post_thumbnail($child->ID, 'vhl-themes') . $cur_title . "</a></strong>";
                    }
                    if ($show_excerpt) {
                        print "<p class='excerpt'>" . $cur_excerpt . "</p>";
                    } 

                    print '</li>';
                    $count = 1;
                }
            }
            echo "</ul>";
	    print '<div class="spacer"></div>';
            
            echo "</div>";
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
        $instance['two_columns'] = strip_tags($new_instance['two_columns']);
        $instance['show_link'] = strip_tags($new_instance['show_link']);
        $instance['show_excerpt'] = strip_tags($new_instance['show_excerpt']);
        $instance['thumb_size'] = strip_tags($new_instance['thumb_size']);
        $instance['thumb_extra_size'] = strip_tags($new_instance['thumb_extra_size']);
        $instance['extra_css'] = strip_tags($new_instance['extra_css']);
        return $instance;
    }

    function form($instance) {
        $collection_id = esc_attr($instance['collection_id']);
        $two_columns = esc_attr($instance['two_columns']);
        $show_link = esc_attr($instance['show_link']);
        $show_excerpt = esc_attr($instance['show_excerpt']);
        $thumb_size = esc_attr($instance['thumb_size']);
        $extra_css = esc_attr($instance['extra_css']);
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
                <label for="<?php echo $this->get_field_id('extra_css'); ?>">
                    <?php _e('CSS Class:', 'vhl'); ?> 
                    <input class="widefat" id="<?php echo $this->get_field_id('extra_css'); ?>" name="<?php echo $this->get_field_name('extra_css'); ?>" type="text" value="<?php echo $extra_css; ?>" />
                </label>
            </p>
             <p>
                <label>
                    <input type="checkbox" name="<?php echo $this->get_field_name('show_link'); ?>" value="true" <?php if ($show_link == 'true'): echo ' checked="true"'; endif?> ><?php _e('Show link to collection page', 'vhl'); ?>
                </label>
             </p>
             <p>
                <label>
                    <input type="checkbox" name="<?php echo $this->get_field_name('show_excerpt'); ?>" value="true" <?php if ($show_excerpt == 'true'): echo ' checked="true"'; endif?> ><?php _e('Show excerpt', 'vhl'); ?>
                </label>
             </p>
             <p>
                <label>
                    <input type="checkbox" name="<?php echo $this->get_field_name('two_columns'); ?>" value="true" <?php if ($two_columns == 'true'): echo ' checked="true"'; endif?> ><?php _e('Display in two columns?', 'vhl'); ?>
                </label>
             </p>
             <p>
                <label>
                    <input type="radio" name="<?php echo $this->get_field_name('thumb_size'); ?>" value="large" <?php if ($thumb_size == 'large'): echo ' checked="true"'; endif?> ><?php _e('Use large thumbnails (120px)', 'vhl'); ?>
                </label>
             </p>
             <p>
                <label>
                    <input type="radio" name="<?php echo $this->get_field_name('thumb_size'); ?>" value="extra-large" <?php if ($thumb_size == 'extra-large'): echo ' checked="true"'; endif?> ><?php _e('Use extra-large thumbnails (240px)', 'vhl'); ?>
                </label>
             </p>
             <p>
                <label>
                    <input type="radio" name="<?php echo $this->get_field_name('thumb_size'); ?>" value="full" <?php if ($thumb_size == 'full'): echo ' checked="true"'; endif?> ><?php _e('Use original size thumbnails', 'vhl'); ?>
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
