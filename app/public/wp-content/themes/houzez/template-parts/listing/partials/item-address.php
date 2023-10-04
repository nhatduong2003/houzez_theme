<?php
$address_composer = houzez_option('listing_address_composer');
$enabled_data = isset($address_composer['enabled']) ? $address_composer['enabled'] : 0;
$temp_array = array();

echo '<address class="item-address">';

if ($enabled_data) {
	unset($enabled_data['placebo']);
	foreach ($enabled_data as $key=>$value) {

		
		if( $key == 'address' ) {
			$map_address = houzez_get_listing_data('property_map_address');

			if( $map_address != '' ) {
				$temp_array[] = $map_address;
			}

		} else if( $key == 'streat-address' ) {
			$property_address = houzez_get_listing_data('property_address');

			if( $property_address != '' ) {
				$temp_array[] = $property_address;
			}

		} else if( $key == 'country' ) {
			$country = houzez_taxonomy_simple('property_country');

			if( $country != '' ) {
				$temp_array[] = $country;
			}

		} else if( $key == 'state' ) {
			$state = houzez_taxonomy_simple('property_state');

			if( $state != '' ) {
				$temp_array[] = $state;
			}

		} else if( $key == 'city' ) {
			$city = houzez_taxonomy_simple('property_city');

			if( $city != '' ) {
				$temp_array[] = $city;
			}

		} else if( $key == 'area' ) {
			$area = houzez_taxonomy_simple('property_area');

			if( $area != '' ) {
				$temp_array[] = $area;
			}

		}
		

	}

	$result = join( ", ", $temp_array );
	echo $result;
}

echo '</address>';