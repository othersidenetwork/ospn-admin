<?php

if (!current_user_can('manage_options')) {
    wp_die(
        '<h1>' . __( 'Cheatin&#8217; uh?' ) . '</h1>' .
        '<p>' . __( 'You are not allowed to manage members.' ) . '</p>',
        403
    );
}

if ( isset($_REQUEST['action']) && 'ospn-admin-add-member' == $_REQUEST['action'] ) {
    check_admin_referer('add-member', '_wpnonce_add-member');
    error_log("request: " . json_encode($_REQUEST), 4);
    $member_name = $_REQUEST['member_name'];
    if ($member_name == null || $member_name == "") {
        $url = add_query_arg(array('update' => 'enter_name'), $_REQUEST['_wp_http_referer']);
        wp_redirect($url);
        die();
    }
}

$title = __('Add New Member');
?>
<div class="wrap">
<h1 id="add-new-member"><?php echo _x('Add New Member', 'member'); ?></h1>
    <?php if (isset($errors) && is_wp_error($errors)) : ?>
        <div class="error">
            <ul>
                <?php
                foreach ($errors->get_error_messages() as $err)
                    echo "<li>$err</li>\n";
                ?>
            </ul>
        </div>
    <?php endif;

    if (!empty($messages)) {
        foreach ($messages as $msg)
            echo '<div id="message" class="updated notice is-dismissible"><p>' . $msg . '</p></div>';
    } ?>

    <?php if ( isset($add_member_errors) && is_wp_error( $add_member_errors ) ) : ?>
        <div class="error">
            <?php
            foreach ( $add_member_errors->get_error_messages() as $message )
                echo "<p>$message</p>";
            ?>
        </div>
    <?php endif; ?>
    <p><?php _e('Create a brand new member and add them to this site.'); ?></p>
    <form method="post" action="<?php echo add_query_arg(array('page' => $_REQUEST['page']), admin_url('admin-post.php')); ?>">
        <input type="hidden" name="action" value="ospn-admin-add-member" />
        <input type="hidden" name="page" value="" />
        <?php wp_nonce_field('add-member', '_wpnonce_add-member'); ?>

        <table class="form-table">
            <tr class="form-field form-required">
                <th scope="row"><label for="member_name"><?php _e('Name'); ?> <span class="description"><?php _e('(required)'); ?></span></label></th>
                <td><input style="width: 350px;" name="member_name" type="text" id="member_name" value="<?php echo esc_attr($new_member_name); ?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="60" /></td>
            </tr>
            <tr class="form-field form-required">
                <th scope="row"><label for="member_website"><?php _e('Website'); ?> <span class="description"><?php _e('(required)'); ?></span></label></th>
                <td><input style="width: 350px;" name="member_website" type="text" id="member_website" value="<?php echo esc_attr($new_member_website); ?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="60" /></td>
            </tr>
            <tr class="form-field form-required">
                <th scope="row"><label for="member_podcast_feed"><?php _e('Podcast RSS Feed'); ?> <span class="description"><?php _e('(required)'); ?></span></label></th>
                <td><input style="width: 350px;" name="member_podcast_feed" type="text" id="member_podcast_feed" value="<?php echo esc_attr($new_member_podcast_feed); ?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="60" /></td>
            </tr>
            <tr class="form-field form-required">
                <th scope="row"><label for="member_host"><?php _e('Host'); ?> <span class="description"><?php _e('(required)'); ?></span></label></th>
                <td><input style="width: 350px;" name="member_host" type="text" id="member_host" value="<?php echo esc_attr($new_member_host); ?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="60" /></td>
            </tr>
            <tr class="form-field form-required">
                <th scope="row"><label for="member_contact"><?php _e('Contact Email'); ?> <span class="description"><?php _e('(required)'); ?></span></label></th>
                <td><input style="width: 350px;" name="member_contact" type="text" id="member_contact" value="<?php echo esc_attr($new_member_contact); ?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="60" /></td>
            </tr>
            <tr class="form-field">
                <th scope="row"><label for="member_twitter_handle"><?php _e('Twitter handler'); ?></label></th>
                <td><input style="width: 350px;" name="member_twitter_handle" type="text" id="member_twitter_handle" value="<?php echo esc_attr($new_member_twitter_handle); ?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="60" /></td>
            </tr>
            <tr class="form-field">
                <th scope="row"><label for="member_facebook_url"><?php _e('Facebook URL'); ?></label></th>
                <td><input style="width: 350px;" name="member_facebook_url" type="text" id="member_facebook_url" value="<?php echo esc_attr($new_member_facebook_url); ?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="60" /></td>
            </tr>
            <tr class="form-field">
                <th scope="row"><label for="member_google_plus_url"><?php _e('Google+ URL'); ?></label></th>
                <td><input style="width: 350px;" name="member_google_plus_url" type="text" id="member_google_plus_url" value="<?php echo esc_attr($new_member_google_plus_url); ?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="60" /></td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Active') ?></th>
                <td><label for="member_active"><input type="checkbox" name="member_active" id="member_active" value="1" <?php checked($new_member_active); ?> /> <?php _e('Set new member as active.'); ?></label></td>
            </tr>
        </table>
        <?php submit_button(__('Add New Member'), 'primary', 'createmember', true, array('id' => 'createmembersub')); ?>
    </form>
</div>