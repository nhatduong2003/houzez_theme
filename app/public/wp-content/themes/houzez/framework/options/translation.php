<?php
global $houzez_opt_name, $allowed_html_array;

$houzez_local = houzez_get_localization();

Redux::setSection( $houzez_opt_name, array(
    'title'  => esc_html__( 'Translation', 'houzez' ),
    'id'     => 'labels-management',
    'desc'   => '',
    'icon'   => 'el-icon-home el-icon-small',
    'fields'        => array(
        
    )
));

Redux::setSection( $houzez_opt_name, array(
    'title'  => esc_html__( 'Common', 'houzez' ),
    'id'     => 'common-labels',
    'desc'   => '',
    'subsection'   => true,
    'fields'        => array(
        array(
            'id'       => 'cl_common_section-start',
            'type'     => 'section',
            'title'    => '',
            'subtitle' => esc_html__('Manage common strings accross site', 'houzez'),
            'indent'   => true,
        ),
        array(
            'id'       => 'cl_featured_label',
            'type'     => 'text',
            'title'    => esc_html__('Featured Label', 'houzez'),
            'desc'     => '',
            'subtitle' => '',
            'default' => esc_html__('Featured', 'houzez')
        ),
        array(
            'id'       => 'cl_property',
            'type'     => 'text',
            'title'    => esc_html__('Property', 'houzez'),
            'desc'     => '',
            'subtitle' => '',
            'default' => esc_html__('Property', 'houzez')
        ),
        array(
            'id'       => 'cl_properties',
            'type'     => 'text',
            'title'    => esc_html__('Properties', 'houzez'),
            'desc'     => '',
            'subtitle' => '',
            'default' => esc_html__('Properties', 'houzez')
        ),
        array(
            'id'       => 'cl_favorite',
            'type'     => 'text',
            'title'    => esc_html__('Favourite', 'houzez'),
            'desc'     => '',
            'subtitle' => '',
            'default' => esc_html__('Favourite', 'houzez')
        ),
        array(
            'id'       => 'cl_preview',
            'type'     => 'text',
            'title'    => esc_html__('Preview', 'houzez'),
            'desc'     => '',
            'subtitle' => '',
            'default' => esc_html__('Preview', 'houzez')
        ),
        array(
            'id'       => 'cl_add_compare',
            'type'     => 'text',
            'title'    => esc_html__('Add Compare', 'houzez'),
            'desc'     => '',
            'subtitle' => '',
            'default' => esc_html__('Add to Compare', 'houzez')
        ),
        array(
            'id'       => 'cl_remove_compare',
            'type'     => 'text',
            'title'    => esc_html__('Remove Compare', 'houzez'),
            'desc'     => '',
            'subtitle' => '',
            'default' => esc_html__('Remove from Compare', 'houzez')
        ),
        array(
            'id'       => 'cl_none',
            'type'     => 'text',
            'title'    => esc_html__('None Label', 'houzez'),
            'default' => esc_html__('None', 'houzez')
        ),
        array(
            'id'       => 'cl_select',
            'type'     => 'text',
            'title'    => esc_html__('Select Label', 'houzez'),
            'default' => esc_html__('Select', 'houzez')
        ),
        array(
            'id'       => 'cl_only_digits',
            'type'     => 'text',
            'title'    => esc_html__('Only digits Label', 'houzez'),
            'default' => esc_html__('Only digits', 'houzez')
        ),
        array(
            'id'       => 'cl_example',
            'type'     => 'text',
            'title'    => esc_html__('For Example Label', 'houzez'),
            'default' => esc_html__('For example', 'houzez')
        ),
        array(
            'id'       => 'cl_hide',
            'type'     => 'text',
            'title'    => esc_html__('Hide Label', 'houzez'),
            'default' => esc_html__('Hide', 'houzez')
        ),
        array(
            'id'       => 'cl_show',
            'type'     => 'text',
            'title'    => esc_html__('Show Label', 'houzez'),
            'default' => esc_html__('Show', 'houzez')
        ),
        array(
            'id'       => 'cl_yes',
            'type'     => 'text',
            'title'    => esc_html__('Yes Label', 'houzez'),
            'default' => esc_html__('Yes', 'houzez')
        ),
        array(
            'id'       => 'cl_no',
            'type'     => 'text',
            'title'    => esc_html__('No Label', 'houzez'),
            'default' => esc_html__('No', 'houzez')
        ),
        array(
            'id'       => 'cl_or',
            'type'     => 'text',
            'title'    => esc_html__('OR Label', 'houzez'),
            'default' => esc_html__('OR', 'houzez')
        ),
        array(
            'id'       => 'cl_select_all',
            'type'     => 'text',
            'title'    => esc_html__('Select All', 'houzez'),
            'default' => esc_html__('Select All', 'houzez')
        ),
        array(
            'id'       => 'cl_deselect_all',
            'type'     => 'text',
            'title'    => esc_html__('Deselect All', 'houzez'),
            'default' => esc_html__('Deselect All', 'houzez')
        ),
        array(
            'id'       => 'cl_no_results_matched',
            'type'     => 'text',
            'title'    => esc_html__('No results matched', 'houzez'),
            'default' => esc_html__('No results matched', 'houzez')
        ),
        array(
            'id'       => 'cl_common_section-end',
            'type'     => 'section',
            'indent'   => false,
        ),
    )
));

Redux::setSection( $houzez_opt_name, array(
    'title'  => esc_html__( 'Searches', 'houzez' ),
    'id'     => 'searches-labels',
    'desc'   => '',
    'subsection'   => true,
    'fields'        => array(
        array(
            'id'       => 'srh_labels_section-start',
            'type'     => 'section',
            'title'    => '',
            'subtitle' => esc_html__('Manage labels for searches accross the site', 'houzez'),
            'indent'   => true,
        ),

        array(
            'id'       => 'srh_item_selected',
            'type'     => 'text',
            'title'    => esc_html__('items selected', 'houzez'),
            'default' => esc_html__('items selected', 'houzez')
        ),
        array(
            'id'       => 'srh_any',
            'type'     => 'text',
            'title'    => esc_html__('Any', 'houzez'),
            'default' => esc_html__('Any', 'houzez')
        ),
        array(
            'id'       => 'srh_keyword',
            'type'     => 'text',
            'title'    => esc_html__('Keyword', 'houzez'),
            'default' => esc_html__('Enter Keyword...', 'houzez')
        ),
        array(
            'id'       => 'srh_address',
            'type'     => 'text',
            'title'    => esc_html__('Address, town, street, zip or property ID', 'houzez'),
            'default' => esc_html__('Enter an address, town, street, zip or property ID','houzez')
        ),

        array(
            'id'       => 'srh_csa',
            'type'     => 'text',
            'title'    => esc_html__('City, State or Area', 'houzez'),
            'default' => esc_html__('Search City, State or Area', 'houzez')
        ),
        array(
            'id'       => 'srh_location',
            'type'     => 'text',
            'title'    => esc_html__('Location', 'houzez'),
            'default' => esc_html__('Location', 'houzez')
        ),
        array(
            'id'       => 'srh_radius',
            'type'     => 'text',
            'title'    => esc_html__('Radius', 'houzez'),
            'default' => esc_html__('Radius', 'houzez')
        ),
        array(
            'id'       => 'srh_type',
            'type'     => 'text',
            'title'    => esc_html__('Type', 'houzez'),
            'default' => esc_html__('Type', 'houzez')
        ),
        array(
            'id'       => 'srh_types',
            'type'     => 'text',
            'title'    => esc_html__('types selected', 'houzez'),
            'default' => esc_html__('types selected', 'houzez')
        ),
        array(
            'id'       => 'srh_status',
            'type'     => 'text',
            'title'    => esc_html__('Status', 'houzez'),
            'default' => esc_html__('Status', 'houzez')
        ),
        array(
            'id'       => 'srh_statuses',
            'type'     => 'text',
            'title'    => esc_html__('statuses selected', 'houzez'),
            'default' => esc_html__('status selected', 'houzez')
        ),
        array(
            'id'       => 'srh_label',
            'type'     => 'text',
            'title'    => esc_html__('Label', 'houzez'),
            'default' => esc_html__('Label', 'houzez')
        ),
        array(
            'id'       => 'srh_labels',
            'type'     => 'text',
            'title'    => esc_html__('Labels', 'houzez'),
            'default' => esc_html__('Labels', 'houzez')
        ),

        array(
            'id'       => 'srh_all_status',
            'type'     => 'text',
            'title'    => esc_html__('All Status', 'houzez'),
            'default' => esc_html__('All Status', 'houzez')
        ),
        array(
            'id'       => 'srh_bedrooms',
            'type'     => 'text',
            'title'    => esc_html__('Bedrooms', 'houzez'),
            'default' => esc_html__('Bedrooms', 'houzez')
        ),
        array(
            'id'       => 'srh_studio',
            'type'     => 'text',
            'title'    => esc_html__('Studio', 'houzez'),
            'default' => esc_html__('Studio', 'houzez')
        ),
        array(
            'id'       => 'srh_rooms',
            'type'     => 'text',
            'title'    => esc_html__('Rooms', 'houzez'),
            'default' => esc_html__('Rooms', 'houzez')
        ),
        array(
            'id'       => 'srh_bathrooms',
            'type'     => 'text',
            'title'    => esc_html__('Bathrooms', 'houzez'),
            'default' => esc_html__('Bathrooms', 'houzez')
        ),
        array(
            'id'       => 'srh_beds',
            'type'     => 'text',
            'title'    => esc_html__('Beds', 'houzez'),
            'default' => esc_html__('Beds', 'houzez')
        ),
        array(
            'id'       => 'srh_baths',
            'type'     => 'text',
            'title'    => esc_html__('Baths', 'houzez'),
            'default' => esc_html__('Baths', 'houzez')
        ),
        array(
            'id'       => 'srh_min_area',
            'type'     => 'text',
            'title'    => esc_html__('Min Area', 'houzez'),
            'default' => esc_html__('Min. Area', 'houzez')
        ),
        array(
            'id'       => 'srh_max_area',
            'type'     => 'text',
            'title'    => esc_html__('Max Area', 'houzez'),
            'default' => esc_html__('Max. Area', 'houzez')
        ),
        array(
            'id'       => 'srh_min_land_area',
            'type'     => 'text',
            'title'    => esc_html__('Min Land Area', 'houzez'),
            'default' => esc_html__('Min. Land Area', 'houzez')
        ),
        array(
            'id'       => 'srh_max_land_area',
            'type'     => 'text',
            'title'    => esc_html__('Max Land Area', 'houzez'),
            'default' => esc_html__('Max. Land Area', 'houzez')
        ),
        array(
            'id'       => 'srh_min_price',
            'type'     => 'text',
            'title'    => esc_html__('Min Price', 'houzez'),
            'default' => esc_html__('Min. Price', 'houzez')
        ),
        array(
            'id'       => 'srh_max_price',
            'type'     => 'text',
            'title'    => esc_html__('Max Price', 'houzez'),
            'default' => esc_html__('Max. Price', 'houzez')
        ),
        array(
            'id'       => 'srh_price',
            'type'     => 'text',
            'title'    => esc_html__('Price', 'houzez'),
            'default' => esc_html__('Price', 'houzez')
        ),
        array(
            'id'       => 'srh_price_range',
            'type'     => 'text',
            'title'    => esc_html__('Price Range', 'houzez'),
            'default' => esc_html__('Price Range', 'houzez')
        ),
        array(
            'id'       => 'srh_from',
            'type'     => 'text',
            'title'    => esc_html__('From', 'houzez'),
            'default' => esc_html__('From', 'houzez')
        ),
        array(
            'id'       => 'srh_to',
            'type'     => 'text',
            'title'    => esc_html__('To', 'houzez'),
            'default' => esc_html__('To', 'houzez')
        ),
        array(
            'id'       => 'srh_prop_id',
            'type'     => 'text',
            'title'    => esc_html__('Property ID', 'houzez'),
            'default' => esc_html__('Property ID', 'houzez')
        ),
        array(
            'id'       => 'srh_countries',
            'type'     => 'text',
            'title'    => esc_html__('All Countries', 'houzez'),
            'default' => esc_html__('All Countries', 'houzez')
        ),
        array(
            'id'       => 'srh_states',
            'type'     => 'text',
            'title'    => esc_html__('All States', 'houzez'),
            'default' => esc_html__('All States', 'houzez')
        ),
        array(
            'id'       => 'srh_cities',
            'type'     => 'text',
            'title'    => esc_html__('All Cities', 'houzez'),
            'default' => esc_html__('All Cities', 'houzez')
        ),
        array(
            'id'       => 'srh_areas',
            'type'     => 'text',
            'title'    => esc_html__('All Areas', 'houzez'),
            'default' => esc_html__('All Areas', 'houzez')
        ),
        array(
            'id'       => 'srh_areass',
            'type'     => 'text',
            'title'    => esc_html__('Areas Selected', 'houzez'),
            'default' => esc_html__('areas selected', 'houzez')
        ),
        array(
            'id'       => 'srh_cities_selected',
            'type'     => 'text',
            'title'    => esc_html__('Cities Selected', 'houzez'),
            'default' => esc_html__('cities selected', 'houzez')
        ),

        array(
            'id'       => 'srh_garage',
            'type'     => 'text',
            'title'    => esc_html__('Garage', 'houzez'),
            'default' => esc_html__('Garage', 'houzez')
        ),

        array(
            'id'       => 'srh_year_built',
            'type'     => 'text',
            'title'    => esc_html__('Year Built', 'houzez'),
            'default' => esc_html__('Year Built', 'houzez')
        ),

        array(
            'id'       => 'srh_currency',
            'type'     => 'text',
            'title'    => esc_html__('Currency', 'houzez'),
            'default' => esc_html__('Currency', 'houzez')
        ),

        array(
            'id'       => 'srh_other_features',
            'type'     => 'text',
            'title'    => esc_html__('Other Features', 'houzez'),
            'default' => esc_html__('Other Features', 'houzez')
        ),

        array(
            'id'       => 'srh_btn_adv',
            'type'     => 'text',
            'title'    => esc_html__('Advanced Button', 'houzez'),
            'default' => esc_html__('Advanced', 'houzez')
        ),
        array(
            'id'       => 'srh_btn_search',
            'type'     => 'text',
            'title'    => esc_html__('Search Button', 'houzez'),
            'default' => esc_html__('Search', 'houzez')
        ),
        array(
            'id'       => 'srh_btn_go',
            'type'     => 'text',
            'title'    => esc_html__('Go Button', 'houzez'),
            'default' => esc_html__('Go', 'houzez')
        ),
        array(
            'id'       => 'srh_btn_save_search',
            'type'     => 'text',
            'title'    => esc_html__('Save Search Button', 'houzez'),
            'default' => esc_html__('Save Search', 'houzez')
        ),

        array(
            'id'       => 'srh_dock_title',
            'type'     => 'text',
            'title'    => esc_html__('Dock Search Main Title', 'houzez'),
            'default' => esc_html__('Advanced Search', 'houzez')
        ),

        array(
            'id'       => 'srh_mobile_title',
            'type'     => 'text',
            'title'    => esc_html__('Mobile Search Placeholder', 'houzez'),
            'default' => esc_html__('Search', 'houzez')
        ),

        array(
            'id'       => 'srh_btn_more',
            'type'     => 'text',
            'title'    => esc_html__('More Options Button', 'houzez'),
            'default' => esc_html__('More Options', 'houzez')
        ),
        array(
            'id'       => 'srh_clear',
            'type'     => 'text',
            'title'    => esc_html__('Clear', 'houzez'),
            'default' => esc_html__('Clear', 'houzez')
        ),
        array(
            'id'       => 'srh_apply',
            'type'     => 'text',
            'title'    => esc_html__('Apply', 'houzez'),
            'default' => esc_html__('Apply', 'houzez')
        ),

        array(
            'id'       => 'srh_labels_section-end',
            'type'     => 'section',
            'indent'   => false,
        ),
    )
));

Redux::setSection( $houzez_opt_name, array(
    'title'  => esc_html__( 'Grid, List, Card & Preview', 'houzez' ),
    'id'     => 'glcp-translation',
    'desc'   => esc_html__( 'Manage titles for listings Grid, List, Card and Preview views', 'houzez' ),
    'subsection'   => true,
    'fields'        => array(
        
        /*--------------------------------------------------------------
        * Grid, list, card and preview
        **------------------------------------------------------------*/
        array(
            'id'       => 'cl_glcp_section-start',
            'type'     => 'section',
            'indent'   => true,
        ),

        array(
            'id'       => 'glc_bedroom',
            'type'     => 'text',
            'title'    => esc_html__('Bedroom Label', 'houzez'),
            'default' => esc_html__('Bedroom', 'houzez')
        ),
        array(
            'id'       => 'glc_bedrooms',
            'type'     => 'text',
            'title'    => esc_html__('Bedrooms Label', 'houzez'),
            'default' => esc_html__('Bedrooms', 'houzez')
        ),
        array(
            'id'       => 'glc_bathroom',
            'type'     => 'text',
            'title'    => esc_html__('Bathroom Label', 'houzez'),
            'default' => esc_html__('Bathroom', 'houzez')
        ),
        array(
            'id'       => 'glc_bathrooms',
            'type'     => 'text',
            'title'    => esc_html__('Bathrooms Label', 'houzez'),
            'default' => esc_html__('Bathrooms', 'houzez')
        ),
        array(
            'id'       => 'glc_bed',
            'type'     => 'text',
            'title'    => esc_html__('Bed Label', 'houzez'),
            'default' => esc_html__('Bed', 'houzez')
        ),
        array(
            'id'       => 'glc_beds',
            'type'     => 'text',
            'title'    => esc_html__('Beds Label', 'houzez'),
            'default' => esc_html__('Beds', 'houzez')
        ),
        array(
            'id'       => 'glc_room',
            'type'     => 'text',
            'title'    => esc_html__('Room Label', 'houzez'),
            'default' => esc_html__('Room', 'houzez')
        ),
        array(
            'id'       => 'glc_rooms',
            'type'     => 'text',
            'title'    => esc_html__('Rooms Label', 'houzez'),
            'default' => esc_html__('Rooms', 'houzez')
        ),
        array(
            'id'       => 'glc_bath',
            'type'     => 'text',
            'title'    => esc_html__('Bath Label', 'houzez'),
            'default' => esc_html__('Bath', 'houzez')
        ),
        array(
            'id'       => 'glc_baths',
            'type'     => 'text',
            'title'    => esc_html__('Baths Label', 'houzez'),
            'default' => esc_html__('Baths', 'houzez')
        ),
        array(
            'id'       => 'glc_garage',
            'type'     => 'text',
            'title'    => esc_html__('Garage Label', 'houzez'),
            'default' => esc_html__('Garage', 'houzez')
        ),
        array(
            'id'       => 'glc_garages',
            'type'     => 'text',
            'title'    => esc_html__('Garages Label', 'houzez'),
            'default' => esc_html__('Garages', 'houzez')
        ),
        array(
            'id'       => 'glc_year_built',
            'type'     => 'text',
            'title'    => esc_html__('Year Built Label', 'houzez'),
            'default' => esc_html__('Year Built', 'houzez')
        ),
        array(
            'id'       => 'glc_id',
            'type'     => 'text',
            'title'    => esc_html__('ID Label', 'houzez'),
            'default' => esc_html__('ID', 'houzez')
        ),
        array(
            'id'       => 'glc_listing_id',
            'type'     => 'text',
            'title'    => esc_html__('Listing ID Label', 'houzez'),
            'default' => esc_html__('Listing ID', 'houzez')
        ),
        array(
            'id'       => 'glc_detail_btn',
            'type'     => 'text',
            'title'    => esc_html__('Details Button Label', 'houzez'),
            'default' => esc_html__('Details', 'houzez')
        ),
        array(
            'id'       => 'cl_glcp_section-end',
            'type'     => 'section',
            'indent'   => false,
        ),
        
    )
));

Redux::setSection( $houzez_opt_name, array(
    'title'  => esc_html__( 'Property Detail Page', 'houzez' ),
    'id'     => 'property-details-labels',
    'desc'   => esc_html__( 'Manage titles for property detail page.', 'houzez' ),
    'subsection'   => true,
    'fields'        => array(
        
        /*--------------------------------------------------------------
        * Property detail and create listing section titles
        **------------------------------------------------------------*/
        array(
            'id'       => 'sp_sections_section-start',
            'type'     => 'section',
            'title'    => esc_html__('Sections Titles', 'houzez'),
            'subtitle' => esc_html__('Manage Single Property page section titles', 'houzez'),
            'indent'   => true,
        ),

        array(
            'id'       => 'sps_overview',
            'type'     => 'text',
            'title'    => esc_html__('Overview title', 'houzez'),
            'default' => esc_html__('Overview', 'houzez')
        ),

        array(
            'id'       => 'sps_description',
            'type'     => 'text',
            'title'    => esc_html__('Description title', 'houzez'),
            'default' => esc_html__('Description', 'houzez')
        ),

        array(
            'id'       => 'sps_documents',
            'type'     => 'text',
            'title'    => esc_html__('Property Documents title', 'houzez'),
            'default' => esc_html__('Property Documents', 'houzez')
        ),

        array(
            'id'       => 'sps_details',
            'type'     => 'text',
            'title'    => esc_html__('Details title', 'houzez'),
            'default' => esc_html__('Details', 'houzez')
        ),
        array(
            'id'       => 'sps_additional_details',
            'type'     => 'text',
            'title'    => esc_html__('Additional details title', 'houzez'),
            'default' => esc_html__('Additional details', 'houzez')
        ),
        array(
            'id'       => 'sps_address',
            'type'     => 'text',
            'title'    => esc_html__('Address title', 'houzez'),
            'default' => esc_html__('Address', 'houzez')
        ),
        array(
            'id'       => 'sps_features',
            'type'     => 'text',
            'title'    => esc_html__('Features title', 'houzez'),
            'default' => esc_html__('Features', 'houzez')
        ),
        array(
            'id'       => 'sps_video',
            'type'     => 'text',
            'title'    => esc_html__('Video title', 'houzez'),
            'default' => esc_html__('Video', 'houzez')
        ),
        array(
            'id'       => 'sps_virtual_tour',
            'type'     => 'text',
            'title'    => esc_html__('360° Virtual Tour title', 'houzez'),
            'default' => esc_html__('360° Virtual Tour', 'houzez')
        ),

        array(
            'id'       => 'sps_sub_listings',
            'type'     => 'text',
            'title'    => esc_html__('Sub listings title', 'houzez'),
            'default' => esc_html__('Sub listings', 'houzez')
        ),
        array(
            'id'       => 'sps_energy_class',
            'type'     => 'text',
            'title'    => esc_html__('Energy Class title', 'houzez'),
            'default' => esc_html__('Energy Class', 'houzez')
        ),
        array(
            'id'       => 'sps_floor_plans',
            'type'     => 'text',
            'title'    => esc_html__('Floor Plans title', 'houzez'),
            'default' => esc_html__('Floor Plans', 'houzez')
        ),
        array(
            'id'       => 'sps_calculator',
            'type'     => 'text',
            'title'    => esc_html__('Mortgage Calculator title', 'houzez'),
            'default' => esc_html__('Mortgage Calculator', 'houzez')
        ),
        array(
            'id'       => 'sps_walkscore',
            'type'     => 'text',
            'title'    => esc_html__('Walkscore title', 'houzez'),
            'default' => esc_html__('Walkscore', 'houzez')
        ),
        array(
            'id'       => 'sps_nearby',
            'type'     => 'text',
            'title'    => esc_html__("What's Nearby? title", 'houzez'),
            'default' => esc_html__("What's Nearby?", 'houzez')
        ),
        array(
            'id'       => 'sps_schedule_tour',
            'type'     => 'text',
            'title'    => esc_html__("Schedule a Tour title", 'houzez'),
            'default' => esc_html__("Schedule a Tour", 'houzez')
        ),

        array(
            'id'       => 'sps_contact',
            'type'     => 'text',
            'title'    => esc_html__("Contact title", 'houzez'),
            'default' => esc_html__("Contact", 'houzez')
        ),

        array(
            'id'       => 'sps_contact_info',
            'type'     => 'text',
            'title'    => esc_html__("Contact Information title", 'houzez'),
            'default' => esc_html__("Contact Information", 'houzez')
        ),

        array(
            'id'       => 'sps_your_info',
            'type'     => 'text',
            'title'    => esc_html__("Your information title", 'houzez'),
            'default' => esc_html__("Your information", 'houzez')
        ),

        array(
            'id'       => 'sps_propperty_enqry',
            'type'     => 'text',
            'title'    => esc_html__("Enquire About This Property title", 'houzez'),
            'default' => esc_html__("Enquire About This Property", 'houzez')
        ),

        array(
            'id'       => 'sps_reviews',
            'type'     => 'text',
            'title'    => esc_html__("Reviews title", 'houzez'),
            'default' => esc_html__("Reviews", 'houzez')
        ),
        array(
            'id'       => 'sps_similar_listings',
            'type'     => 'text',
            'title'    => esc_html__("Similar Listings title", 'houzez'),
            'default' => esc_html__("Similar Listings", 'houzez')
        ),

        array(
            'id'       => 'sp_sections_section-end',
            'type'     => 'section',
            'indent'   => false,
        ),
        /*------------------------------- Detail page labels ---------------------------------------*/
        array(
            'id'       => 'sp_labels_section-start',
            'type'     => 'section',
            'title'    => esc_html__('Property Detail Labels', 'houzez'),
            'subtitle' => esc_html__('Manage property detail page labels', 'houzez'),
            'indent'   => true,
        ),

        array(
            'id'       => 'spl_prop_id',
            'type'     => 'text',
            'title'    => esc_html__("Property ID", 'houzez'),
            'default' => esc_html__("Property ID", 'houzez')
        ),
        array(
            'id'       => 'spl_price',
            'type'     => 'text',
            'title'    => esc_html__('Price', 'houzez'),
            'default' => esc_html__("Price", 'houzez')
        ),

        array(
            'id'       => 'spl_prop_type',
            'type'     => 'text',
            'title'    => esc_html__("Property Type", 'houzez'),
            'default' => esc_html__("Property Type", 'houzez')
        ),
        array(
            'id'       => 'spl_prop_status',
            'type'     => 'text',
            'title'    => esc_html__("Property Status", 'houzez'),
            'default' => esc_html__("Property Status", 'houzez')
        ),
        array(
            'id'       => 'spl_prop_size',
            'type'     => 'text',
            'title'    => esc_html__("Property Size", 'houzez'),
            'default' => esc_html__("Property Size", 'houzez')
        ),
        array(
            'id'       => 'spl_land',
            'type'     => 'text',
            'title'    => esc_html__("Land Area", 'houzez'),
            'default' => esc_html__("Land Area", 'houzez')
        ),
        array(
            'id'       => 'spl_room',
            'type'     => 'text',
            'title'    => esc_html__('Room Label', 'houzez'),
            'default' => esc_html__('Room', 'houzez')
        ),
        array(
            'id'       => 'spl_rooms',
            'type'     => 'text',
            'title'    => esc_html__('Rooms Label', 'houzez'),
            'default' => esc_html__('Rooms', 'houzez')
        ),
        array(
            'id'       => 'spl_bedroom',
            'type'     => 'text',
            'title'    => esc_html__('Bedroom Label', 'houzez'),
            'default' => esc_html__('Bedroom', 'houzez')
        ),
        array(
            'id'       => 'spl_bedrooms',
            'type'     => 'text',
            'title'    => esc_html__('Bedrooms Label', 'houzez'),
            'default' => esc_html__('Bedrooms', 'houzez')
        ),
        array(
            'id'       => 'spl_bathroom',
            'type'     => 'text',
            'title'    => esc_html__('Bathroom Label', 'houzez'),
            'default' => esc_html__('Bathroom', 'houzez')
        ),
        array(
            'id'       => 'spl_bathrooms',
            'type'     => 'text',
            'title'    => esc_html__('Bathrooms Label', 'houzez'),
            'default' => esc_html__('Bathrooms', 'houzez')
        ),
        array(
            'id'       => 'spl_garage',
            'type'     => 'text',
            'title'    => esc_html__('Garage Label', 'houzez'),
            'default' => esc_html__('Garage', 'houzez')
        ),
        array(
            'id'       => 'spl_garages',
            'type'     => 'text',
            'title'    => esc_html__('Garages Label', 'houzez'),
            'default' => esc_html__('Garages', 'houzez')
        ),
        array(
            'id'       => 'spl_garage_size',
            'type'     => 'text',
            'title'    => esc_html__('Garage Size Label', 'houzez'),
            'default' => esc_html__('Garage Size', 'houzez')
        ),
        array(
            'id'       => 'spl_year_built',
            'type'     => 'text',
            'title'    => esc_html__('Year Built Label', 'houzez'),
            'default' => esc_html__('Year Built', 'houzez')
        ),
        array(
            'id'       => 'spl_lot',
            'type'     => 'text',
            'title'    => esc_html__('Lot', 'houzez'),
            'default' => esc_html__('Lot', 'houzez')
        ),
        array(
            'id'       => 'spl_ogm',
            'type'     => 'text',
            'title'    => esc_html__('Open on Google Maps', 'houzez'),
            'default' => esc_html__('Open on Google Maps', 'houzez')
        ),

        array(
            'id'       => 'spl_address',
            'type'     => 'text',
            'title'    => esc_html__('Address Label', 'houzez'),
            'default' => esc_html__('Address', 'houzez')
        ),
        array(
            'id'       => 'spl_zip',
            'type'     => 'text',
            'title'    => esc_html__('Zip/Postal Code Label', 'houzez'),
            'default' => esc_html__('Zip/Postal Code', 'houzez')
        ),
        array(
            'id'       => 'spl_country',
            'type'     => 'text',
            'title'    => esc_html__('Country Label', 'houzez'),
            'default' => esc_html__('Country', 'houzez')
        ),
        array(
            'id'       => 'spl_state',
            'type'     => 'text',
            'title'    => esc_html__('State/county Label', 'houzez'),
            'default' => esc_html__('State/county', 'houzez')
        ),
        array(
            'id'       => 'spl_city',
            'type'     => 'text',
            'title'    => esc_html__('City Label', 'houzez'),
            'default' => esc_html__('City', 'houzez')
        ),
        array(
            'id'       => 'spl_area',
            'type'     => 'text',
            'title'    => esc_html__('Area Label', 'houzez'),
            'default' => esc_html__('Area', 'houzez')
        ),

        array(
            'id'       => 'sp_labels_section-end',
            'type'     => 'section',
            'indent'   => false,
        ),

        array(
            'id'       => 'sp_agent_forms_section-start',
            'type'     => 'section',
            'title'    => esc_html__('Contact Forms', 'houzez'),
            'subtitle' => esc_html__('Manage labels for agent contact forms and schedule tour', 'houzez'),
            'indent'   => true,
        ),

        array(
            'id'       => 'spl_con_name',
            'type'     => 'text',
            'title'    => esc_html__("Name", 'houzez'),
            'default' => esc_html__("Name", 'houzez')
        ),
        array(
            'id'       => 'spl_con_name_plac',
            'type'     => 'text',
            'title'    => esc_html__("Name Placeholder", 'houzez'),
            'default' => esc_html__("Enter your name", 'houzez')
        ),

        array(
            'id'       => 'spl_con_phone',
            'type'     => 'text',
            'title'    => esc_html__("Phone", 'houzez'),
            'default' => esc_html__("Phone", 'houzez')
        ),
        array(
            'id'       => 'spl_con_phone_plac',
            'type'     => 'text',
            'title'    => esc_html__("Phone Placeholder", 'houzez'),
            'default' => esc_html__("Enter your Phone", 'houzez')
        ),

        array(
            'id'       => 'spl_con_email',
            'type'     => 'text',
            'title'    => esc_html__("Email", 'houzez'),
            'default' => esc_html__("Email", 'houzez')
        ),
        array(
            'id'       => 'spl_con_email_plac',
            'type'     => 'text',
            'title'    => esc_html__("Email Placeholder", 'houzez'),
            'default' => esc_html__("Enter your email", 'houzez')
        ),

        array(
            'id'       => 'spl_con_message',
            'type'     => 'text',
            'title'    => esc_html__("Message", 'houzez'),
            'default' => esc_html__("Message", 'houzez')
        ),
        array(
            'id'       => 'spl_con_message_plac',
            'type'     => 'text',
            'title'    => esc_html__("Message Placeholder", 'houzez'),
            'default' => esc_html__("Enter your Message", 'houzez')
        ),

        array(
            'id'       => 'spl_con_interested',
            'type'     => 'text',
            'title'    => esc_html__("Message Default Prefix", 'houzez'),
            'default' => esc_html__("Hello, I am interested in", 'houzez')
        ),

        array(
            'id'       => 'spl_con_usertype',
            'type'     => 'text',
            'title'    => esc_html__("I'm a", 'houzez'),
            'default' => esc_html__("I'm a", 'houzez')
        ),
        
        array(
            'id'       => 'spl_con_select',
            'type'     => 'text',
            'title'    => esc_html__("Select", 'houzez'),
            'default' => esc_html__("Select", 'houzez')
        ),

        array(
            'id'       => 'spl_con_buyer',
            'type'     => 'text',
            'title'    => esc_html__("I'm a buyer", 'houzez'),
            'default' => esc_html__("I'm a buyer", 'houzez')
        ),

        array(
            'id'       => 'spl_con_tennant',
            'type'     => 'text',
            'title'    => esc_html__("I'm a tennant", 'houzez'),
            'default' => esc_html__("I'm a tennant", 'houzez')
        ),

        array(
            'id'       => 'spl_con_agent',
            'type'     => 'text',
            'title'    => esc_html__("I'm an agent", 'houzez'),
            'default' => esc_html__("I'm an agent", 'houzez')
        ),

        array(
            'id'       => 'spl_con_other',
            'type'     => 'text',
            'title'    => esc_html__("Other", 'houzez'),
            'default' => esc_html__("Other", 'houzez')
        ),

        array(
            'id'       => 'spl_con_view_listings',
            'type'     => 'text',
            'title'    => esc_html__("View Listings link", 'houzez'),
            'default' => esc_html__("View Listings", 'houzez')
        ),

        array(
            'id'       => 'spl_con_tour_type',
            'type'     => 'text',
            'title'    => esc_html__("Tour Type", 'houzez'),
            'default' => esc_html__("Tour Type", 'houzez')
        ),
        array(
            'id'       => 'spl_con_in_person',
            'type'     => 'text',
            'title'    => esc_html__("In Person", 'houzez'),
            'default' => esc_html__("In Person", 'houzez')
        ),
        array(
            'id'       => 'spl_con_video_chat',
            'type'     => 'text',
            'title'    => esc_html__("Video Chat", 'houzez'),
            'default' => esc_html__("Video Chat", 'houzez')
        ),
        array(
            'id'       => 'spl_con_date',
            'type'     => 'text',
            'title'    => esc_html__("Date", 'houzez'),
            'default' => esc_html__("Date", 'houzez')
        ),
        array(
            'id'       => 'spl_con_date_plac',
            'type'     => 'text',
            'title'    => esc_html__("Date Placeholder", 'houzez'),
            'default' => esc_html__("Select tour date", 'houzez')
        ),

        array(
            'id'       => 'spl_con_time',
            'type'     => 'text',
            'title'    => esc_html__("Time", 'houzez'),
            'default' => esc_html__("Time", 'houzez')
        ),

        array(
            'id'       => 'spl_btn_send',
            'type'     => 'text',
            'title'    => esc_html__("Send Email Button", 'houzez'),
            'default' => esc_html__("Send Email", 'houzez')
        ),
        array(
            'id'       => 'spl_btn_call',
            'type'     => 'text',
            'title'    => esc_html__("Call Button", 'houzez'),
            'default' => esc_html__("Call", 'houzez')
        ),

        array(
            'id'       => 'spl_btn_message',
            'type'     => 'text',
            'title'    => esc_html__("Send Message Button", 'houzez'),
            'default' => esc_html__("Send Message", 'houzez')
        ),

        array(
            'id'       => 'spl_btn_request_info',
            'type'     => 'text',
            'title'    => esc_html__("Request Information Button", 'houzez'),
            'default' => esc_html__("Request Information", 'houzez')
        ),
        array(
            'id'       => 'spl_btn_tour_sch',
            'type'     => 'text',
            'title'    => esc_html__("Submit a Tour Request Button", 'houzez'),
            'default' => esc_html__("Submit a Tour Request", 'houzez')
        ),

        array(
            'id'       => 'spl_sub_agree',
            'type'     => 'text',
            'title'    => esc_html__("By submitting this form I agree to", 'houzez'),
            'default' => esc_html__("By submitting this form I agree to", 'houzez')
        ),

        array(
            'id'       => 'spl_term',
            'type'     => 'text',
            'title'    => esc_html__("Terms of Use", 'houzez'),
            'default' => esc_html__("Terms of Use", 'houzez')
        ),

        array(
            'id'       => 'agent_forms_terms_validation',
            'type'     => 'text',
            'title'    => esc_html__( 'Terms of Use Checkbox Validation Message', 'houzez' ),
            'subtitle' => '',
            'default'  => esc_html__('Please accept terms of use', 'houzez')
        ),

        array(
            'id'       => 'sp_agent_forms_section-end',
            'type'     => 'section',
            'indent'   => false,
        ),

        /*------------------------------------------- Energy Detail page -------------------------*/
        array(
            'id'       => 'sp_energy_labels_section-start',
            'type'     => 'section',
            'title'    => esc_html__('Energy Class', 'houzez'),
            'subtitle' => esc_html__('Manage labels for energy class section', 'houzez'),
            'indent'   => true,
        ),

        array(
            'id'       => 'spl_energetic_cls',
            'type'     => 'text',
            'title'    => esc_html__("Energetic class", 'houzez'),
            'default' => esc_html__("Energetic class", 'houzez')
        ),
        
        array(
            'id'       => 'spl_energy_index',
            'type'     => 'text',
            'title'    => esc_html__("Global Energy Performance Index", 'houzez'),
            'default' => esc_html__("Global Energy Performance Index", 'houzez')
        ),
       
        array(
            'id'       => 'spl_energy_renew_index',
            'type'     => 'text',
            'title'    => esc_html__("Renewable energy performance index", 'houzez'),
            'default' => esc_html__("Renewable energy performance index", 'houzez')
        ),
        

        array(
            'id'       => 'spl_energy_build_performance',
            'type'     => 'text',
            'title'    => esc_html__("Energy performance of the building", 'houzez'),
            'default' => esc_html__("Energy performance of the building", 'houzez')
        ),
        

        array(
            'id'       => 'spl_energy_ecp_rating',
            'type'     => 'text',
            'title'    => esc_html__("EPC Current Rating", 'houzez'),
            'default' => esc_html__("EPC Current Rating", 'houzez')
        ),
    
        array(
            'id'       => 'spl_energy_ecp_p',
            'type'     => 'text',
            'title'    => esc_html__("EPC Potential Rating", 'houzez'),
            'default' => esc_html__("EPC Potential Rating", 'houzez')
        ),
        array(
            'id'       => 'spl_energy_cls',
            'type'     => 'text',
            'title'    => esc_html__("Energy class", 'houzez'),
            'default' => esc_html__("Energy class", 'houzez')
        ),
        
        array(
            'id'       => 'sp_energy_labels_section-end',
            'type'     => 'section',
            'indent'   => false,
        ),

        /*------------------------------------------- Mortgage Calculator -------------------------*/
        array(
            'id'       => 'sp_mortgage_cal_labels_section-start',
            'type'     => 'section',
            'title'    => esc_html__('Mortgage Calculator', 'houzez'),
            'subtitle' => esc_html__('Manage labels for mortgage calculator section', 'houzez'),
            'indent'   => true,
        ),

        array(
            'id'       => 'spc_principal_ints',
            'type'     => 'text',
            'title'    => esc_html__("Principal & Interest", 'houzez'),
            'default' => esc_html__("Principal & Interest", 'houzez')
        ),

        array(
            'id'       => 'spc_prop_tax',
            'type'     => 'text',
            'title'    => esc_html__("Property Tax", 'houzez'),
            'default' => esc_html__("Property Tax", 'houzez')
        ),
        array(
            'id'       => 'spc_hi',
            'type'     => 'text',
            'title'    => esc_html__("Home Insurance", 'houzez'),
            'default' => esc_html__("Home Insurance", 'houzez')
        ),
        array(
            'id'       => 'spc_pmi',
            'type'     => 'text',
            'title'    => esc_html__("PMI", 'houzez'),
            'default' => esc_html__("PMI", 'houzez')
        ),
        array(
            'id'       => 'spc_total_amt',
            'type'     => 'text',
            'title'    => esc_html__("Total Amount", 'houzez'),
            'default' => esc_html__("Total Amount", 'houzez')
        ),
        array(
            'id'       => 'spc_down_payment',
            'type'     => 'text',
            'title'    => esc_html__("Down Payment", 'houzez'),
            'default' => esc_html__("Down Payment", 'houzez')
        ),
        array(
            'id'       => 'spc_ir',
            'type'     => 'text',
            'title'    => esc_html__("Interest Rate", 'houzez'),
            'default' => esc_html__("Interest Rate", 'houzez')
        ),

        array(
            'id'       => 'spc_load_term',
            'type'     => 'text',
            'title'    => esc_html__("Loan Term", 'houzez'),
            'default' => esc_html__("Loan Terms (Years)", 'houzez')
        ),

        array(
            'id'       => 'spc_monthly',
            'type'     => 'text',
            'title'    => esc_html__("Monthly", 'houzez'),
            'default' => esc_html__("Monthly", 'houzez')
        ),
        array(
            'id'       => 'spc_btn_cal',
            'type'     => 'text',
            'title'    => esc_html__("Calculate Button", 'houzez'),
            'default' => esc_html__("Calculate", 'houzez')
        ),
        
        
        array(
            'id'       => 'sp_mortgage_cal_labels_section-end',
            'type'     => 'section',
            'indent'   => false,
        ),
        
    )
));

Redux::setSection( $houzez_opt_name, array(
    'title'  => esc_html__( 'Add New Property', 'houzez' ),
    'id'     => 'createlisting-translation',
    'desc'   => '',
    'subsection'   => true,
    'fields'        => array(
        
        array(
            'id'       => 'cl_buttons_section-start',
            'type'     => 'section',
            'title'    => esc_html__('Buttons and links', 'houzez'),
            'subtitle' => esc_html__('Manage buttons and links titles front-end', 'houzez'),
            'indent'   => true,
        ),

        array(
            'id'       => 'fal_submit_property',
            'type'     => 'text',
            'title'    => esc_html__('Submit Property', 'houzez'),
            'default' => esc_html__('Submit Property', 'houzez')
        ),

        array(
            'id'       => 'fal_save_draft',
            'type'     => 'text',
            'title'    => esc_html__('Save as Draft', 'houzez'),
            'default' => esc_html__('Save as Draft', 'houzez')
        ),

        array(
            'id'       => 'fal_save_changes',
            'type'     => 'text',
            'title'    => esc_html__('Save Changes', 'houzez'),
            'default' => esc_html__('Save Changes', 'houzez')
        ),

        array(
            'id'       => 'fal_view_property',
            'type'     => 'text',
            'title'    => esc_html__('View Property', 'houzez'),
            'default' => esc_html__('View Property', 'houzez')
        ),

        array(
            'id'       => 'fal_cancel',
            'type'     => 'text',
            'title'    => esc_html__('Cancel', 'houzez'),
            'default' => esc_html__('Cancel', 'houzez')
        ),
        array(
            'id'       => 'fal_back',
            'type'     => 'text',
            'title'    => esc_html__('Back', 'houzez'),
            'default' => esc_html__('Back', 'houzez')
        ),

        array(
            'id'       => 'fal_next',
            'type'     => 'text',
            'title'    => esc_html__('Next', 'houzez'),
            'default' => esc_html__('Next', 'houzez')
        ),

        array(
            'id'       => 'cl_buttons_section-end',
            'type'     => 'section',
            'indent'   => false,
        ),

        array(
            'id'       => 'cl_sections_section-start',
            'type'     => 'section',
            'title'    => esc_html__('Sections Titles', 'houzez'),
            'subtitle' => esc_html__('Manage create listing page section titles front-end and admin', 'houzez'),
            'indent'   => true,
        ),

        array(
            'id'       => 'cls_description',
            'type'     => 'text',
            'title'    => esc_html__('Description', 'houzez'),
            'default' => esc_html__('Description', 'houzez')
        ),

        array(
            'id'       => 'cls_description_price',
            'type'     => 'text',
            'title'    => esc_html__('Description & Price', 'houzez'),
            'default' => esc_html__('Description & Price', 'houzez')
        ),

        array(
            'id'       => 'cls_price',
            'type'     => 'text',
            'title'    => esc_html__('Price', 'houzez'),
            'default' => esc_html__('Price', 'houzez')
        ),

        array(
            'id'       => 'cls_media',
            'type'     => 'text',
            'title'    => esc_html__('Media', 'houzez'),
            'default' => esc_html__('Media', 'houzez')
        ),

        array(
            'id'       => 'cls_documents',
            'type'     => 'text',
            'title'    => esc_html__('Property Documents', 'houzez'),
            'default' => esc_html__('Property Documents', 'houzez')
        ),

        array(
            'id'       => 'cls_details',
            'type'     => 'text',
            'title'    => esc_html__('Details', 'houzez'),
            'default' => esc_html__('Details', 'houzez')
        ),
        array(
            'id'       => 'cls_private_notes',
            'type'     => 'text',
            'title'    => esc_html__('Private Note', 'houzez'),
            'default' => esc_html__('Private Note', 'houzez')
        ),
        array(
            'id'       => 'cls_additional_details',
            'type'     => 'text',
            'title'    => esc_html__('Additional details', 'houzez'),
            'default' => esc_html__('Additional details', 'houzez')
        ),
        array(
            'id'       => 'cls_address',
            'type'     => 'text',
            'title'    => esc_html__('Address', 'houzez'),
            'default' => esc_html__('Address', 'houzez')
        ),
        array(
            'id'       => 'cls_location',
            'type'     => 'text',
            'title'    => esc_html__('Location', 'houzez'),
            'default' => esc_html__('Location', 'houzez')
        ),
        array(
            'id'       => 'cls_map',
            'type'     => 'text',
            'title'    => esc_html__('Map', 'houzez'),
            'default' => esc_html__('Map', 'houzez')
        ),
        array(
            'id'       => 'cls_features',
            'type'     => 'text',
            'title'    => esc_html__('Features', 'houzez'),
            'default' => esc_html__('Features', 'houzez')
        ),
        array(
            'id'       => 'cls_video',
            'type'     => 'text',
            'title'    => esc_html__('Video', 'houzez'),
            'default' => esc_html__('Video', 'houzez')
        ),
        array(
            'id'       => 'cls_virtual_tour',
            'type'     => 'text',
            'title'    => esc_html__('360° Virtual Tour', 'houzez'),
            'default' => esc_html__('360° Virtual Tour', 'houzez')
        ),

        array(
            'id'       => 'cls_sub_listings',
            'type'     => 'text',
            'title'    => esc_html__('Sub listings', 'houzez'),
            'default' => esc_html__('Sub listings', 'houzez')
        ),
        array(
            'id'       => 'cls_energy_class',
            'type'     => 'text',
            'title'    => esc_html__('Energy Class', 'houzez'),
            'default' => esc_html__('Energy Class', 'houzez')
        ),
        array(
            'id'       => 'cls_floor_plans',
            'type'     => 'text',
            'title'    => esc_html__('Floor Plans', 'houzez'),
            'default' => esc_html__('Floor Plans', 'houzez')
        ),
        
        array(
            'id'       => 'cls_walkscore',
            'type'     => 'text',
            'title'    => esc_html__('Walkscore', 'houzez'),
            'default' => esc_html__('Walkscore', 'houzez')
        ),

        array(
            'id'       => 'cls_contact_info',
            'type'     => 'text',
            'title'    => esc_html__("Contact Information", 'houzez'),
            'default' => esc_html__("Contact Information", 'houzez')
        ),

        array(
            'id'       => 'cls_information',
            'type'     => 'text',
            'title'    => esc_html__("Information", 'houzez'),
            'default' => esc_html__("Information", 'houzez')
        ),

        array(
            'id'       => 'cls_settings',
            'type'     => 'text',
            'title'    => esc_html__("Property Settings", 'houzez'),
            'default' => esc_html__("Property Settings", 'houzez')
        ),

        array(
            'id'       => 'cls_slider',
            'type'     => 'text',
            'title'    => esc_html__("Slider", 'houzez'),
            'default' => esc_html__("Slider", 'houzez')
        ),

        array(
            'id'       => 'cls_layout',
            'type'     => 'text',
            'title'    => esc_html__("Layout", 'houzez'),
            'default' => esc_html__("Layout", 'houzez')
        ),

        array(
            'id'       => 'cls_rental',
            'type'     => 'text',
            'title'    => esc_html__("Rental Details", 'houzez'),
            'default' => esc_html__("Rental Details", 'houzez')
        ),
       
        array(
            'id'       => 'cls_gdpr',
            'type'     => 'text',
            'title'    => esc_html__("GDPR Agreement", 'houzez'),
            'default' => esc_html__("GDPR Agreement *", 'houzez')
        ),

        array(
            'id'       => 'cl_sections_section-end',
            'type'     => 'section',
            'indent'   => false,
        ),

        /*--------------------------------------------------------------
        * Location labels
        **------------------------------------------------------------*/
        array(
            'id'       => 'cl_location_section-start',
            'type'     => 'section',
            'title'    => esc_html__('Fields Labels and Placeholders', 'houzez'),
            'subtitle' => '',
            'indent'   => true,
        ),

        array(
            'id'       => 'cl_prop_title',
            'type'     => 'text',
            'title'    => esc_html__("Property Title", 'houzez'),
            'default' => esc_html__("Property Title", 'houzez')
        ),
        array(
            'id'       => 'cl_prop_title_plac',
            'type'     => 'text',
            'title'    => esc_html__("Property Title Placeholder", 'houzez'),
            'default' => esc_html__("Enter your property title", 'houzez')
        ),
        array(
            'id'       => 'cl_content',
            'type'     => 'text',
            'title'    => esc_html__("Content", 'houzez'),
            'default' => esc_html__("Content", 'houzez')
        ),
        
        array(
            'id'       => 'cl_prop_type',
            'type'     => 'text',
            'title'    => esc_html__("Type", 'houzez'),
            'default' => esc_html__("Type", 'houzez')
        ),
        array(
            'id'       => 'cl_prop_types',
            'type'     => 'text',
            'title'    => esc_html__("Types", 'houzez'),
            'default' => esc_html__("Types", 'houzez')
        ),
        array(
            'id'       => 'cl_prop_status',
            'type'     => 'text',
            'title'    => esc_html__("Status", 'houzez'),
            'default' => esc_html__("Status", 'houzez')
        ),
        array(
            'id'       => 'cl_prop_statuses',
            'type'     => 'text',
            'title'    => esc_html__("Statuses", 'houzez'),
            'default' => esc_html__("Statuses", 'houzez')
        ),
        array(
            'id'       => 'cl_prop_label',
            'type'     => 'text',
            'title'    => esc_html__("Label", 'houzez'),
            'default' => esc_html__("Label", 'houzez')
        ),
        array(
            'id'       => 'cl_prop_labels',
            'type'     => 'text',
            'title'    => esc_html__("Labels", 'houzez'),
            'default' => esc_html__("Labels", 'houzez')
        ),
        array(
            'id'       => 'cl_sale_price',
            'type'     => 'text',
            'title'    => esc_html__("Sale or Rent Price", 'houzez'),
            'default' => esc_html__("Sale or Rent Price", 'houzez')
        ),
        array(
            'id'       => 'cl_sale_price_plac',
            'type'     => 'text',
            'title'    => esc_html__("Sale or Rent Price Placeholder", 'houzez'),
            'default' => esc_html__("Enter the price", 'houzez')
        ),

        array(
            'id'       => 'cl_second_price',
            'type'     => 'text',
            'title'    => esc_html__("Second Price", 'houzez'),
            'default' => esc_html__("Second Price (Optional)", 'houzez')
        ),
        array(
            'id'       => 'cl_second_price_plac',
            'type'     => 'text',
            'title'    => esc_html__("Second Price Placeholder", 'houzez'),
            'default' => esc_html__("Enter the second price", 'houzez')
        ),
        array(
            'id'       => 'cl_price_postfix',
            'type'     => 'text',
            'title'    => esc_html__("Price Postfix", 'houzez'),
            'default' => esc_html__("After The Price", 'houzez')
        ),
        array(
            'id'       => 'cl_price_postfix_plac',
            'type'     => 'text',
            'title'    => esc_html__("Price Postfix Placeholder", 'houzez'),
            'default' => esc_html__("Enter the after price", 'houzez')
        ),
        array(
            'id'       => 'cl_price_postfix_tooltip',
            'type'     => 'text',
            'title'    => esc_html__("Price Postfix Tooltip", 'houzez'),
            'default' => esc_html__("For example: Monthly", 'houzez')
        ),

        array(
            'id'       => 'cl_price_prefix',
            'type'     => 'text',
            'title'    => esc_html__("Price Prefix", 'houzez'),
            'default' => esc_html__("Price Prefix", 'houzez')
        ),
        array(
            'id'       => 'cl_price_prefix_plac',
            'type'     => 'text',
            'title'    => esc_html__("Price Prefix Placeholder", 'houzez'),
            'default' => esc_html__("Enter the price prefix", 'houzez')
        ),
        array(
            'id'       => 'cl_price_prefix_tooltip',
            'type'     => 'text',
            'title'    => esc_html__("Price Postfix Tooltip", 'houzez'),
            'default' => esc_html__("For example: Start from", 'houzez')
        ),

        array(
            'id'       => 'cl_bedrooms',
            'type'     => 'text',
            'title'    => esc_html__('Bedrooms', 'houzez'),
            'default' => esc_html__('Bedrooms', 'houzez')
        ),
        array(
            'id'       => 'cl_bedrooms_plac',
            'type'     => 'text',
            'title'    => esc_html__('Bedrooms Placeholder', 'houzez'),
            'default' => esc_html__('Enter number of bedrooms', 'houzez')
        ),

        array(
            'id'       => 'cl_rooms',
            'type'     => 'text',
            'title'    => esc_html__('Rooms', 'houzez'),
            'default' => esc_html__('Rooms', 'houzez')
        ),
        array(
            'id'       => 'cl_rooms_plac',
            'type'     => 'text',
            'title'    => esc_html__('Rooms Placeholder', 'houzez'),
            'default' => esc_html__('Enter number of rooms', 'houzez')
        ),

        array(
            'id'       => 'cl_bathrooms',
            'type'     => 'text',
            'title'    => esc_html__('Bathrooms', 'houzez'),
            'default' => esc_html__('Bathrooms', 'houzez')
        ),

        array(
            'id'       => 'cl_bathrooms_plac',
            'type'     => 'text',
            'title'    => esc_html__('Bathrooms Placeholder', 'houzez'),
            'default' => esc_html__('Enter number of bathrooms', 'houzez')
        ),

        array(
            'id'       => 'cl_area_size',
            'type'     => 'text',
            'title'    => esc_html__('Area Size', 'houzez'),
            'default' => esc_html__('Area Size', 'houzez')
        ),

        array(
            'id'       => 'cl_area_size_plac',
            'type'     => 'text',
            'title'    => esc_html__('Area Size Placeholder', 'houzez'),
            'default' => esc_html__('Enter property area size', 'houzez')
        ),

        array(
            'id'       => 'cl_area_size_postfix',
            'type'     => 'text',
            'title'    => esc_html__('Size Postfix', 'houzez'),
            'default' => esc_html__('Size Postfix', 'houzez')
        ),

        array(
            'id'       => 'cl_area_size_postfix_plac',
            'type'     => 'text',
            'title'    => esc_html__('Size Postfix Placeholder', 'houzez'),
            'default' => esc_html__('Enter property area size postfix', 'houzez')
        ),

        array(
            'id'       => 'cl_area_size_postfix_tooltip',
            'type'     => 'text',
            'title'    => esc_html__('Size Postfix Tooltip', 'houzez'),
            'default' => esc_html__('For example: Sq Ft', 'houzez')
        ),

        array(
            'id'       => 'cl_land_size',
            'type'     => 'text',
            'title'    => esc_html__('Land Area', 'houzez'),
            'default' => esc_html__('Land Area', 'houzez')
        ),

        array(
            'id'       => 'cl_land_size_plac',
            'type'     => 'text',
            'title'    => esc_html__('Land Area Placeholder', 'houzez'),
            'default' => esc_html__('Enter property Land Area', 'houzez')
        ),

        array(
            'id'       => 'cl_land_size_postfix',
            'type'     => 'text',
            'title'    => esc_html__('Land Area Size Postfix', 'houzez'),
            'default' => esc_html__('Land Area Size Postfix', 'houzez')
        ),

        array(
            'id'       => 'cl_land_size_postfix_plac',
            'type'     => 'text',
            'title'    => esc_html__('Land Area Size Postfix Placeholder', 'houzez'),
            'default' => esc_html__('Enter property Land Area postfix', 'houzez')
        ),

        array(
            'id'       => 'cl_land_size_postfix_tooltip',
            'type'     => 'text',
            'title'    => esc_html__('Land Area Size Postfix Tooltip', 'houzez'),
            'default' => esc_html__('For example: Sq Ft', 'houzez')
        ),

        array(
            'id'       => 'cl_garage',
            'type'     => 'text',
            'title'    => esc_html__('Garages', 'houzez'),
            'default' => esc_html__('Garages', 'houzez')
        ),

        array(
            'id'       => 'cl_garage_plac',
            'type'     => 'text',
            'title'    => esc_html__('Garages Placeholder', 'houzez'),
            'default' => esc_html__('Enter number of garages', 'houzez')
        ),

        array(
            'id'       => 'cl_garage_size',
            'type'     => 'text',
            'title'    => esc_html__('Garage Size', 'houzez'),
            'default' => esc_html__('Garage Size', 'houzez')
        ),

        array(
            'id'       => 'cl_garage_size_plac',
            'type'     => 'text',
            'title'    => esc_html__('Garage Size Placeholder', 'houzez'),
            'default' => esc_html__('Enter the garage size', 'houzez')
        ),

        array(
            'id'       => 'cl_garage_size_tooltip',
            'type'     => 'text',
            'title'    => esc_html__('Garage Size Tooltip', 'houzez'),
            'default' => esc_html__('For example: 200 Sq Ft', 'houzez')
        ),

        array(
            'id'       => 'cl_year_built',
            'type'     => 'text',
            'title'    => esc_html__('Year Built', 'houzez'),
            'default' => esc_html__('Year Built', 'houzez')
        ),

        array(
            'id'       => 'cl_year_built_plac',
            'type'     => 'text',
            'title'    => esc_html__('Year Built Placeholder', 'houzez'),
            'default' => esc_html__('Enter year built', 'houzez')
        ),

        array(
            'id'       => 'cl_prop_id',
            'type'     => 'text',
            'title'    => esc_html__("Property ID", 'houzez'),
            'default' => esc_html__("Property ID", 'houzez')
        ),
        array(
            'id'       => 'cl_prop_id_plac',
            'type'     => 'text',
            'title'    => esc_html__("Property ID Placeholder", 'houzez'),
            'default' => esc_html__("Enter property ID", 'houzez')
        ),

        array(
            'id'       => 'cl_prop_id_tooltip',
            'type'     => 'text',
            'title'    => esc_html__("Property ID Tooltip", 'houzez'),
            'default' => esc_html__("For example: HZ-01", 'houzez')
        ),

        array(
            'id'       => 'cl_additional_title',
            'type'     => 'text',
            'title'    => esc_html__("Additional Details Title", 'houzez'),
            'default' => esc_html__("Title", 'houzez')
        ),
        array(
            'id'       => 'cl_additional_title_plac',
            'type'     => 'text',
            'title'    => esc_html__("Additional Details Title Placeholder", 'houzez'),
            'default' => esc_html__("Eg: Equipment", 'houzez')
        ),

        array(
            'id'       => 'cl_additional_value',
            'type'     => 'text',
            'title'    => esc_html__("Additional Details Value", 'houzez'),
            'default' => esc_html__("Value", 'houzez')
        ),
        array(
            'id'       => 'cl_additional_value_plac',
            'type'     => 'text',
            'title'    => esc_html__("Additional Details Value Placeholder", 'houzez'),
            'default' => esc_html__("Grill - Gas", 'houzez')
        ),

        array(
            'id'       => 'cl_drag_drop_text_image',
            'type'     => 'text',
            'title'    => esc_html__("Drag & Drop Text", 'houzez'),
            'default' => esc_html__("Drag and drop the images to customize the image gallery order.", 'houzez')
        ),

        array(
            'id'       => 'cl_drag_drop_title',
            'type'     => 'text',
            'title'    => esc_html__("Drag & Drop Title", 'houzez'),
            'default' => esc_html__("Drag and drop the gallery images here", 'houzez')
        ),

        array(
            'id'       => 'cl_image_size',
            'type'     => 'text',
            'title'    => esc_html__("Image Size", 'houzez'),
            'default' => esc_html__("(Minimum size 1440x900)", 'houzez')
        ),

        array(
            'id'       => 'cl_image_btn',
            'type'     => 'text',
            'title'    => esc_html__("Select Image Button", 'houzez'),
            'default' => esc_html__("Select and Upload", 'houzez')
        ),

        array(
            'id'       => 'cl_image_featured',
            'type'     => 'text',
            'title'    => esc_html__("Make Featured text", 'houzez'),
            'default' => esc_html__("To mark an image as featured, click the star icon. If no image is marked as featured, the first image will be considered the featured image.", 'houzez')
        ),

        array(
            'id'       => 'cl_video_url',
            'type'     => 'text',
            'title'    => esc_html__("Video Url", 'houzez'),
            'default' => esc_html__("Video URL", 'houzez')
        ),
        array(
            'id'       => 'cl_video_url_plac',
            'type'     => 'text',
            'title'    => esc_html__("Video Url Placeholder", 'houzez'),
            'default' => esc_html__("YouTube, Vimeo, SWF File and MOV File are supported", 'houzez')
        ),

        array(
            'id'       => 'cl_energy_cls',
            'type'     => 'text',
            'title'    => esc_html__("Energy Class", 'houzez'),
            'default' => esc_html__("Energy Class", 'houzez')
        ),
        array(
            'id'       => 'cl_energy_cls_plac',
            'type'     => 'text',
            'title'    => esc_html__("Energy Class Placeholder", 'houzez'),
            'default' => esc_html__("Select Energy Class", 'houzez')
        ),

        array(
            'id'       => 'cl_energy_index',
            'type'     => 'text',
            'title'    => esc_html__("Global Energy Performance Index", 'houzez'),
            'default' => esc_html__("Global Energy Performance Index", 'houzez')
        ),
        array(
            'id'       => 'cl_energy_index_plac',
            'type'     => 'text',
            'title'    => esc_html__("Global Energy Performance Index Placeholder", 'houzez'),
            'default' => esc_html__("For example: 92.42 kWh / m²a", 'houzez')
        ),

        array(
            'id'       => 'cl_energy_renew_index',
            'type'     => 'text',
            'title'    => esc_html__("Renewable energy performance index", 'houzez'),
            'default' => esc_html__("Renewable energy performance index", 'houzez')
        ),
        array(
            'id'       => 'cl_energy_renew_index_plac',
            'type'     => 'text',
            'title'    => esc_html__("Renewable energy performance index Placeholder", 'houzez'),
            'default' => esc_html__("For example: 00.00 kWh / m²a", 'houzez')
        ),

        array(
            'id'       => 'cl_energy_build_performance',
            'type'     => 'text',
            'title'    => esc_html__("Energy performance of the building", 'houzez'),
            'default' => esc_html__("Energy performance of the building", 'houzez')
        ),
        array(
            'id'       => 'cl_energy_build_performance_plac',
            'type'     => 'text',
            'title'    => esc_html__("Energy performance of the building Placeholder", 'houzez'),
            'default' => '',
        ),

        array(
            'id'       => 'cl_energy_ecp_rating',
            'type'     => 'text',
            'title'    => esc_html__("EPC Current Rating", 'houzez'),
            'default' => esc_html__("EPC Current Rating", 'houzez')
        ),
        array(
            'id'       => 'cl_energy_ecp_rating_plac',
            'type'     => 'text',
            'title'    => esc_html__("EPC Current Rating Placeholder", 'houzez'),
            'default' => ''
        ),

        array(
            'id'       => 'cl_energy_ecp_p',
            'type'     => 'text',
            'title'    => esc_html__("EPC Potential Rating", 'houzez'),
            'default' => esc_html__("EPC Potential Rating", 'houzez')
        ),
        array(
            'id'       => 'cl_energy_ecp_p_plac',
            'type'     => 'text',
            'title'    => esc_html__("EPC Potential Rating Placeholder", 'houzez'),
            'default' => '',
        ),

        array(
            'id'       => 'cl_virtual_plac',
            'type'     => 'text',
            'title'    => esc_html__("Virtual Tour Placeholder", 'houzez'),
            'default' => esc_html__("Enter virtual tour iframe/embeded code", 'houzez')
        ),

        array(
            'id'       => 'cl_private_note',
            'type'     => 'text',
            'title'    => esc_html__("Private Note Label", 'houzez'),
            'default' => esc_html__("Write private note for this property, it will not display for public.", 'houzez')
        ),

        array(
            'id'       => 'cl_private_note_plac',
            'type'     => 'text',
            'title'    => esc_html__("Private Note Placeholder", 'houzez'),
            'default' => esc_html__("Enter the note here", 'houzez')
        ),

        array(
            'id'       => 'cl_address',
            'type'     => 'text',
            'title'    => esc_html__('Address', 'houzez'),
            'default' => esc_html__('Address', 'houzez')
        ),
        array(
            'id'       => 'cl_address_plac',
            'type'     => 'text',
            'title'    => esc_html__('Address Placeholder', 'houzez'),
            'default' => esc_html__('Enter your property address', 'houzez')
        ),
        array(
            'id'       => 'cl_zip',
            'type'     => 'text',
            'title'    => esc_html__('Zip/Postal Code', 'houzez'),
            'default' => esc_html__('Zip/Postal Code', 'houzez')
        ),
        array(
            'id'       => 'cl_zip_plac',
            'type'     => 'text',
            'title'    => esc_html__('Zip/Postal Code Placeholder', 'houzez'),
            'default' => esc_html__('Enter zip/postal code', 'houzez')
        ),
        array(
            'id'       => 'cl_country',
            'type'     => 'text',
            'title'    => esc_html__('Country', 'houzez'),
            'default' => esc_html__('Country', 'houzez')
        ),
        array(
            'id'       => 'cl_country_plac',
            'type'     => 'text',
            'title'    => esc_html__('Country Placeholder', 'houzez'),
            'default' => esc_html__('Enter the country', 'houzez')
        ),
        array(
            'id'       => 'cl_state',
            'type'     => 'text',
            'title'    => esc_html__('State/county', 'houzez'),
            'default' => esc_html__('State/county', 'houzez')
        ),
        array(
            'id'       => 'cl_state_plac',
            'type'     => 'text',
            'title'    => esc_html__('State/county Placeholder', 'houzez'),
            'default' => esc_html__('Enter the State/county', 'houzez')
        ),
        array(
            'id'       => 'cl_city',
            'type'     => 'text',
            'title'    => esc_html__('City', 'houzez'),
            'default' => esc_html__('City', 'houzez')
        ),
        array(
            'id'       => 'cl_city_plac',
            'type'     => 'text',
            'title'    => esc_html__('City Placeholder', 'houzez'),
            'default' => esc_html__('Enter the city', 'houzez')
        ),
        array(
            'id'       => 'cl_area',
            'type'     => 'text',
            'title'    => esc_html__('Area', 'houzez'),
            'default' => esc_html__('Area', 'houzez')
        ),
        array(
            'id'       => 'cl_area_plac',
            'type'     => 'text',
            'title'    => esc_html__('Area Placeholder', 'houzez'),
            'default' => esc_html__('Enter the area', 'houzez')
        ),

        array(
            'id'       => 'cl_drag_drop_text',
            'type'     => 'text',
            'title'    => esc_html__('Drag & Drop Text', 'houzez'),
            'default' => esc_html__('Drag and drop the pin on map to find exact location', 'houzez')
        ),

        array(
            'id'       => 'cl_latitude',
            'type'     => 'text',
            'title'    => esc_html__('Latitude', 'houzez'),
            'default' => esc_html__('Latitude', 'houzez')
        ),
        array(
            'id'       => 'cl_latitude_plac',
            'type'     => 'text',
            'title'    => esc_html__('Latitude Placeholder', 'houzez'),
            'default' => esc_html__('Enter address latitude', 'houzez')
        ),

        array(
            'id'       => 'cl_longitude',
            'type'     => 'text',
            'title'    => esc_html__('Longitude', 'houzez'),
            'default' => esc_html__('Longitude', 'houzez')
        ),
        array(
            'id'       => 'cl_longitude_plac',
            'type'     => 'text',
            'title'    => esc_html__('Longitude Placeholder', 'houzez'),
            'default' => esc_html__('Enter address Longitude', 'houzez')
        ),

        array(
            'id'       => 'cl_street_view',
            'type'     => 'text',
            'title'    => esc_html__('Street View', 'houzez'),
            'default' => esc_html__('Street View', 'houzez')
        ),

        array(
            'id'       => 'cl_ppbtn',
            'type'     => 'text',
            'title'    => esc_html__('Place pin buttton', 'houzez'),
            'default' => esc_html__('Place the pin in address above', 'houzez')
        ),

        array(
            'id'       => 'cl_streat_address',
            'type'     => 'text',
            'title'    => esc_html__("Street Address", 'houzez'),
            'default' => esc_html__("Street Address", 'houzez')
        ),
        array(
            'id'       => 'cl_streat_address_plac',
            'type'     => 'text',
            'title'    => esc_html__("Street Address Placeholder", 'houzez'),
            'default' => esc_html__("Enter only the street name and the building number", 'houzez')
        ),

        array(
            'id'       => 'cl_make_featured',
            'type'     => 'text',
            'title'    => esc_html__("Make Featured Text", 'houzez'),
            'default' => esc_html__("Do you want to mark this property as featured?", 'houzez')
        ),

        array(
            'id'       => 'cl_login_view',
            'type'     => 'text',
            'title'    => esc_html__("Login to view title", 'houzez'),
            'default' => esc_html__("The user must be logged in to view this property?", 'houzez')
        ),

        array(
            'id'       => 'cl_login_view_plac',
            'type'     => 'text',
            'title'    => esc_html__("Login to view description", 'houzez'),
            'default' => esc_html__('If "Yes" then only logged in user can view property details.', 'houzez')
        ),

        array(
            'id'       => 'cl_disclaimer',
            'type'     => 'text',
            'title'    => esc_html__("Disclaimer", 'houzez'),
            'default' => esc_html__("Disclaimer", 'houzez')
        ),

        array(
            'id'       => 'cl_mortgage',
            'type'     => 'text',
            'title'    => esc_html__("Mortgage Calculator", 'houzez'),
            'default' => esc_html__("Mortgage Calulator", 'houzez')
        ),

        array(
            'id'       => 'cl_mortgage_plac',
            'type'     => 'text',
            'title'    => esc_html__("Mortgage Calulator Placeholder", 'houzez'),
            'default' => esc_html__('Show/Hide mortgage calculator for this listing?', 'houzez')
        ),

        array(
            'id'       => 'cl_decuments_text',
            'type'     => 'text',
            'title'    => esc_html__("Documents Text", 'houzez'),
            'default' => esc_html__("You can attach PDF files, Map images OR other documents.", 'houzez')
        ),

        array(
            'id'       => 'cl_attachment_btn',
            'type'     => 'text',
            'title'    => esc_html__("Attachment button", 'houzez'),
            'default' => esc_html__("Select Attachment.", 'houzez')
        ),

        array(
            'id'       => 'cl_uploaded_attachments',
            'type'     => 'text',
            'title'    => esc_html__("Uploaded Attachments", 'houzez'),
            'default' => esc_html__("Uploaded Attachments", 'houzez')
        ),

        array(
            'id'       => 'cl_contact_info_text',
            'type'     => 'text',
            'title'    => esc_html__("Contact Info Text", 'houzez'),
            'default' => esc_html__("What information do you want to display in agent data container?", 'houzez')
        ),
        array(
            'id'       => 'cl_author_info',
            'type'     => 'text',
            'title'    => esc_html__("Author Info", 'houzez'),
            'default' => esc_html__("Author Info", 'houzez')
        ),

        array(
            'id'       => 'cl_agent_info',
            'type'     => 'text',
            'title'    => esc_html__("Agent Info", 'houzez'),
            'default' => esc_html__("Agent Info (Choose agent from the list below)", 'houzez')
        ),

        array(
            'id'       => 'cl_agent_info_plac',
            'type'     => 'text',
            'title'    => esc_html__("Agent Info Placeholder", 'houzez'),
            'default' => esc_html__("Select an Agent", 'houzez')
        ),

        array(
            'id'       => 'cl_agency_info',
            'type'     => 'text',
            'title'    => esc_html__("Agency Info", 'houzez'),
            'default' => esc_html__("Agency Info (Choose agency from the list below)", 'houzez')
        ),

        array(
            'id'       => 'cl_agency_info2',
            'type'     => 'text',
            'title'    => esc_html__("Agency Info", 'houzez'),
            'default' => esc_html__("Agency Info", 'houzez')
        ),

        array(
            'id'       => 'cl_agency_info_plac',
            'type'     => 'text',
            'title'    => esc_html__("Agency Info Placeholder", 'houzez'),
            'default' => esc_html__("Select an Agency", 'houzez')
        ),

        array(
            'id'       => 'cl_not_display',
            'type'     => 'text',
            'title'    => esc_html__("Do not display", 'houzez'),
            'default' => esc_html__("Do not display", 'houzez')
        ),

        array(
            'id'       => 'cl_add_slider',
            'type'     => 'text',
            'title'    => esc_html__("Add to Slider", 'houzez'),
            'default' => esc_html__("Do you want to display this property on the custom property slider?", 'houzez')
        ),
        array(
            'id'       => 'cl_add_slider_plac',
            'type'     => 'text',
            'title'    => esc_html__("Add to Slider Placeholder", 'houzez'),
            'default' => esc_html__("Upload an image below if you selected yes.", 'houzez')
        ),
        array(
            'id'       => 'cl_slider_img',
            'type'     => 'text',
            'title'    => esc_html__("Slider Image", 'houzez'),
            'default' => esc_html__("Slider Image", 'houzez')
        ),

        array(
            'id'       => 'cl_slider_img_size',
            'type'     => 'text',
            'title'    => esc_html__("Slider Image Size", 'houzez'),
            'default' => esc_html__("Suggested size 2000px x 700px", 'houzez')
        ),
        array(
            'id'       => 'cl_plan_title',
            'type'     => 'text',
            'title'    => esc_html__("Plan Title", 'houzez'),
            'default' => esc_html__("Plan Title", 'houzez')
        ),

        array(
            'id'       => 'cl_plan_title_plac',
            'type'     => 'text',
            'title'    => esc_html__("Plan Title Placeholder", 'houzez'),
            'default' => esc_html__("Enter the plan title", 'houzez')
        ),

        array(
            'id'       => 'cl_plan_bedrooms',
            'type'     => 'text',
            'title'    => esc_html__("Plan Bedrooms", 'houzez'),
            'default' => esc_html__("Bedrooms", 'houzez')
        ),

        array(
            'id'       => 'cl_plan_bedrooms_plac',
            'type'     => 'text',
            'title'    => esc_html__("Plan Bedrooms Placeholder", 'houzez'),
            'default' => esc_html__("Enter the number of bedrooms", 'houzez')
        ),

        array(
            'id'       => 'cl_plan_bathrooms',
            'type'     => 'text',
            'title'    => esc_html__("Plan Bathrooms", 'houzez'),
            'default' => esc_html__("Bathrooms", 'houzez')
        ),
        array(
            'id'       => 'cl_plan_bathrooms_plac',
            'type'     => 'text',
            'title'    => esc_html__("Plan Bedrooms Placeholder", 'houzez'),
            'default' => esc_html__("Enter the number of bathrooms", 'houzez')
        ),

        array(
            'id'       => 'cl_plan_price',
            'type'     => 'text',
            'title'    => esc_html__("Plan Price", 'houzez'),
            'default' => esc_html__("Price", 'houzez')
        ),
        array(
            'id'       => 'cl_plan_price_plac',
            'type'     => 'text',
            'title'    => esc_html__("Plan Price Placeholder", 'houzez'),
            'default' => esc_html__("Enter the price", 'houzez')
        ),

        array(
            'id'       => 'cl_plan_price_postfix',
            'type'     => 'text',
            'title'    => esc_html__("Plan Price Postfix", 'houzez'),
            'default' => esc_html__("Price Postfix", 'houzez')
        ),
        array(
            'id'       => 'cl_plan_price_postfix_plac',
            'type'     => 'text',
            'title'    => esc_html__("Plan Price Postfix Placeholder", 'houzez'),
            'default' => esc_html__("Enter the price postfix", 'houzez')
        ),

        array(
            'id'       => 'cl_plan_size',
            'type'     => 'text',
            'title'    => esc_html__("Plan Size", 'houzez'),
            'default' => esc_html__("Plan Size", 'houzez')
        ),
        array(
            'id'       => 'cl_plan_size_plac',
            'type'     => 'text',
            'title'    => esc_html__("Plan Size Placeholder", 'houzez'),
            'default' => esc_html__("Enter the plan size", 'houzez')
        ),

        array(
            'id'       => 'cl_plan_img',
            'type'     => 'text',
            'title'    => esc_html__("Plan Image", 'houzez'),
            'default' => esc_html__("Plan Image", 'houzez')
        ),
        array(
            'id'       => 'cl_plan_img_plac',
            'type'     => 'text',
            'title'    => esc_html__("Plan Image Placeholder", 'houzez'),
            'default' => esc_html__("Upload the plan image", 'houzez')
        ),
        array(
            'id'       => 'cl_plan_img_btn',
            'type'     => 'text',
            'title'    => esc_html__("Plan Image Button", 'houzez'),
            'default' => esc_html__("Select Image", 'houzez')
        ),
        array(
            'id'       => 'cl_plan_img_size',
            'type'     => 'text',
            'title'    => esc_html__("Plan Image Size", 'houzez'),
            'default' => esc_html__("Minimum size 800 x 600 px", 'houzez')
        ),

        array(
            'id'       => 'cl_plan_des',
            'type'     => 'text',
            'title'    => esc_html__("Plan Description", 'houzez'),
            'default' => esc_html__("Description", 'houzez')
        ),
        array(
            'id'       => 'cl_plan_des_plac',
            'type'     => 'text',
            'title'    => esc_html__("Plan Description Placeholder", 'houzez'),
            'default' => esc_html__("Enter the plan description", 'houzez')
        ),

        array(
            'id'       => 'cl_subl_title',
            'type'     => 'text',
            'title'    => esc_html__("Title", 'houzez'),
            'default' => esc_html__("Title", 'houzez')
        ),

        array(
            'id'       => 'cl_subl_title_plac',
            'type'     => 'text',
            'title'    => esc_html__("Title Placeholder", 'houzez'),
            'default' => esc_html__("Enter the  title", 'houzez')
        ),

        array(
            'id'       => 'cl_subl_bedrooms',
            'type'     => 'text',
            'title'    => esc_html__("Bedrooms", 'houzez'),
            'default' => esc_html__("Bedrooms", 'houzez')
        ),

        array(
            'id'       => 'cl_subl_bedrooms_plac',
            'type'     => 'text',
            'title'    => esc_html__("Bedrooms Placeholder", 'houzez'),
            'default' => esc_html__("Enter the number of bedrooms", 'houzez')
        ),

        array(
            'id'       => 'cl_subl_bathrooms',
            'type'     => 'text',
            'title'    => esc_html__("Bathrooms", 'houzez'),
            'default' => esc_html__("Bathrooms", 'houzez')
        ),
        array(
            'id'       => 'cl_subl_bathrooms_plac',
            'type'     => 'text',
            'title'    => esc_html__("Bedrooms Placeholder", 'houzez'),
            'default' => esc_html__("Enter the number of bathrooms", 'houzez')
        ),

        array(
            'id'       => 'cl_subl_price',
            'type'     => 'text',
            'title'    => esc_html__("Price", 'houzez'),
            'default' => esc_html__("Price", 'houzez')
        ),
        array(
            'id'       => 'cl_subl_price_plac',
            'type'     => 'text',
            'title'    => esc_html__("Price Placeholder", 'houzez'),
            'default' => esc_html__("Enter the price", 'houzez')
        ),

        array(
            'id'       => 'cl_subl_price_postfix',
            'type'     => 'text',
            'title'    => esc_html__("Price Postfix", 'houzez'),
            'default' => esc_html__("Price", 'houzez')
        ),
        array(
            'id'       => 'cl_subl_price_postfix_plac',
            'type'     => 'text',
            'title'    => esc_html__("Price Postfix Placeholder", 'houzez'),
            'default' => esc_html__("Enter the price postfix", 'houzez')
        ),

        array(
            'id'       => 'cl_subl_size',
            'type'     => 'text',
            'title'    => esc_html__("Property Size", 'houzez'),
            'default' => esc_html__("Property Size", 'houzez')
        ),
        array(
            'id'       => 'cl_subl_size_plac',
            'type'     => 'text',
            'title'    => esc_html__("Property Size Placeholder", 'houzez'),
            'default' => esc_html__("Enter the property size", 'houzez')
        ),

        array(
            'id'       => 'cl_subl_size_postfix',
            'type'     => 'text',
            'title'    => esc_html__("Size Postfix", 'houzez'),
            'default' => esc_html__("Size Postfix", 'houzez')
        ),
        array(
            'id'       => 'cl_subl_size_postfix_plac',
            'type'     => 'text',
            'title'    => esc_html__("Size Postfix Placeholder", 'houzez'),
            'default' => esc_html__("Enter the property size postfix", 'houzez')
        ),

        array(
            'id'       => 'cl_subl_type',
            'type'     => 'text',
            'title'    => esc_html__("Property Type", 'houzez'),
            'default' => esc_html__("Property Type", 'houzez')
        ),
        array(
            'id'       => 'cl_subl_type_plac',
            'type'     => 'text',
            'title'    => esc_html__("Property Type Placeholder", 'houzez'),
            'default' => esc_html__("Enter the property type", 'houzez')
        ),

        array(
            'id'       => 'cl_subl_date',
            'type'     => 'text',
            'title'    => esc_html__("Availability Date", 'houzez'),
            'default' => esc_html__("Availability Date", 'houzez')
        ),
        array(
            'id'       => 'cl_subl_date_plac',
            'type'     => 'text',
            'title'    => esc_html__("Availability Date Placeholder", 'houzez'),
            'default' => esc_html__("Enter the availability date", 'houzez')
        ),

        array(
            'id'       => 'cl_subl_ids',
            'type'     => 'text',
            'title'    => esc_html__("Listing IDs", 'houzez'),
            'default' => esc_html__("Listing IDs", 'houzez')
        ),
        array(
            'id'       => 'cl_subl_ids_plac',
            'type'     => 'text',
            'title'    => esc_html__("Listing IDs Placeholder", 'houzez'),
            'default' => esc_html__("Enter the listing IDs comma separated", 'houzez')
        ),
        array(
            'id'       => 'cl_subl_ids_tooltip',
            'type'     => 'text',
            'title'    => esc_html__("Listing IDs Tooltp", 'houzez'),
            'default' => esc_html__("If the sub-properties are separated listings, use the box above to enter the listing IDs (Example: 4,5,6)", 'houzez')
        ),

        array(
            'id'       => 'cl_location_section-end',
            'type'     => 'section',
            'indent'   => false,
        ),
        
    )
));

Redux::setSection( $houzez_opt_name, array(
    'title'  => esc_html__( 'Dashboard Menu', 'houzez' ),
    'id'     => 'dashboard-menu',
    'desc'   => '',
    'subsection'   => true,
    'fields'        => array(
        array(
            'id'       => 'dsh_labels_section-start',
            'type'     => 'section',
            'title'    => '',
            'subtitle' => esc_html__('Manage labels for dashboard menu', 'houzez'),
            'indent'   => true,
        ),

        array(
            'id'       => 'dsh_board',
            'type'     => 'text',
            'title'    => esc_html__("Board", 'houzez'),
            'default' => esc_html__("Board", 'houzez')
        ),
        array(
            'id'       => 'dsh_activities',
            'type'     => 'text',
            'title'    => esc_html__("Activities", 'houzez'),
            'default' => esc_html__("Activities", 'houzez')
        ),
        array(
            'id'       => 'dsh_deals',
            'type'     => 'text',
            'title'    => esc_html__("Deals", 'houzez'),
            'default' => esc_html__("Deals", 'houzez')
        ),
        array(
            'id'       => 'dsh_leads',
            'type'     => 'text',
            'title'    => esc_html__("Leads", 'houzez'),
            'default' => esc_html__("Leads", 'houzez')
        ),
        array(
            'id'       => 'dsh_inquiries',
            'type'     => 'text',
            'title'    => esc_html__("Inquiries", 'houzez'),
            'default' => esc_html__("Inquiries", 'houzez')
        ),
        array(
            'id'       => 'dsh_insight',
            'type'     => 'text',
            'title'    => esc_html__("Insight", 'houzez'),
            'default' => esc_html__("Insight", 'houzez')
        ),
        array(
            'id'       => 'dsh_props',
            'type'     => 'text',
            'title'    => esc_html__("Properties", 'houzez'),
            'default' => esc_html__("Properties", 'houzez')
        ),
        array(
            'id'       => 'dsh_all',
            'type'     => 'text',
            'title'    => esc_html__("All", 'houzez'),
            'default' => esc_html__("All", 'houzez')
        ),
        array(
            'id'       => 'dsh_published',
            'type'     => 'text',
            'title'    => esc_html__("Published", 'houzez'),
            'default' => esc_html__("Published", 'houzez')
        ),
        array(
            'id'       => 'dsh_pending',
            'type'     => 'text',
            'title'    => esc_html__("Pending", 'houzez'),
            'default' => esc_html__("Pending", 'houzez')
        ),
        array(
            'id'       => 'dsh_expired',
            'type'     => 'text',
            'title'    => esc_html__("Expired", 'houzez'),
            'default' => esc_html__("Expired", 'houzez')
        ),
        array(
            'id'       => 'dsh_draft',
            'type'     => 'text',
            'title'    => esc_html__("Draft", 'houzez'),
            'default' => esc_html__("Draft", 'houzez')
        ),
        array(
            'id'       => 'dsh_hold',
            'type'     => 'text',
            'title'    => esc_html__("On Hold", 'houzez'),
            'default' => esc_html__("On Hold", 'houzez')
        ),
        array(
            'id'       => 'dsh_disapproved',
            'type'     => 'text',
            'title'    => esc_html__("Disapproved", 'houzez'),
            'default' => esc_html__("Disapproved", 'houzez')
        ),
        array(
            'id'       => 'dsh_create_listing',
            'type'     => 'text',
            'title'    => esc_html__("Create a Listing", 'houzez'),
            'default' => esc_html__("Create a Listing", 'houzez')
        ),
        array(
            'id'       => 'dsh_favorite',
            'type'     => 'text',
            'title'    => esc_html__("Favorites", 'houzez'),
            'default' => esc_html__("Favorites", 'houzez')
        ),
        array(
            'id'       => 'dsh_messages',
            'type'     => 'text',
            'title'    => esc_html__("Messages", 'houzez'),
            'default' => esc_html__("Messages", 'houzez')
        ),
        array(
            'id'       => 'dsh_saved_searches',
            'type'     => 'text',
            'title'    => esc_html__("Saved Searches", 'houzez'),
            'default' => esc_html__("Saved Searches", 'houzez')
        ),
        array(
            'id'       => 'dsh_membership',
            'type'     => 'text',
            'title'    => esc_html__("Membership", 'houzez'),
            'default' => esc_html__("Membership", 'houzez')
        ),
        array(
            'id'       => 'dsh_invoices',
            'type'     => 'text',
            'title'    => esc_html__("Invoices", 'houzez'),
            'default' => esc_html__("Invoices", 'houzez')
        ),
        array(
            'id'       => 'dsh_profile',
            'type'     => 'text',
            'title'    => esc_html__("My Profile", 'houzez'),
            'default' => esc_html__("My Profile", 'houzez')
        ),
        array(
            'id'       => 'dsh_gdpr',
            'type'     => 'text',
            'title'    => esc_html__("GDPR Request", 'houzez'),
            'default' => esc_html__("GDPR Request", 'houzez')
        ),
        array(
            'id'       => 'dsh_agents',
            'type'     => 'text',
            'title'    => esc_html__("Agents", 'houzez'),
            'default' => esc_html__("Agents", 'houzez')
        ),
        array(
            'id'       => 'dsh_addnew',
            'type'     => 'text',
            'title'    => esc_html__("Add New", 'houzez'),
            'default' => esc_html__("Add New", 'houzez')
        ),
        array(
            'id'       => 'dsh_logout',
            'type'     => 'text',
            'title'    => esc_html__("Log Out", 'houzez'),
            'default' => esc_html__("Log Out", 'houzez')
        ),
    

        array(
            'id'       => 'dsh_labels_section-end',
            'type'     => 'section',
            'indent'   => false,
        ),
    )
));