<?php

if (!current_user_can('manage_options')) {
    wp_die(
        '<h1>' . __( 'Cheatin&#8217; uh?' ) . '</h1>' .
        '<p>' . __( 'You are not allowed here.' ) . '</p>',
        403
    );
}

use OSPN\Form\OSPN_Podcast_Form;

/**
 * @global OSPN_Podcast_Form $podcast_form
 */
global $podcast_form;

?><div class="wrap ospn-wrap">
    <h1 id="podcast-new"><?php _e("Edit Podcast"); ?></h1>
    <p><?php _e('Edit podcast info.'); ?></p>
    <form method="post" id="podcast-edit-form" action="<?php echo add_query_arg(array('page' => $_REQUEST['page']), admin_url('admin-post.php')); ?>" novalidate="novalidate" class="ospn-form">
        <div class="glass"></div>
        <input type="hidden" name="action" value="ospn-admin-podcast-edit" />
        <input type="hidden" name="blog_id" id="blog_id" value="<?php echo $podcast_form->blog_id; ?>">
        <?php wp_nonce_field('podcast-edit'); ?>
        <h2><?php _e('Global Information'); ?> <span class="description"><?php _e('(required)'); ?></span></h2>
        <table class="form-table">
            <tr class="podcast-rss-feed-wrap">
                <th><label for="podcast-rss-feed"><?php _e('RSS Feed') ?></label></th>
                <td>
                    <input type="url" name="podcast-rss-feed" id="podcast-rss-feed" value="<?php echo $podcast_form->podcast_feed; ?>" class="regular-text" required="required" />
                    <br/><?php submit_button(__('Fetch data from RSS feed'), 'small', 'podcast-edit-update-from-rss', false, array("x-data-validation" => "bypass")); ?>
                </td>
            </tr>
            <tr class="podcast-name-wrap">
                <th><label for="podcast-name"><?php _e('Podcast Name') ?></label></th>
                <td><input type="text" name="podcast-name" id="podcast-name" value="<?php echo $podcast_form->podcast_name; ?>" class="regular-text" required="required" /></td>
            </tr>
            <tr class="podcast-tagline-wrap">
                <th><label for="podcast-tagline"><?php _e('Tagline') ?></label></th>
                <td>
                    <textarea name="podcast-tagline" id="podcast-tagline" cols="30" rows="3" style="width: 25em;" required="required"><?php echo $podcast_form->tagline; ?></textarea>
                </td>
            </tr>
            <tr class="podcast-description-wrap">
                <th><label for="podcast-description"><?php _e('Description') ?></label></th>
                <td>
                    <textarea name="podcast-description" id="podcast-description" cols="30" rows="15" style="width: 25em;" required="required"><?php echo $podcast_form->description; ?></textarea>
                </td>
            </tr>
            <tr class="podcast-host-wrap">
                <th><label for="podcast-host"><?php _e('Host') ?></label></th>
                <td>
                    <?php wp_dropdown_users(array(
                        "name" => "podcast-host",
                        "selected" => $podcast_form->host_id,
                        "blog_id" => $podcast_form->blog_id
                    )); ?>
                </td>
            </tr>
            <tr class="podcast-host2-wrap">
                <th><label for="podcast-host2"><?php _e('Host') ?></label></th>
                <td>
                    <?php wp_dropdown_users(array(
                        "name" => "podcast-host2",
                        "selected" => $podcast_form->host2_id,
                        "show_option_none" => __("None"),
                        "blog_id" => $podcast_form->blog_id
                    )); ?>
                </td>
            </tr>
            <tr class="podcast-logo-wrap">
                <th><label for="podcast-logo"><?php _e('Logo (URL)') ?></label></th>
                <td><input type="url" name="podcast-logo" id="podcast-logo" value="<?php echo $podcast_form->logo; ?>" class="regular-text" required="required" /></td>
            </tr>
            <?php if ($podcast_form->logo != ''):?>
            <tr>
                <th>

                </th>
                <td>
                    <img src="<?php echo $podcast_form->logo; ?>" width="50px" height="50px">
                    <img src="<?php echo $podcast_form->logo; ?>" width="100px" height="100px">
                    <img src="<?php echo $podcast_form->logo; ?>" width="200px" height="200px">
                </td>
            </tr>
            <?php endif; ?>
            <tr class="podcast-email-wrap">
                <th><label for="podcast-email"><?php _e('Contact Mail') ?></label></th>
                <td><input type="email" name="podcast-email" id="podcast-email" value="<?php echo $podcast_form->contact; ?>" class="regular-text" required="required" /></td>
            </tr>
            <tr class="podcast-website-wrap">
                <th><label for="podcast-website"><?php _e('Website') ?></label></th>
                <td><input type="url" name="podcast-website" id="podcast-website" value="<?php echo $podcast_form->website; ?>" class="regular-text" required="required" /></td>
            </tr>
        </table>
        <?php if ($podcast_form->origin == "admin"): ?>
        <h2><?php _e('Administrative informations'); ?></h2>
        <table class="form-table">
            <tr class="podcast-active-wrap">
                <th><?php _e('Active') ?></th>
                <td><label for="podcast-active"><input name="podcast-active" type="checkbox" id="podcast-active" value="true"<?php if ($podcast_form->active == 1) echo ' checked="checked"'; ?>/><?php _e( 'Mark this podcast as active'); ?></label></td>
            </tr>
        </table>
        <?php else: ?>
            <?php if ($podcast_form->active == 1): ?><input type="hidden" name="podcast-active" id="podcast-active" value="true"><?php endif; ?>
        <?php endif; ?>
        <input type="hidden" name="origin" id="origin" value="<?php echo $podcast_form->origin; ?>">
        <?php submit_button(__('Update'), 'primary', 'podcast-edit-submit'); ?>
    </form>
</div>
