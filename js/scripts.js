(function($) {

    // add new field to the field group
    function simple_fields_field_group_add_field() {
        simple_fields_highest_field_id++;
        var data = {
            action: 'simple_fields_field_group_add_field',
            simple_fields_highest_field_id: simple_fields_highest_field_id
        }
        $.post(ajaxurl, data, function(response) {
            var ul = $("#simple-fields-field-group-existing-fields ul:first");
            $response = $(response);
            ul.append($response);
            ul.find(".simple-fields-field-group-one-field:last").effect("highlight").find(".simple-fields-field-group-one-field-name").focus();
            //$response.effect("highlight").find(".simple-fields-field-group-one-field-name").focus();
        });     
    }
    
    function simple_fields_metabox_tinymce_attach() {
        if (typeof( tinyMCE ) == "object" && typeof( tinyMCEPreInit ) == "object" ) {
            var tiny_init = tinyMCEPreInit.mceInit;
            tiny_init.mode = "exact";
            tiny_init.theme_advanced_resizing = true;
            var elms_to_convert = jQuery(".simple-fields-metabox-field-textarea-tinymce");
            var str_elms_to_convert = "";
            for (var i=0; i<elms_to_convert.length; i++) {
                var one_elm = elms_to_convert[i];
                // check if this element id already is a tiny editor
                if (tinyMCE.get(one_elm.id)) {
                    // exists, do nada
                } else {
                    // does not exist, not a tiny editor, so add to the list of ids to convert to editors
                    str_elms_to_convert += one_elm.id + ",";
                }
                
            }
            str_elms_to_convert = str_elms_to_convert.replace(/,$/, "");
            if (str_elms_to_convert != "") {
                tiny_init.elements = str_elms_to_convert;
                tinyMCE.init( tiny_init );
            }
        }
    }
    
    function simple_fields_metabox_tinymce_detach() {
        for( edId in tinyMCE.editors ) {
            if ( /simple_fields/.test(edId) ) {
                tinyMCE.execCommand('mceRemoveControl', false, edId);
            }
        }
    }
    

    function simple_fields_get_fieldID_from_this(t) {
        var $t = $(t);
        return $t.closest(".simple-fields-field-group-one-field").find(".simple-fields-field-group-one-field-id").val();
    }



    $(".simple-fields-metabox-field-file-select").live("click", function() {
        var input = $(this).closest(".simple-fields-metabox-field").find(".simple-fields-metabox-field-file-fileID");
        simple_fields_metabox_field_file_select_input_selectedID = input;
    });
    
    $(".simple-fields-file-browser-file-select").live("click", function() {
        var file_id = $(this).closest("li").find("input[name='simple-fields-file-browser-list-file-id']").val();
        var file_thumb = $(this).closest("li").find(".thumbnail img").attr("src");
        var file_name = $(this).closest("li").find("h3").text();
        self.parent.simple_fields_metabox_file_select(file_id, file_thumb, file_name);
        self.parent.tb_remove();
    });

    $(".simple-fields-metabox-field-file-clear").live("click", function() {
        var $li = $(this).closest(".simple-fields-metabox-field-file");
        $li.find(".simple-fields-metabox-field-file-fileID").val("");
        $li.find(".simple-fields-metabox-field-file-selected-image").text("");
        $li.find(".simple-fields-metabox-field-file-selected-image-name").text("");
        return false;
    });

    // media buttons
    $(".simple_fields_tiny_media_button").live("click", function(){
        var id = $(this).closest(".simple-fields-metabox-field").find("textarea").attr("id");
        simple_fields_focusTextArea(id);
        simple_fields_thickbox($(this).get(0));
        return false;
    });
    
    
    /**
     * ondomready stuff
     */
    $(function() {

        // attach TinyMCE to textareas
        simple_fields_metabox_tinymce_attach();
        
        // Media browser: make sure search and filter works by adding hidden inputs
        // would have been best to do this in PHP, but I can't find any filter for it
        if ( pagenow == "media-upload-popup" && window.location.search.match(/simple_fields_dummy=/) ) {

            var frm_filter = $("form#filter");
            
            // http://localhost/wp-admin/media-upload.php?simple_fields_dummy=1&simple_fields_action=select_file&simple_fields_file_field_unique_id=simple_fields_fieldgroups_12_1_0&post_id=-1&
            // get these
            // simple_fields_dummy=1
            // simple_fields_action=select_file
            // simple_fields_file_field_unique_id=simple_fields_fieldgroups_12_1_0
            var params = {
                "simple_fields_dummy": 1,
                "simple_fields_action": "select_file"
            }
            
            var match = window.location.search.match(/simple_fields_file_field_unique_id=([\w]+)/);
            params.simple_fields_file_field_unique_id = match[1];
            
            // all params that start with "simple_fields_"
            $.each(params, function(key, val) {
                frm_filter.append("<input type='hidden' name='"+key+"' value='"+val+"' />");
            }); 
        }
        
      $(".addButton").live("click", function () {
            var inputDiv="#" + $(this).closest('div').attr("id");
            var inputId=$(this).closest('div').find('input').attr("name");
           
            var newTextBoxDiv = $(document.createElement('div'))
                .attr("id", 'TextBoxDiv' + counter);

            newTextBoxDiv.html('<input type="text" name="' + inputId + '" id="textbox' + counter + 
                    '" value="" class="text">');

            newTextBoxDiv.appendTo(inputDiv);
            counter++;
      });

     $(".removeButton").live("click", function () {
        counter--;
        $("#TextBoxDiv" + counter).remove();

     });

     $("#getButtonValue").click(function () {

        var msg = '';
        for(i=1; i<counter; i++){
            msg += "\n Textbox #" + i + " : " + $('#textbox' + i).val();
        }
       alert(msg);
     });
        
        
    });

}(jQuery));

// global js stuff; sorry about that...
var simple_fields_metabox_field_file_select_input_selectedID = null;
var simple_fields_is_simple_fields_popup = false;
var counter = 2;

// called when selecting file from tiny-area, if I remember correct
function simple_fields_metabox_file_select(file_id, file_thumb, file_name) {
    simple_fields_metabox_field_file_select_input_selectedID.val(file_id);
    $file_thumb_tag = jQuery("<img src='"+file_thumb+"' alt='' />");
    simple_fields_metabox_field_file_select_input_selectedID.closest(".simple-fields-metabox-field").find(".simple-fields-metabox-field-file-selected-image").html($file_thumb_tag);
    simple_fields_metabox_field_file_select_input_selectedID.closest(".simple-fields-metabox-field").find(".simple-fields-metabox-field-file-selected-image-name").text(file_name);
    simple_fields_metabox_field_file_select_input_selectedID.closest(".simple-fields-metabox-field").effect("highlight", 4000);
    
}
// simple-fields-metabox-field-file

$j = jQuery.noConflict();

$j(document).ready(function() {

    var obj_id = '';

        $j('.header-banner,.header-logo,.layout-background').click(function() {
            obj_id = $j(this).attr('id');
            obj_id = '#' + obj_id.replace( /(:|\.|\[|\])/g, "\\$1" );
            tb_show('Upload a Image', 'media-upload.php?referer=media_page&type=image&TB_iframe=true&width=755&post_id=0', false);
            
            window.send_to_editor = function(html) {
                var hostname = window.location.hostname;
                var src = $j(html).attr('src');
                var image_url = (src !== undefined) ? src : $j('img', html).attr('src');
                if (image_url.indexOf(hostname) != -1) {
                    image_url = image_url.replace(/https?:\/\/[^\/]+/i, '');
                }
                $j(obj_id).val(image_url);
                tb_remove();
            }

            return false;
        });
        
});
