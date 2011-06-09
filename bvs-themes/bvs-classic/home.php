<?php
/**
 * @package BVS
 * @subpackage Classic_Theme
 */
get_header();
$mlf_options = get_option('mlf_config');
//print_r($mlf_options);
$current_language = get_bloginfo('language');
?>

<?php if ( is_active_sidebar( 'vhl_column_1_' . $current_language ) ) : ?>
                <div id="first" class="widget-area">
                    <ul class="xoxo">
                        <?php dynamic_sidebar(  'vhl_column_1_' . $current_language ); ?>
                    </ul>
                </div><!-- /#first .vhl_column_1 -->
<?php endif; ?>



<?php get_footer();?>
