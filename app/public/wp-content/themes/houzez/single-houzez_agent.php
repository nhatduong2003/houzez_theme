<?php
get_header();

$agent_detail_layout = houzez_option('agent-detail-layout', 'v1');

if( isset( $_GET['single-agent-layout'] ) && $_GET['single-agent-layout'] != "" ) {
	$agent_detail_layout = esc_html($_GET['single-agent-layout']);
}

get_template_part( 'template-parts/realtors/agent/single-agent', $agent_detail_layout );

get_footer();
