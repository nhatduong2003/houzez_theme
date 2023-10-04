<?php
/**
 * Template Name: Template all Agents v1
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 09/02/16
 * Time: 4:03 PM
 */
get_header();

$agents_layout = houzez_option('agents-template-layout', 'v1');
$agent_header_search = houzez_option('agent_header_search', 1);

if( isset( $_GET['agents-layout'] ) && $_GET['agents-layout'] != "" ) {
    $agents_layout = esc_html($_GET['agents-layout']);
}

if( $agent_header_search ) {
    get_template_part('template-parts/realtors/agent/agent-search');
}
get_template_part('template-parts/realtors/agent/layout', $agents_layout);

if( $agent_header_search ) {
    get_template_part('template-parts/realtors/agent/mobile-agent-search-overlay');
}

get_footer(); ?>
