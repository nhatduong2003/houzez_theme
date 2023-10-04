<?php

require_once __DIR__ . '/class-library-source.php';
require_once __DIR__ . '/class-library.php';

add_action('init', function () {
    if (defined('ELEMENTOR_VERSION')) {
        new Houzez_Library;
    }
});
