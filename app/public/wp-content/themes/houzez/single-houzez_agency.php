<?php
get_header();

$agency_detail_layout = houzez_option('agency-detail-layout', 'v1');

if( isset( $_GET['single-agency-layout'] ) && $_GET['single-agency-layout'] != "" ) {
	$agency_detail_layout = esc_html($_GET['single-agency-layout']);
}

get_template_part( 'template-parts/realtors/agency/single-agency', $agency_detail_layout );

get_footer();