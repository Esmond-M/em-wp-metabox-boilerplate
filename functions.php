<?php


  //adding meta box for post
  function em_custom_theme_post_title_hide_metabox()
  {
    $screens = ['post'];
    foreach ($screens as $screen) {
      add_meta_box(
        'em_metaboxbox_id',           // Unique ID
        'Hide Post title?',  // Box title
        'em_custom_theme_post_title_hide_html',  // Content callback, must be of type callable
        $screen,                  // Post type
        'side',
        'high'
      );
    }
  }
  add_action('add_meta_boxes', 'em_custom_theme_post_title_hide_metabox');
  function em_custom_theme_post_title_hide_html($post)
  {
  
    $meta = get_post_meta($post->ID);
    $post_title_hidden_value = (isset($meta['post_title_hidden_value'][0]) &&  '1' === $meta['post_title_hidden_value'][0]) ? 1 : 0;
    wp_nonce_field('post_title_hidden_control_meta_box', 'post_title_hidden_control_meta_box_nonce'); // Always add nonce to your meta boxes!
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
                  <?php checked($post_title_hidden_value, 1); ?> /><?php esc_attr_e('', 'post_title_hidden'); ?></label>
      </p></div>
      <?php
  }
  
  function em_custom_theme_post_title_hidden_save_metaboxes($post_id)
  {
  
    /*
       * We need to verify this came from the our screen and with proper authorization,
       * because save_post can be triggered at other times. Add as many nonces, as you
       * have metaboxes.
       */
    if (!isset($_POST['post_title_hidden_control_meta_box_nonce']) || !wp_verify_nonce(sanitize_key($_POST['post_title_hidden_control_meta_box_nonce']), 'post_title_hidden_control_meta_box')) { // Input var okay.
      return $post_id;
    }
  
    // Check the user's permissions.
    if (isset($_POST['post_type']) && 'page' === $_POST['post_type']) { // Input var okay.
      if (!current_user_can('edit_page', $post_id)) {
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
  
    $post_title_hidden_value = (isset($_POST['post_title_hidden_value']) && '1' === $_POST['post_title_hidden_value']) ? 1 : 0; // Input var okay.
    update_post_meta($post_id, 'post_title_hidden_value', esc_attr($post_title_hidden_value));
  }
  
  add_action('save_post', 'em_custom_theme_post_title_hidden_save_metaboxes');
  
  function em_custom_theme_hide_post_title(){
      
      $mykey_values = get_post_custom_values( 'post_title_hidden_value' );
      if(isset($mykey_values)){
    foreach ( $mykey_values as $key => $value ) {
      if ($value == 1){
         echo "<style>
                h1.entry-title {
                  display: none!important;
                }       
               </style>";
      }
    }
      }
          }
  add_action( 'wp_head', 'em_custom_theme_hide_post_title' );