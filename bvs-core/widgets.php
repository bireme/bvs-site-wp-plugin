<?php

include_once(BVS_PATH . '/bvs-widgets/vhl_collection_widget.php');
include_once(BVS_PATH . '/bvs-widgets/vhl_network_widget.php');

function register_vhl_widgets() {
    register_widget("VHL_Collection_Widget");
    register_widget("VHL_Network_Widget");
}

add_action('widgets_init', 'register_vhl_widgets');

?>
