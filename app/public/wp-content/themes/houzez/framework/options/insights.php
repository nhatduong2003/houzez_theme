<?php
global $houzez_opt_name, $allowed_html_array;
Redux::setSection( $houzez_opt_name, array(
    'title'  => esc_html__( 'Insights Options', 'houzez' ),
    'id'     => 'favethemes-insights',
    'desc'   => '',
    'icon'   => 'el el-cog',
    'fields'        => array(
        array(
            'id'       => 'insights_removal',
            'type'     => 'button_set',
            'title'    => esc_html__('Insights Clearance', 'houzez'),
            'subtitle' => esc_html__('Delete data older then?', 'houzez'),
            'options'  => array(
                'keep'   => esc_html__( 'Always Keep', 'houzez' ),
                '7days'   => esc_html__( '7 Days', 'houzez' ),
                '14days'   => esc_html__( '14 Days', 'houzez' ),
                '30days'   => esc_html__( '30 Days', 'houzez' ),
                '60days'   => esc_html__( '60 Days', 'houzez' ),
                'custom'   => esc_html__( 'Custom', 'houzez' ),
            ),
            'default'  => '60days',
        ),
        array(
            'id'       => 'custom_days',
            'type'     => 'text',
            'required' => array('insights_removal', '=', 'custom'),
            'title'    => esc_html__( 'Number of Days', 'houzez' ),
            'default'    => '90',
            'validate' => 'numeric',
        ),

    ),
));