<?php

/*** VHL Clusters Widget ****************/
class VHL_Clusters_Widget extends WP_Widget {

    function VHL_Clusters_Widget() {
        $widget_ops = array('classname' => 'vhl-clusters', 'description' => __('Adds a cluster list from iAHx on your site', 'vhl') );
        parent::WP_Widget('vhl_clusters', __('VHL Clusters', 'vhl'), $widget_ops);
    }
 
    function widget($args, $instance) {
        extract($args);

        echo $before_widget;
        
        if(!empty($instance['clusters']) and !empty($instance['cluster'])) {

            // print "<pre>";
            // var_dump($instance['clusters']);

            print "<ul>";
            foreach($instance['clusters'][$instance['cluster']] as $cluster_content) {
                print "<li>";
                print $cluster_content[0] . " (${cluster_content[1]})";
                print "</li>";
            }
            print "</ul>";

        }
        
        echo $after_widget;
    }
 
    function update($new_instance, $old_instance) {
        
        $instance = $old_instance;
        
        $instance['cluster'] = strip_tags($new_instance['cluster']);
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['url'] = strip_tags($new_instance['url']);

        if(!empty($instance['url']) and strpos($instance['url'], '?') === false) {
            $instance['url'] = $instance['url'] . "?";
        }

        if(!empty($instance['url']) and empty($instance['url_dia_ws'])) {

            $url_dia_ws = file_get_contents($instance['url'] . "&debug=true");
            $url_dia_ws = explode("<br/><!DOCTYPE", $url_dia_ws)[0];
            $url_dia_ws = str_replace('<b>request:</b> ', '', $url_dia_ws);
            $url_dia_ws = trim($url_dia_ws);

            $instance['url_dia_ws'] = $url_dia_ws;
        }

        if(empty($instance['url'])) {
            $instance['url_dia_ws'] = NULL;
        }

        if(!empty($instance['url_dia_ws'])) {

            $url = $instance['url_dia_ws'];
            $data = json_decode(file_get_contents($url), true);
            $instance['clusters'] = $data['diaServerResponse'][0]['facet_counts']['facet_fields'];
        }

        return $instance;
    }
    
    function form($instance) {
        
        $title = esc_attr($instance['title']);
        $url = esc_attr($instance['url']);

        ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>">
                    <?php _e('Title:', 'vhl'); ?> 
                    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
                </label>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('url'); ?>">
                    <?php _e('iAHx Url:', 'vhl'); ?> 
                    <input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo $url; ?>" />
                </label>
            </p>

            <?php if(!empty($url)): ?>
                <p>
                    <label for="<?php echo $this->get_field_id('cluster'); ?>">
                        <?php _e('Cluster:', 'vhl'); ?> 
                        <select class='widefat' id="<?php echo $this->get_field_id('cluster'); ?>" name="<?php echo $this->get_field_name('cluster'); ?>">
                            <option></option>
                            <?php foreach(array_keys($instance['clusters']) as $cluster): ?>
                                <?php if($cluster == $instance['cluster']): ?>
                                    <option value="<?= $cluster; ?>" selected><?= $cluster; ?></option>
                                <?php else: ?>
                                    <option value="<?= $cluster; ?>"><?= $cluster; ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </p>                
            <?php endif; ?>

        <?php 
    }
}
?>
