<?php
global $property_data;

$features_terms_id = array();
if (houzez_edit_property()) {
    $features_terms = get_the_terms( $property_data->ID, 'property_feature' );
    if ( $features_terms && ! is_wp_error( $features_terms ) ) {
        foreach( $features_terms as $feature ) {
            $features_terms_id[] = intval( $feature->term_id );
        }
    }
}

$all_features  = houzez_build_features_array();
$single_feature =  '';
$output_html = '';
$has_child = false;

if( is_array($all_features) ) {

    foreach( $all_features as $key => $item ) {

        if( count( $item['childs']) > 0 ) {

            $inner_output =  '<div class="features_group_name col-lg-12">'.$item['name'].'</div>';

            $child_check = '';

            if( is_array($item['childs']) ){
                $i = 0;
                foreach($item['childs'] as $key_ch => $child) {

                    $child_term_id = $item['child_ids'][$i];
                    $temp   = houzez_feature_submit_output( $child, $child_term_id, $features_terms_id );
                    $inner_output .= $temp;
                    $child_check  .= $temp;

                    $i++;
                }
            }

            if( $child_check != '' ) {
                $has_child = true;
                $output_html .= $inner_output;
            }

        } else {
            $single_feature  .= houzez_feature_submit_output( $item['name'], $item['term_id'], $features_terms_id );
        }

    }

}
if( $single_feature !='' ) {
    if( $has_child ) {
        $output_html .= '<div class="features_group_name col-lg-12">'.esc_html__('Other Features','houzez').'</div>';
    }
    $output_html .= $single_feature;
}

echo $output_html;