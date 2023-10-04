<?php
global $property_features, $ele_settings;

$data_column_class = isset($ele_settings['data_columns']) && !empty($ele_settings['data_columns']) ? $ele_settings['data_columns'] : houzez_option('prop_features_cols', 'list-3-cols');

$all_features  = houzez_build_features_array();
$single_feature =  '';
$output_html = '';
$child_check = '';
$has_child = false;

if( is_array($all_features) ) {

    foreach( $all_features as $key => $item ) {

        if( count( $item['childs']) > 0 ) {

            $inner_output =  '<div class="features_group_name">'.$item['name'].'</div>';
            $inner_output .=  '<ul class="'.$data_column_class.' list-unstyled">';

            $child_check = '';

            if( is_array($item['childs']) ) {
                $i = 0;
                foreach($item['childs'] as $key_ch => $child) {

                    $child_term_id = $item['child_ids'][$i];
                    $temp   = houzez_feature_output( $child, $child_term_id, $property_features );
                    $inner_output .= $temp;
                    $child_check  .= $temp;

                    $i++;
                }
            }
            $inner_output .= '</ul>';

            if( $child_check != '' ) {
                $has_child = true;
                $output_html .= $inner_output;
            }

        } else {
            $single_feature  .= houzez_feature_output( $item['name'], $item['term_id'], $property_features );
        }

    }

}
if( $single_feature !='' ) {
    if( $has_child ) {
        $output_html .= '<div class="features_group_name">'.esc_html__('Other Features','houzez').'</div>';
    }
    $output_html .= '<ul class="'.$data_column_class.' list-unstyled">'.$single_feature .'</ul>';
}

echo $output_html;
