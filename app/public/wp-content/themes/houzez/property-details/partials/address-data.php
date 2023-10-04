<?php
global $hide_fields;
$hide_fields = array(
    'address' => 0,
    'city' => 0,
    'state' => 0,
    'zip' => 0,
    'area' => 0,
    'country' => 0
);
$address = houzez_get_listing_data('property_address');
$zipcode = houzez_get_listing_data('property_zip');

$country = houzez_taxonomy_simple('property_country');
$city = houzez_taxonomy_simple('property_city');
$state = houzez_taxonomy_simple('property_state');
$area = houzez_taxonomy_simple('property_area');

if( !empty($address) && $hide_fields['address'] != 1 ) {
    echo '<li class="detail-address"><strong>'.houzez_option('spl_address', 'Address').'</strong> <span>'.esc_attr( $address ).'</span></li>';
}
if( !empty( $city ) && $hide_fields['city'] != 1 ) {
    echo '<li class="detail-city"><strong>'.houzez_option( 'spl_city', 'City' ).'</strong> <span>'.esc_attr( $city ).'</span></li>';
}
if( !empty( $state ) && $hide_fields['state'] != 1 ) {
    echo '<li class="detail-state"><strong>'.houzez_option('spl_state', 'County/State').'</strong> <span>'.esc_attr( $state ).'</span></li>';
}
if( !empty($zipcode) && $hide_fields['zip'] != 1 ) {
    echo '<li class="detail-zip"><strong>'.houzez_option('spl_zip', 'Zip/Postal Code').'</strong> <span>'.esc_attr( $zipcode ).'</span></li>';
}
if( !empty( $area ) && $hide_fields['area'] != 1 ) {
    echo '<li class="detail-area"><strong>'.houzez_option( 'spl_area', 'Area' ).'</strong> <span>'.esc_attr( $area ).'</span></li>';
}
if( !empty($country) && $hide_fields['country'] != 1 ) {
    echo '<li class="detail-country"><strong>'.houzez_option('spl_country', 'Country').'</strong> <span>'.esc_attr($country).'</span></li>';
}