<?php

add_action( 'init', 'create_vhl_post_type');
add_action( 'add_meta_boxes', 'vhl_add_custom_box' );
add_action( 'save_post', 'save_vhl_meta', 1, 2); // save the custom fields


/* define custom fields */
$meta_fields['metasearch'][] = array( "name" => "Base search url",
                        "desc" => "",
                        "id" => "_vhl_base_search_url",
                        "type" => "text");
$meta_fields['page_links_to'][] = array( "name" => "Point to this URL:",
                        "desc" => "",
                        "id" => "_vhl_links_to",
                        "type" => "text");
$meta_fields['page_links_to'][] = array( "name" => "Open this link in a new window",
                        "desc" => "",
                        "id" => "_vhl_links_to_new_window",
                        "type" => "checkbox");

// check for multi language framework and create list of custom post_type (including translations)
$mlf_config = get_option('mlf_config');
$vhl_post_type_list[] = 'vhl_collection';
foreach ( $mlf_config['enabled_languages'] as $lng )
    $vhl_post_type_list[] = 'vhl_collection_t_' . $lng;


function create_vhl_post_type() {
    global $vhl_post_type_list;

    register_post_type( 'vhl_collection',
        array(
       'labels' => array(
                'name' => __( 'BVS Collection' ),
                'singular_name' => __( 'Item' ),
                'add_new' => __( 'Add New Item' ),
                'add_new_item' => __( 'Add New Item' ),
                'edit_item' => __( 'Edit Item' ),
                'new_item' => __( 'Add New Item' ),
                'view_item' => __( 'View Item' ),
                'search_items' => __( 'Search Item' ),
                'parent_item_colon' => __('Item pai'), 
                'not_found' => __( 'No items found' ),
                'not_found_in_trash' => __( 'No item found in trash' ),
                'menu_name' => 'BVS Collection',
            ),
            'public' => true,
            'show_ui' => true,
            'has_archive' => true,
            'hierarchical' => true,
            'rewrite' => array('slug' => 'vhl'),
            'menu_position' => 20,
            'capability_type' => 'page',
        )
    );

    // register support for each custom post_type (including translation)
    foreach ( $vhl_post_type_list as $post_type_name )
        add_post_type_support( $post_type_name,  array('title','editor','revisions','page-attributes') );

}


function vhl_add_custom_box() {
    global $vhl_post_type_list;

    /* register custom_meta_box for each custom post_type (including translation)
    foreach ( $vhl_post_type_list as $post_type_name )       
        add_meta_box( $post_type_name, 'Meta Search', 'vhl_metasearch_custom_box',  $post_type_name ,'normal', 'high');
    */

}

/* Prints the box content */
function vhl_metasearch_custom_box() {
    global $post, $meta_fields;

    echo '<input type="hidden" name="vhl_noncename" id="vhl_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
    echo '<div class="vhl-metabox-field-group">';
    foreach ($meta_fields['metasearch'] as $field)
        vhl_print_metafield($field);  
    
    echo '</div>';
}

function vhl_links_to_custom_box() {
    global $post, $meta_fields;
    
    echo '<input type="hidden" name="vhl_noncename" id="vhl_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
    echo '<div class="vhl-metabox-field-group">';
    foreach ($meta_fields['page_links_to'] as $field)
        vhl_print_metafield($field);  

    echo '</div>';
}


function vhl_print_metafield($field){
    global $post;
 
    $field_id = $field["id"];
    $field_name = $field["name"];
    $field_type = $field["type"];
    $field_description = $field["desc"];
    $field_repeatable = $field["repeatable"];

    if ($field_repeatable == true){    
        $field_value = get_post_custom_values($field_id, $post->ID);
    }else{            
        $field_value = get_post_meta($post->ID, $field_id, true);
    }

    echo '<div class="vhl-metabox-field">';

    switch ($field_type){
        case 'text':
            echo '    <div class="vhl-metabox-field-col1">';
            echo '        <label for="' . $field_id . '">'. $field_name .'</label>';
            echo '        <p class="howto">' . $field_description . '</p>';
            echo '    </div>';
            echo '    <div class="vhl-metabox-field-col2" id="' . $field_id .'">';
            
            if ($field_repeatable == true){
                echo '<input class="text" name="'. $field_id . '[]" id="' . $field_id . '" value="' . $field_value[0] . '">';
                echo '<input type="button" class="addButton" value="add +"/>';
                if (count($field_value) > 1){
                    $count_item = 0;
                    foreach ($field_value as $item_value){
                            $count_item++;
                            if ($count_item > 1 && $item_value != '') 
                                echo '<input class="text" name="'. $field_id . '[]" id="' . $field_id . '" value="' . $item_value . '">';
                    }
                }
            }else{
                echo '<input class="text" name="'. $field_id . '" id="' . $field_id . '" value="' . $field_value . '">';
            }            
            echo '    </div>';
            break;
        case 'textarea':
            echo '     <div class="vhl-metabox-field-col1">';
            echo '         <label for="' . $field_id . '">'. $field_name .'</label>';
            echo '     </div>';

            echo '     <div class="vhl-metabox-field-col2">';            
            echo '        <textarea id="' . $field_id . '" name="'. $field_id . '" rows="5">' . $field_value . '</textarea>';
            echo '     </div>';
            break;

        case 'checkbox':
            ?>
            <label for="<?php echo $field_id; ?>">            
                <input type='checkbox' name='<?php echo $field_id;?>' id='<?php echo $field_id; ?>' value='_blank' <?php checked( '_blank', $field_value ); ?> > <?php echo $field_name; ?>
            </label>
            <?php
            break;

        case 'file':
            $attachment_id = (int) $field_value;

            $file_html = "";
            $file_name = "";
            if ($attachment_id) {
                $file_thumbnail = wp_get_attachment_image_src( $attachment_id, 'thumbnail', true );
                $file_thumbnail = $file_thumbnail[0];
                $file_html = "<img src='$file_thumbnail' alt='' />";
                $file_post = get_post($attachment_id);
                $file_name = esc_html($file_post->post_title);
            }
        
            echo '<div class="simple-fields-metabox-field">';
            echo '   <div class="simple-fields-metabox-field-file"><label>' . $field_name . '</label>';
            echo '      <div class="simple-fields-metabox-field-file-col1">';
            echo '      <div class="simple-fields-metabox-field-file-selected-image">' . $file_html  . '</div>';
            echo '   </div>';
        
            echo '   <div class="simple-fields-metabox-field-file-col2">';
            echo '      <input type="hidden" name="' . $field_id .'" id="'. $field_id .'" value="' . $field_value .'" />';
            echo '      <div class="simple-fields-metabox-field-file-selected-image-name">' . $file_name . '</div>';
            echo '          <a class="thickbox simple-fields-metabox-field-file-select" href="media-upload.php?simple_fields_dummy=1&simple_fields_action=select_file&simple_fields_file_field_unique_id=' . $field_id .'&post_id=-1&TB_iframe=true&width=640&height=426">Select file</a> | <a href="#" class="simple-fields-metabox-field-file-clear">Clear</a>';
            echo '      </div>';
            echo '   </div>';
            echo '</div>';
            break;


    }    
    echo '</div>';
    
}



// Save the Metabox Data 
function save_vhl_meta($post_id, $post) {
    global $post, $meta_fields;
 
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( !wp_verify_nonce( $_POST['vhl_noncename'], plugin_basename(__FILE__) )) {
        return $post->ID;
    }
 
    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;
 
    // OK, we're authenticated: we need to find and save the data
    // We'll put it into an array to make it easier to loop though.    
    foreach ($meta_fields as $meta_fields_group){
        foreach ($meta_fields_group as $meta){
            $id = $meta['id'];
            $vhl_meta[$id] = $_POST[$id];
        }
    }

    // Add values of $vhl_meta as custom fields
    foreach ($vhl_meta as $key => $value) { // Cycle through the $vhl_meta array!
        if( $post->post_type == 'revision' ) return; // Don't store custom data twice
        
        //$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)

        // treatment for repeatable fields
        if (is_array($value)){
            // delete previous version of all occurences of meta field
            $repeatable_values = get_post_custom_values($key, $post->ID);
            foreach ( $repeatable_values as $old_rep_value ){
                delete_post_meta($post->ID, $key, $old_rep_value);
            }    
            // add new values for meta field
            foreach ($value as $new_rep_value){
                add_post_meta($post->ID, $key, $new_rep_value);
            }
        // treatment for single fields
        }else{        
            if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value            
                update_post_meta($post->ID, $key, $value);
            } else { // If the custom field doesn't have a value
                add_post_meta($post->ID, $key, $value);
            }
            if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
        }

    }
}

?>
