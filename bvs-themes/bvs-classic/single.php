<?php
/**
 * @package BVS
 * @subpackage Classic_Theme
 */
get_header();
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<div class="middle">
    <?php the_date('','<h2>','</h2>'); ?>

    <div <?php post_class() ?> id="post-<?php the_ID(); ?>">
         <h3 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h3>

        <div class="storycontent">
            <?php the_content(__('(more...)')); ?>
        </div>
		<div class="childPages">
			<ul>
			  <?php
				 global $id;
				 $post_type = get_post_type( $id );
				 wp_list_pages("post_type=" . $post_type. "&title_li=&child_of=" . $id);
			  ?>
			</ul>
		</div>
        <div class="feedback">
            <?php wp_link_pages(); ?>
            <?php comments_popup_link(__('Comments (0)'), __('Comments (1)'), __('Comments (%)')); ?>
        </div>

    </div>

    <?php comments_template(); // Get wp-comments.php template ?>

    <?php endwhile; else: ?>
    <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
    <?php endif; ?>

    <?php posts_nav_link(' &#8212; ', __('&laquo; Newer Posts'), __('Older Posts &raquo;')); ?>

</div>
<?php get_footer(); ?>
