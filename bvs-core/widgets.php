<?php

include_once(BVS_PLUGIN_PATH . '/bvs-widgets/vhl_collection_widget.php');
include_once(BVS_PLUGIN_PATH . '/bvs-widgets/vhl_network_widget.php');
include_once(BVS_PLUGIN_PATH . '/bvs-widgets/vhl_search_widget.php');
include_once(BVS_PLUGIN_PATH . '/bvs-widgets/vhl_certification_widget.php');
include_once(BVS_PLUGIN_PATH . '/bvs-widgets/vhl_themes_widget.php');
include_once(BVS_PLUGIN_PATH . '/bvs-widgets/vhl_clusters_widget.php');

function register_vhl_widgets() {
    register_widget("VHL_Collection_Widget");
    register_widget("VHL_Network_Widget");
    register_widget("VHL_Search_Widget");
    register_widget("VHL_Certification_Widget");
    register_widget("VHL_Themes_Widget");
    register_widget("VHL_Clusters_Widget");
}

add_action('widgets_init', 'register_vhl_widgets');

?>
