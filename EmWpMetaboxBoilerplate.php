<?php
/**
Class file.
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

if (!class_exists('EmWpMetaboxBoilerplate')) {
    /**
    Declaring class

    @category Wordpress_Plugin
    @package  Esmond-M
    @author   Esmond Mccain <esmondmccain@gmail.com>
    @license  https://www.gnu.org/licenses/gpl-3.0.en.html GNU General Public License
    @link     esmondmccain.com
    @return
     */

    class EmWpMetaboxBoilerplate
    {
        //begin class


        /**
      Declaring constructor
         */
        public function __construct()
        {

            add_action(
                'add_meta_boxes',
                [$this, 'emCustomThemePostTitleHideMetabox']
            );
            add_action(
                'save_post',
                [$this, 'emCustomThemePostTitleHiddenSaveMetaboxes']
            );
            add_action(
                'wp_head',
                [$this, 'emCustomThemeHidePostTitle']
            );
            add_action(
                'manage_page_posts_columns',
                [$this, 'addPostTitleHiddenQuickEditColumn'],
                10, 1
            ); //add custom column
            add_action(
                'manage_post_posts_columns',
                [$this, 'addPostTitleHiddenQuickEditColumn'],
                10, 1
            ); //add custom column
            add_action(
                'manage_posts_custom_column',
                [$this, 'managePostTitleHiddenQuickEditColumn'],
                10, 2
            );  //populate column
            add_action(
                'manage_pages_custom_column',
                [$this, 'managePostTitleHiddenQuickEditColumn'],
                10, 2
            );  //populate column
            add_action(
                'quick_edit_custom_box',
                [$this, 'displayQuickEditCustom'],
                10, 2
            );
            add_action(
                'admin_enqueue_scripts',
                [$this, 'postTitleHiddenSettingQuickEdit']
            ); //enqueue admin script (for pre-populating fields with JS)
    
        }


        /**
         Adding meta box for post

        @return void
         */
        public function emCustomThemePostTitleHideMetabox()
        {
            $screens = ['post', 'page'];
            foreach ($screens as $screen) {
                add_meta_box(
                    'em_metaboxbox_id',    // Unique ID
                    'Hide Post title?',  // Box title
                    array($this, 'emCustomThemePostTitleHideHtml'),
                    $screen,                  // Post type
                    'side',
                    'high'
                );
            }
        }
        /**
        Styles for custom metabox on backend

        @param $post callback

        @return callable
         */
        public function emCustomThemePostTitleHideHtml($post)
        {
            $meta = get_post_meta($post->ID);
            $post_title_hidden_value = (
                isset($meta['post_title_hidden_value'][0])
                &&  '1' === $meta['post_title_hidden_value'][0]) ? 1 : 0;
            wp_nonce_field(
                'post_title_hidden_control_meta_box',
                'post_title_hidden_control_meta_box_nonce'
            ); // adding nonce to meta box.
            ?>
            <style type="text/css">
                .post_meta_extras p {
                    margin: 20px;
                }

                .post_meta_extras label {
                    display: block;
                    margin-bottom: 10px;
                }
            </style>
            <div class="post_meta_extras">
                <p>
                    <label>Yes <input type="checkbox" name="post_title_hidden_value" value="1"
                            <?php checked(
                                $post_title_hidden_value,
                                1
                            );
                            ?> />
                        <?php esc_attr_e(
                            '',
                            'post_title_hidden'
                        );
                        ?>
                    </label>
                </p>
            </div>
            <?php
        }
    
        /**
        Save meta box value to database

        @param $post_id of wordpress post

        @return integer
         */
        public function emCustomThemePostTitleHiddenSaveMetaboxes($post_id)
        {
            /*
            * We need to verify this came from the
            *  our screen and with proper authorization,
            * because save_post can be triggered at
            *  other times. Add as many nonces, as you
            * have metaboxes.
               */
            if (!isset($_POST['post_title_hidden_control_meta_box_nonce'])
                || !wp_verify_nonce(
                    sanitize_key(
                        $_POST['post_title_hidden_control_meta_box_nonce']
                    ),
                    'post_title_hidden_control_meta_box'
                )
            ) { // Input var okay.
                return $post_id;
            }

            // Check the user's permissions.
            if (isset($_POST['post_type'])
                && 'page' === $_POST['post_type']
            ) { // Input var okay.
                if (!current_user_can(
                    'edit_page', $post_id
                )
                ) {
                    return $post_id;
                }
            } else {
                if (!current_user_can('edit_post', $post_id)) {
                    return $post_id;
                }
            }

            /*
               * If this is an autosave, our form has not been submitted,
               * so we don't want to do anything.
               */
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return $post_id;
            }

            /* Ok to save */

            $post_title_hidden_value = (
                isset($_POST['post_title_hidden_value'])
                && '1' === $_POST['post_title_hidden_value']
            ) ? 1 : 0; // Input var okay.
            update_post_meta(
                $post_id,
                'post_title_hidden_value',
                esc_attr($post_title_hidden_value)
            );
        }
        /**
        Insert html to hide post title

        @return void
         */
        public function emCustomThemeHidePostTitle()
        {

              $mykey_values = get_post_custom_values('post_title_hidden_value');
            if (isset($mykey_values)) {
                foreach ($mykey_values as $key => $value) {
                    if ($value == 1) {
                        echo "<style>
                        h1.entry-title {
                          display: none!important;
                        }       
                       </style>";
                    }
                }
            }
        }
        /**
        Add a custom column to quick edit screen

        @param $columns

        @return void
         */
        public function addPostTitleHiddenQuickEditColumn($columns)
        {
            $new_columns = array();
            $new_columns['post_title_hidden_value'] = 'Post title set';
            return array_merge($columns, $new_columns);
        }
        /**
         * Customise the data for our custom column,
         * it's here we pull in metadata info for each post/page.
         * These will be referred to in a JavaScript file for pre-populating
         * our quick-edit screen

        @param $column_name
        @param $post_id

        @return integer/string
         */
        public function managePostTitleHiddenQuickEditColumn($column_name, $post_id)
        {

            $html = '';

            if ($column_name == 'post_title_hidden_value') {
                $post_title_hidden_value = get_post_meta(
                    $post_id, 'post_title_hidden_value', true
                );
                $html .= '<div id="post_title_hidden_value' . $post_id . '">';
                if ($post_title_hidden_value == 1) {
                     $html .= 'hidden';
                }
                if ($post_title_hidden_value == 0
                    || empty($post_title_hidden_value)
                ) {
                    $html .= 'shown';
                }
                $html .= '</div>';
            }

            echo $html;
        }
        /**
         * Display our custom content on the quick-edit interface,
         * no values can be pre-populated (all done in JavaScript).

        @param $column

        @return void
         */
        public function displayQuickEditCustom($column)
        {
            $html = '';

            wp_nonce_field(
                'post_title_hidden_control_meta_box',
                'post_title_hidden_control_meta_box_nonce'
            );// adding nonce to meta box.
            //output post featured checkbox
            if ($column == 'post_title_hidden_value') {
                 $html .= '<fieldset class="inline-edit-col-left clear">';
                 $html .= '<div class="inline-edit-group wp-clearfix">';
                 $html .= '<label class="alignleft" for="enable">
                 <span style="margin-left:5px;width:8rem;margin-right:-2rem;"
                 class="title">Hide Post title?</span>';
                 $html .= '<input type="radio" name="post_title_hidden_value" 
                 id="enable" value="1"/>';
                 $html .= '<span class="checkbox-title">Hide title</span></label>';
                 $html .= '<label class="alignleft" for="disable">';
                 $html .= '<input type="radio" name="post_title_hidden_value"
                 id="disable" value="0"/>';
                 $html .= '<span class="checkbox-title">Show title</span></label>';
                 $html .= '</div>';
                 $html .= '</fieldset>';
            }

             echo $html;
        }
        /**
        Enqueues script for quick editing on backend

        @return void
         */
        public function postTitleHiddenSettingQuickEdit()
        {
            wp_enqueue_script(
                'quick-edit-script',
                plugin_dir_url(__FILE__) .
                 'assets/post_title_hidden_setting_quick_edit.js',
                ['jquery','inline-edit-post']
            );
        }

    }

}

