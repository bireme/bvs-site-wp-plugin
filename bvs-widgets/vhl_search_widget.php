<?php

/*** VHL Search Widget ****************/
class VHL_Search_Widget extends WP_Widget {

    function VHL_Search_Widget() {
        $widget_ops = array('classname' => 'vhl-search', 'description' => __('Adds a VHL search on your site') );
        parent::WP_Widget('vhl_search', __('VHL Search'), $widget_ops);
    }
 
    function widget($args, $instance) {
        extract($args);

        $current_language = strtolower(get_bloginfo('language'));
        // network lang parameter only accept 2 letters language code (pt, es, en)
        $lng = substr($current_language, 0,2);

        echo $before_widget;
            if($instance['title']) echo $before_title, $instance['title'], $after_title;

             echo '<form action="' . $instance['action'] . '" method="get" name="searchForm">';
             echo '   <input type="hidden" value="pt" name="' . $lng . '">';
             echo '   <input type="hidden" name="home_url" value="' . get_bloginfo('home') . '">';
             echo '   <input type="hidden" name="home_text" value="' . get_bloginfo('name') . '">';
             echo '   <input type="text" value="" class="vhl-search-input" name="q" id="vhl-search-input">';
             echo '   <input type="submit" class="vhl-search-submit submit" name="submit" value="Pesquisar">';
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
                    <?php _e('Title:'); ?> 
                    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
                </label>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('action'); ?>">
                    <?php _e('Search URL:'); ?> 
                    <input class="widefat" id="<?php echo $this->get_field_id('action'); ?>" name="<?php echo $this->get_field_name('action'); ?>" type="text" value="<?php echo $action; ?>" />
                </label>
            </p>

        <?php 
    }
}
?>
