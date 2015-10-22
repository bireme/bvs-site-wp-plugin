<?php

/*** VHL Clusters Widget ****************/
class VHL_Clusters_Widget extends WP_Widget {

    function VHL_Clusters_Widget() {
        $widget_ops = array('classname' => 'vhl-clusters', 'description' => __('Adds a cluster list from iAHx on your site', 'vhl') );
        parent::WP_Widget('vhl_clusters', __('VHL Clusters', 'vhl'), $widget_ops);
        add_action( 'wp_footer', array(&$this, 'footer'), 20, 1 );
    }

    function translate($key, $array_lang) {
        if(array_key_exists($key, $array_lang)) {
            return $array_lang[$key];
        }
        return $key;
    }
 
    function widget($args, $instance) {
        extract($args);

        echo $before_widget;
        echo "<div class='$extra_css'>";

        if(!empty($instance['image'])) {

            print "<div class='vhl-clusters-thumb'>";
            print "<img src='${instance['image']}'>";
            print "</div>";
        }
        
        print "<h2 class='widget-title'>${instance['title']}</h2>";
        
        if(!empty($instance['clusters']) and !empty($instance['cluster'])) {

            print "<ul>";
            $count = 0;
            foreach($instance['clusters'][$instance['cluster']] as $cluster_content) {

                if($count >= (int)$instance['results']) {
                    break;
                } else {
                    $count++;
                }


                $lang_key = "REFINE_" . $instance['cluster'];
                $array_lang = $instance['language_content'];

                print "<li class='vhl_clusters_${instance['columns']}_columns'>";
                print "<a href='javascript:vhl_clusters_open_cluster(\"${instance['cluster']}\", \"${cluster_content[0]}\", \"${instance['url']}\");'>";
                print $this->translate($cluster_content[0], $array_lang[$lang_key]) . " (${cluster_content[1]})";
                print "</a>";
                print "</li>";
            }
            print "</ul>";
            print "<div class='clear'></div>";

        }
        
        echo "</div>";
        echo $after_widget;
    }
 
    function update($new_instance, $old_instance) {

        $current_language = strtolower(get_bloginfo('language'));
        // network lang parameter only accept 2 letters language code (pt, es, en)
        $lng = substr($current_language, 0,2);
        
        $instance = $old_instance;
        
        $instance['cluster'] = strip_tags($new_instance['cluster']);
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['url'] = strip_tags($new_instance['url']);
        $instance['results'] = strip_tags($new_instance['results']);
        $instance['image'] = strip_tags($new_instance['image']);
        $instance['columns'] = strip_tags($new_instance['columns']);
        $instance['extra_css'] = strip_tags($new_instance['extra_css']);

        if(!empty($instance['url']) and strpos($instance['url'], '?') === false) {
            $instance['url'] = $instance['url'] . "?";
        }

        $url_dia_ws = file_get_contents($instance['url'] . "&debug=true");
        $url_dia_ws = explode("<br/><!DOCTYPE", $url_dia_ws)
        $url_dia_ws = $url_dia_ws[0];
        $url_dia_ws = str_replace('<b>request:</b> ', '', $url_dia_ws);
        $url_dia_ws = trim($url_dia_ws);
        $instance['url_dia_ws'] = $url_dia_ws;

        if(!empty($instance['cluster'])) {
            
            $instance['url_dia_ws'] .= '&fb=' . $instance['cluster'] . ":" . $instance['results'];
        }
        
        $url = $instance['url_dia_ws'];
        $data = json_decode(file_get_contents($url), true);
        $instance['clusters'] = $data['diaServerResponse'][0]['facet_counts']['facet_fields'];

        $language_url = explode("/", $instance['url']);
        $language_url = str_replace(end($language_url), '', $instance['url']);

        $language_url .= "/locale/$lng/texts.ini";
        $instance['language_url'] = $language_url;

        $instance['language_content'] = file_get_contents($instance['language_url']);
        $instance['language_content'] = parse_ini_string($instance['language_content'], true);

        return $instance;
    }
    
    function form($instance) {
        
        $title = esc_attr($instance['title']);
        $url = esc_attr($instance['url']);
        $results = esc_attr($instance['results']);
        $image = esc_attr($instance['image']);
        $columns = esc_attr($instance['columns']);
        $extra_css = esc_attr($instance['extra_css']);

        // var_dump($instance['url_dia_ws']);
        // var_dump($instance['language_content']);

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

                <p id='VhlClustersUseImage'>
                    <label for="<?php echo $this->get_field_id('image'); ?>">
                        <?php _e('Endereço da Imagem:', 'vhl'); ?> <i>(<?php _e('empty if not use', 'vhl'); ?>)</i>
                        <input class="widefat" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>" type="text" value="<?php echo $image; ?>" />
                    </label>
                </p>
            
                <p>
                    <label for="<?php echo $this->get_field_id('cluster'); ?>">
                        <?php _e('Cluster:', 'vhl'); ?> 
                        <select class='widefat' id="<?php echo $this->get_field_id('cluster'); ?>" name="<?php echo $this->get_field_name('cluster'); ?>">
                            <option></option>
                            <?php foreach(array_keys($instance['clusters']) as $cluster): ?>
                                <?php if($cluster == $instance['cluster']): ?>
                                    <option value="<?= $cluster; ?>" selected><?= $this->translate($cluster, $array_lang['REFINE']); ?></option>
                                <?php else: ?>
                                    <option value="<?= $cluster; ?>"><?= $this->translate($cluster, $instance['language_content']['REFINE']); ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </p>                

                <p>
                    <label for="<?php echo $this->get_field_id('results'); ?>">
                        <?php _e('Number of Results:', 'vhl'); ?> 
                        <input class="widefat" id="<?php echo $this->get_field_id('results'); ?>" name="<?php echo $this->get_field_name('results'); ?>" type="number" value="<?php echo $results; ?>" />
                    </label>
                </p>

                <p>
                    <label for="<?php echo $this->get_field_id('extra_css'); ?>">
                        <?php _e('CSS Class:', 'vhl'); ?> 
                        <input class="widefat" id="<?php echo $this->get_field_id('extra_css'); ?>" name="<?php echo $this->get_field_name('extra_css'); ?>" type="text" value="<?php echo $extra_css; ?>" />
                    </label>
                </p>

                <p>
                    <label for="<?php echo $this->get_field_id('columns'); ?>">
                        <input class="widefat" id="<?php echo $this->get_field_id('columns'); ?>" name="<?php echo $this->get_field_name('columns'); ?>" type="radio" value="1" <?php if($columns == 1) print 'checked="true"'; ?> /> <?php _e('One Column', 'vhl'); ?> 
                    </label>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id('columns'); ?>">
                        <input class="widefat" id="<?php echo $this->get_field_id('columns'); ?>" name="<?php echo $this->get_field_name('columns'); ?>" type="radio" value="2" <?php if($columns == 2) print 'checked="true"'; ?> /> <?php _e('Two Columns', 'vhl'); ?> 
                    </label>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id('columns'); ?>">
                        <input class="widefat" id="<?php echo $this->get_field_id('columns'); ?>" name="<?php echo $this->get_field_name('columns'); ?>" type="radio" value="3" <?php if($columns == 3) print 'checked="true"'; ?>/> <?php _e('Three Columns', 'vhl'); ?> 
                    </label>
                </p>
            <?php endif; ?>

        <?php 
    }

    function footer( $instance = null ){ ?>

        <script type="text/javascript">/* <![CDATA[ */
            function vhl_clusters_open_cluster(cluster, field, url) {
                var f = document.createElement("form");
                f.setAttribute('method',"post");
                f.setAttribute('action', url);

                var i = document.createElement("input"); //input element, text
                i.setAttribute('type',"text");
                i.setAttribute('name', "filter["+cluster+"][]");
                i.setAttribute('value', field);
                f.appendChild(i);

                f.submit();
            }
        /* ]]> */</script>
        <noscript>Your browser does not support JavaScript!</noscript>

        <style>
            .vhl_clusters_2_columns {
                width: 50%;
                float: left;
            }   
            .vhl_clusters_3_columns {
                width: 33%;
                float: left;
            }
        </style>

    <?php }
}
?>
