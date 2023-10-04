<?php
// Add the country select field to the state taxonomy
function add_country_field_to_state_taxonomy() {
    // Get the taxonomy object for the state taxonomy
    $taxonomy = get_taxonomy( 'property_state' );

    // Add the country select field to the state taxonomy
    add_action( "{$taxonomy->name}_add_form_fields", 'render_country_field' );
    add_action( "{$taxonomy->name}_edit_form_fields", 'render_country_field', 10, 2 );

    // Save the country field value when the state term is saved
    add_action( "created_{$taxonomy->name}", 'save_country_field' );
    add_action( "edited_{$taxonomy->name}", 'save_country_field' );
}

// Render the country select field
function render_country_field( $taxonomy_term = null ) {
    $country = '';
    if ( isset( $taxonomy_term->taxonomy ) && $taxonomy_term->taxonomy === 'property_state' ) {
        // Get the currently selected country
        $country = get_term_meta( $taxonomy_term->term_id, 'houzez_country', true );
    }

    // Get the list of countries from the country taxonomy
    $countries = get_terms( array(
        'taxonomy' => 'property_country',
        'hide_empty' => false,
    ) );

    // Output the country select field
    ?>
    <div class="form-field term-group">
        <label for="country"><?php esc_html_e( 'Country', 'text-domain' ); ?></label>
        <select name="houzez_country" id="houzez_country">
            <?php foreach ( $countries as $country_term ) : ?>
                <option value="<?php echo esc_attr( $country_term->term_id ); ?>" <?php selected( $country, $country_term->term_id ); ?>><?php echo esc_html( $country_term->name ); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php
}


// Save the country field value
function save_country_field( $term_id ) {
    if ( isset( $_POST['houzez_country'] ) ) {
        // Sanitize the country value
        $country = sanitize_text_field( $_POST['houzez_country'] );

        // Save the country value as term meta
        update_term_meta( $term_id, 'houzez_country', $country );
    }
}

add_action( 'init', 'add_country_field_to_state_taxonomy' );