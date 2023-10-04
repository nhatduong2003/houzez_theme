<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}


define('AUTH_KEY',         'mi+rdhMKf1cn4n/DVpue1RyAl54uDPZa5Le5pomDSwkTX9p2Hwb40ZHZIJ+sjJdCOQ/AwP5IkXqHIoaX87I5fQ==');
define('SECURE_AUTH_KEY',  'DAyCUrx9iEZ8bhGG9bDHr123lD6nw6gjj08BjVv4WgBvWBnPFugipngYi9v8vPc0N9QEHPhVqOvtsWPL/IqMAg==');
define('LOGGED_IN_KEY',    '0cA6FSUnmSdSxJTwoIl08ya8HQ7E8ShLysTp38Dm4VEIYGCAtQnLKS5+hbOTBhYib/sjxTc44NmAGzJS0UmAVA==');
define('NONCE_KEY',        'nGuHni5PLSJgZdoL6pZVQ4Bt/M8WgEY5iBAj/h3XTvOmKVSlFz+wR2nMaCkcwWGHC8Ql++9JbdupIec5fcj4Xw==');
define('AUTH_SALT',        'ZLKiZUaVf9rq5zAZJh0rUTmx4ZHnGgPi12GGnoj7phnXYWxmH9nhZ8IoZ2FzCiAvTGFCIbPtqY0hQ2bJ4k3pTw==');
define('SECURE_AUTH_SALT', 'DQY47BAhoEaKQGzUp2hYubS97nrV/qx4EMjxC5vpwiRjhiAVn8nFdYpq+Yb3eyGyVHjA/I9/0A89EzabOcJxZQ==');
define('LOGGED_IN_SALT',   'J6aTtiT/R6n1qd1QtB6La5MGdcF71qUm5Ts501Xy06EE2ep7e/PF+kZvg5mdqiLQJCKTVwBZUIAPXRo+5fUqbg==');
define('NONCE_SALT',       '8JJLoPNjGPZvo7a7z6M7d4W39zPpded45RZ9bP4WcAxLcCz0PwNMlyvKmqcyNkFKnxYdBfyej+O46kwDv8gJUQ==');
define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
