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
/**
 * Define global constants

 * @param $constant_name
 * @param $value
 *
 * @return array
 */
function emWpMetaBoxesConstants($constant_name, $value)
{
    $constant_name_prefix = 'EM_WP_MetaBoxes_Constants_';
    $constant_name = $constant_name_prefix . $constant_name;
    if (!defined($constant_name))
        define($constant_name, $value);
}
emWpMetaBoxesConstants('DIR', dirname(plugin_basename(__FILE__)));
emWpMetaBoxesConstants('BASE', plugin_basename(__FILE__));
emWpMetaBoxesConstants('URL', plugin_dir_url(__FILE__));
emWpMetaBoxesConstants('PATH', plugin_dir_path(__FILE__));
emWpMetaBoxesConstants('SLUG', dirname(plugin_basename(__FILE__)));
require  EM_WP_MetaBoxes_Constants_PATH
    . 'includes/classes/EmWpMetaboxBoilerplate.php';
use EmWpMetaboxBoilerplate\EmWpMetaboxBoilerplate;

new EmWpMetaboxBoilerplate;


