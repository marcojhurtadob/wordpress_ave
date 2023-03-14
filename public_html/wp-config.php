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
define( 'DB_NAME', "u259360998_soyave" );

/** Database username */
define( 'DB_USER', "u259360998_soyave" );

/** Database password */
define( 'DB_PASSWORD', "ZZ\$!5pH@h~0" );

/** Database hostname */
define( 'DB_HOST', "localhost" );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',          '!~YfRpAo6SFtTd#+`Ix[$E|P1pzehyK;4D=}je!0@>|sPr,m@i#P`XH]QI7Nk(}#' );
define( 'SECURE_AUTH_KEY',   'zAE!6E~8ui#Mt@4xK,ODTAr@.7cvX+CLYNJ|UB~Z5%S12W[3Lmv@+[m#3)@?Z$D{' );
define( 'LOGGED_IN_KEY',     'c/ITm:~8g/>:_``R]sa 0M&w0mX!E4<{~^n>ZaLrL1>LFjbgx!t72,^xQCR$cs~)' );
define( 'NONCE_KEY',         '0w@*RL!^>ZQ?|?t{$k:bUe8 ?;vdmC`;rOiE[PCj^+C5(yJQLt.^`_0qh75p/SK5' );
define( 'AUTH_SALT',         'RMMNL(rtAi(g.zsf8Kcp|%z1B830MK]gEIR@a^]@u#+[@2{WXMz,bNY#&SB- &3i' );
define( 'SECURE_AUTH_SALT',  'jX<a#RQr4!|Cju|sm3pt`ygeKFv7:Kib*W0vwJ(9G#e;:@w71lf!cA|uy,ZsQS}[' );
define( 'LOGGED_IN_SALT',    '55qbNeNf:iI}.esGM6%},,$9b%tF!JS3Z]zP:}i4Ug=W(q.7vf,G)!n0> v[B>&+' );
define( 'NONCE_SALT',        'MEC$Uv{T7/<zw1?6D/${riwj`*2)LY-Lw4x{,4m!(<ph:iRdZ4Fidkvz}miSxA@0' );
define( 'WP_CACHE_KEY_SALT', 'WjY`01x~B:w;eb^P{;t}6fw~VdWVl+5Hpy*Q!UYks2L<@D8wPBCk}N9cP7.I#t!c' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
define( 'WP_DEBUG', false );

define( 'MULTISITE', true );
define( 'SUBDOMAIN_INSTALL', false );
define( 'DOMAIN_CURRENT_SITE', 'soyave.online' );
define( 'PATH_CURRENT_SITE', '/' );
define( 'SITE_ID_CURRENT_SITE', 1 );
define( 'BLOG_ID_CURRENT_SITE', 1 );

/* Add any custom values between this line and the "stop editing" line. */



define( 'FS_METHOD', 'direct' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
define( 'DUPLICATOR_AUTH_KEY', 'q)414v|hB?As}n#_8^CuC`n%nV3fgkBC1L+o%Ku+jZ-CHo2PXkycuvqY}$)jra8@' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname(__FILE__) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';



define('WP_ALLOW_MULTISITE', true);