<?php
/*
Template Name: Latest news
*/
__('Latest news', 'vhl');

get_header();
?>

<section id="primary" class="site-content category">
    <div id="content" role="main">
<?php
    $paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

    $args = array(
        'posts_per_page' => 10,
        'paged' => $paged,
        'orderby' => 'date',
        'order'   => 'DESC'
    );

    $the_query = new WP_Query( $args );
?>
<?php if ( $the_query->have_posts() ) : ?>
        <header class="archive-header">
            <h1 class="archive-title"><?php the_title(); ?></h1>
        </header>
    <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
        <header class="entry-header">
            <?php the_post_thumbnail( 'category-thumb' ); ?>
            <div class="category-post">
                        <h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
                        <?php the_excerpt(); ?>
            </div>
        </header>
        <hr />
    <?php endwhile; ?>
        <div class="paginate-links">
            <?php
                $big = 999999999; // need an unlikely integer

                echo paginate_links( array(
                    'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                    'format' => '?paged=%#%',
                    'current' => max( 1, get_query_var('paged') ),
                    'total' => $the_query->max_num_pages
                ) );
            ?>
        </div>
<?php endif; ?>
    </div>
</section>

<?php
wp_reset_postdata();
get_sidebar();
get_footer();
?>
