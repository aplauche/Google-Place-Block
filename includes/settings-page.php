<?php

function fsdgpb_register_my_api_keys_page() {
    add_submenu_page(
        'tools.php',
        'API Keys',
        'API Keys',
        'manage_options',
        'api-keys',
        'fsdgpb_add_api_keys_callback' );
}
 
// The admin page containing the form
function fsdgpb_add_api_keys_callback() { ?>

    <?php

      $data_encryption = new FSDHH\Data_Encryption();
      $api_key = get_option('google_api_key');

      if($api_key){
        $api_key = $data_encryption->decrypt( get_option('google_api_key'));
      }


    ?>


    <div class="wrap"><div id="icon-tools" class="icon32"></div>
        <h2>Happy Hour Settings</h2>
        <?php

          // Check if status is 1 which means a successful options save just happened
          if(isset($_GET['status']) && $_GET['status'] == 1): ?>
            
            <div class="notice notice-success inline">
              <p>Options Saved!</p>
            </div>

          <?php endif;

        ?>
        <form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">

            <h3>Your Google API Key</h3>

            <!-- The nonce field is a security feature to avoid submissions from outside WP admin -->
            <?php wp_nonce_field( 'fsdgpb_options_verify'); ?>

            <input type="password" name="google_api_key" placeholder="Enter API Key" value="<?php echo $api_key ? esc_attr( $api_key ) : '' ; ?>">
            <input type="hidden" name="action" value="process_google_form">			 
            <input type="submit" name="submit" id="submit" class="update-button button button-primary" value="Update API Key"  />
        </form> 
    </div>
    <?php
}

// Submit functionality
function fsdgpb_submit_api_key() {

    // Make sure user actually has the capability to edit the options
    if(!current_user_can( 'edit_theme_options' )){
      wp_die("You do not have permission to view this page.");
    }
  
    // pass in the nonce ID from our form's nonce field - if the nonce fails this will kill script
    check_admin_referer( 'fsdgpb_options_verify');


    if (isset($_POST['google_api_key'])) {

        $data_encryption = new FSDHH\Data_Encryption();
        $api_key = sanitize_text_field( $_POST['google_api_key'] );
        $encrypted_api_key = $data_encryption->encrypt($api_key);

        $api_exists = get_option('google_api_key');

        if (!empty($api_key) && !empty($api_exists)) {
            update_option('google_api_key', $encrypted_api_key);
        } else {
            add_option('google_api_key', $encrypted_api_key);
        }
    }
    wp_redirect($_SERVER['HTTP_REFERER'] . '&status=1');
}

add_action( 'admin_post_nopriv_process_google_form', 'fsdgpb_submit_api_key' );
add_action( 'admin_post_process_google_form', 'fsdgpb_submit_api_key' );