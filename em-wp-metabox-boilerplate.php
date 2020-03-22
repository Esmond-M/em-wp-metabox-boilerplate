<?php

/**
Main plugin file.
PHP version 7.3

@category Wordpress_Plugin
@package  Esmond-M
@author   Esmond Mccain <esmondmccain@gmail.com>
@license  https://www.gnu.org/licenses/gpl-3.0.en.html GNU General Public License
@link     esmondmccain.com
@return
 */
declare(strict_types=1);
namespace EmWpMetaboxBoilerplate;
/**
* Plugin Name: EM-WP-MetaBoxes
* Plugin URI: https://esmondmccain.com
// phpcs:disable
* Description: Wordpress plugin boilerplate for adding metaboxes to a client's Wordpress websites. Currently this plugin hides the post or page title of the Twenty Seventeen Wordpress theme.
* // phpcs:enable
* Version: 1.0
* Author: Esmond Mccain
* Author URI: https://esmondmccain.com
*/
defined('ABSPATH') or die();

require plugin_dir_path(__FILE__) . 'EmWpMetaboxBoilerplate.php';
use EmWpMetaboxBoilerplate\EmWpMetaboxBoilerplate;

new EmWpMetaboxBoilerplate;


