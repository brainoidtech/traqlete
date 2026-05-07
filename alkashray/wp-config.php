<?php
define( 'WP_CACHE', true );

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
define( 'DB_NAME', 'u762996292_kZRQI' );

/** Database username */
define( 'DB_USER', 'u762996292_tx9NP' );

/** Database password */
define( 'DB_PASSWORD', 'IZpPRUOmNK' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

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
define( 'AUTH_KEY',          '.&<pRJI3txx<XC/=WJ8c%lT4R|S DgW(TcwO{vlr$Gw3Pkk&}kA_9> E.Ie+K0A^' );
define( 'SECURE_AUTH_KEY',   '`8Cjvqs<eA8wYsx/;,74eJe7:yx-_@c*6v]5zaxFmd80<otr|G?io@},sJ/|(JV7' );
define( 'LOGGED_IN_KEY',     'vqZ*0t}`!2W*47qFlO2Jq2{x}z(HJdY}o_fF_-sC+|-`&FzBoF|dutTx<;%CAs.O' );
define( 'NONCE_KEY',         '|+-Lm>P2#3j0%%.0%kjJ>hkvEHzmLLH b:tG[}}g[treM$: I+I!/u=b!Wub15CX' );
define( 'AUTH_SALT',         '?uE(]s0n!^E$6M`E+B|?Kz!~v[`VKtriBLLgekj3PP]m}n7JB;V~o>;E2o*3:XYj' );
define( 'SECURE_AUTH_SALT',  'efb13Y+Gr14#SJ7C8-wJ9Wfc4QF$!z=L=iBV/?nXS*>j1z[JhyYQZO#x[|L&_OK^' );
define( 'LOGGED_IN_SALT',    'TCh>hcp>O;]r.p@CX#4/1|(3X-%gbY}D0v8i|[3$ErabATMXe;^`lq>.}BJ3@6/t' );
define( 'NONCE_SALT',        '!R(8]P vZwx$$]^G{bg_TgC$1QdA?.3&4>y3eoo?&RRwPSTy8[>9*YDzO9#jJtO:' );
define( 'WP_CACHE_KEY_SALT', '6.gDNyT$}lonqE81&L3`|4==B!|J~Yk]9Y[:4rYEg*:2EU )mbyZv__GiVubmktg' );


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

define( 'FS_METHOD', 'direct' );
define( 'COOKIEHASH', 'aa54e3f19c0d475d92b57a31feb9bc05' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
