<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

/*===============================
=            GLOBALS            =
===============================*/

define('DISALLOW_FILE_EDIT', true); // No to file editing
define('WP_DEFAULT_THEME', 'free-flex'); // Set your default theme


// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'scotchbox');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'dqR,kEGz-Wt{#tIU2C]P?BxZo^Kl-|u)9{[g=;yvU&K6+J{A+<By1G#S*ZJo~x2!');
define('SECURE_AUTH_KEY',  '4EoqgGk2ORe*E_^Gtp-_etI+hWA6zH4Wlusqq4M1=RmHwwPn|K-VD`1pZsb|G-oc');
define('LOGGED_IN_KEY',    'd#G2kUtje.|PcWmP%#m(c[~ICEE>|m#IDF#8n:qEzA1Z]/*ZXMKp u+v.ZI(sAu!');
define('NONCE_KEY',        'ZoLUO,|c=Se4K ZQ+EH`Z8hb7z>Z2+r7&1|ZS: |Yr 9W)-c9OZe=w__3|E:jK>X');
define('AUTH_SALT',        '?9B| `v OY#X-N(+j@%;|pNj92x_SAWqr2+k 9w+/q-3Zbu(-u5|+LAIk{jnei_0');
define('SECURE_AUTH_SALT', '<eN<j$2F[]@%_p|PiEE8*cyE6e$(Bq 9o#BPBb4?k|oWj- s]2] t6k~q{@5Q|~+');
define('LOGGED_IN_SALT',   '2?Uw%g?sxmIm-xaCsN2 ,w({-?e+Q7g89<[GKe9hU82o_chXni^aL[(5gV~CHg)A');
define('NONCE_SALT',       'bQ(({^$P>O5h^.X1juTP5**W0yU7+d$6j_C,{kfDCnv +c]NuXRPb%$b]uU-:[L4');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'fk_wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
    define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
