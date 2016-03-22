<?php

if (!current_user_can('manage_options')) {
    wp_die(
        '<h1>' . __( 'Cheatin&#8217; uh?' ) . '</h1>' .
        '<p>' . __( 'You are not allowed here.' ) . '</p>',
        403
    );
}

use OSPN\Form\OSPN_Podcast_Form;

/** @global OSPN_Podcast_Form $podcast_form */
global $podcast_form;


?><div class="wrap">
    <h1 id="podcast-new"><?php _e("Edit Podcast"); ?></h1>
    <p><?php _e('Edit podcast info.'); ?></p>
    <form method="post" id="podcast-edit-form" action="<?php echo add_query_arg(array('page' => $_REQUEST['page']), admin_url('admin-post.php')); ?>" novalidate="novalidate" class="ospn-form">
        <input type="hidden" name="action" value="ospn-admin-podcast-edit" />
        <input type="hidden" name="blog_id" id="blog_id" value="<?php echo $podcast_form->blog_id; ?>">
        <?php wp_nonce_field('podcast-edit'); ?>
        <h2><?php _e('Global Information'); ?> <span class="description"><?php _e('(required)'); ?></span></h2>
        <table class="form-table">
            <tr class="podcast-name-wrap">
                <th><label for="podcast-name"><?php _e('Podcast Name') ?></label></th>
                <td><input type="text" name="podcast-name" id="podcast-name" value="<?php echo $podcast_form->blog_name; ?>" class="regular-text" required="required" /></td>
            </tr>
            <tr class="podcast-host-wrap">
                <th><label for="podcast-host"><?php _e('Host') ?></label></th>
                <td>
                    <?php wp_dropdown_users(array(
                        "blog_id" => $podcast_form->blog_id
                    )); ?>
                </td>
            </tr>
            <tr class="podcast-email-wrap">
                <th><label for="podcast-email"><?php _e('Contact Mail') ?></label></th>
                <td><input type="email" name="podcast-email" id="podcast-email" value="<?php echo $podcast_form->contact; ?>" class="regular-text" required="required" /></td>
            </tr>
            <tr class="podcast-website-wrap">
                <th><label for="podcast-website"><?php _e('Website') ?></label></th>
                <td><input type="url" name="podcast-website" id="podcast-website" value="<?php echo $podcast_form->website; ?>" class="regular-text" required="required" /></td>
            </tr>
            <tr class="podcast-rss-feed-wrap">
                <th><label for="podcast-rss-feed"><?php _e('RSS Feed') ?></label></th>
                <td><input type="url" name="podcast-rss-feed" id="podcast-rss-feed" value="<?php echo $podcast_form->podcast_feed; ?>" class="regular-text" required="required" /></td>
            </tr>
        </table>
        <h2><?php _e('Administrative informations'); ?></h2>
        <table class="form-table">
            <tr class="podcast-active-wrap">
                <th><?php _e('Active') ?></th>
                <td><label for="podcast-active"><input name="podcast-active" type="checkbox" id="podcast-active" value="true"<?php if ($podcast_form->active == 1) echo ' checked="checked"'; ?>/><?php _e( 'Mark this podcast as active'); ?></label></td>
            </tr>
        </table>
        <?php submit_button(__('Update'), 'primary', 'podcast-edit-submit', true, array('id' => 'podcast-edit-submit')); ?>
    </form>
</div>
