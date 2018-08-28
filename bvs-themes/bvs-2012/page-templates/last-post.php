<?php
/*
Template Name: Latest news
*/

get_header(); 

$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

$args = array(
	'posts_per_page' => 10,
	'paged' => $paged,
	'orderby' => 'date',
	'order'   => 'DESC'

);

$the_query = new WP_Query( $args );

if ( $the_query->have_posts() ) :
	while ( $the_query->have_posts() ) :
		$the_query->the_post();
?>

<h2 class="entry-title">
    <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
</h2>
<div class="entry-content">

<?php the_excerpt(); ?>


</div>


<?php 
	endwhile;

$big = 999999999; // need an unlikely integer

echo paginate_links( array(
	'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	'format' => '?paged=%#%',
	'current' => max( 1, get_query_var('paged') ),
	'total' => $the_query->max_num_pages
) );


endif;

wp_reset_postdata();

get_sidebar();
get_footer(); 
?>