<?php
/**
 * @package WordPress
 * @subpackage Classic_Theme
 */

automatic_feed_links();

if ( function_exists('register_sidebar') )
    register_sidebar(
        array('name'=>'Coluna 1',
            'id' => 'column_1',
            'description' => __('Rede Social da BVS', 'example'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h2 class="widgettitle">',
            'after_title' => '</h2>',
        ));
    register_sidebar(
        array('name'=>'Coluna 2', 
            'id' => 'column_2',
            'description' => __('Rede de Conteúdos da BVS', 'example'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h2 class="widgettitle">',
            'after_title' => '</h2>',
        ));
    register_sidebar(
        array('name'=>'Coluna 3', 
            'id' => 'column_3',
            'description' => __('Rede de Notícias', 'example'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h2 class="widgettitle">',
            'after_title' => '</h2>',
        ));

       
?>
