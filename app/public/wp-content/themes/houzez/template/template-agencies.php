<?php
/**
 * Template Name: Template all agencies
 *
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 20/10/16
 * Time: 4:44 PM
 */
get_header();

$agencies_layout = houzez_option('agencies-template-layout', 'v1');

if( isset( $_GET['agencies-layout'] ) && $_GET['agencies-layout'] != "" ) {
    $agencies_layout = esc_html($_GET['agencies-layout']);
}

get_template_part('template-parts/realtors/agency/layout', $agencies_layout);

get_footer(); ?>