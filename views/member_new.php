<div class="wrap" id="member_new">
    <h1><?php echo esc_html(__('Add Member')); ?></h1>
    <form id="member_new_form" action="admin-post.php" method="post" novalidate="novalidate" class="ospn-form">
        <?php wp_nonce_field('member_new') ?>
        <h2><?php _e('Global Information'); ?> <span class="description"><?php _e('(required)'); ?></span></h2>
        <table class="form-table">
            <tr class="podcast-name-wrap">
                <th><label for="podcast-name"><?php _e('Podcast Name') ?></label></th>
                <td><input type="text" name="podcast-name" id="podcast-name" value="" class="regular-text" required="required" /></td>
            </tr>
            <tr class="podcast-website-wrap">
                <th><label for="podcast-website"><?php _e('Website') ?></label></th>
                <td><input type="text" name="podcast-website" id="podcast-website" value="" class="regular-text" required="required" /></td>
            </tr>
            <tr class="podcast-rss-feed-wrap">
                <th><label for="podcast-rss-feed"><?php _e('RSS Feed') ?></label></th>
                <td><input type="text" name="podcast-rss-feed" id="podcast-rss-feed" value="" class="regular-text" required="required" /></td>
            </tr>
            <tr class="podcast-host-wrap">
                <th><label for="podcast-host"><?php _e('Host'); ?></label></th>
                <td>
                    <select id="podcast-host" name="podcast-host" required="required">
                        <option value=""></option>
                        <?php
                        global $allusers;
                        foreach ($allusers as $u) {
                            ?>
                        <option value="<?php echo $u->ID; ?>"><?php echo $u->display_name; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr class="podcast-email-wrap">
                <th><label for="podcast-email"><?php _e('Contact Mail') ?></label></th>
                <td><input type="email" name="podcast-email" id="podcast-email" value="" class="regular-text" required="required" /></td>
            </tr>
        </table>
        <h2><?php _e('Social networks'); ?></h2>
        <table class="form-table">
            <tr class="podcast-twitter-wrap">
                <th><label for="podcast-twitter"><?php _e('Twitter Username') ?></label></th>
                <td><input type="text" name="podcast-twitter" id="podcast-twitter" value="" class="regular-text" placeholder="@myhandle" pattern="@.+"/></td>
            </tr>
            <tr class="podcast-facebook-url-wrap">
                <th><label for="podcast-facebook-url"><?php _e('Facebook URL') ?></label></th>
                <td><input type="text" name="podcast-facebook-url" id="podcast-facebook-url" value="" class="regular-text" placeholder="https://facebook.com/..." pattern="https?://(www\.)?facebook\.com/.+"/></td>
            </tr>
            <tr class="podcast-google-plus-url-wrap">
                <th><label for="podcast-google-plus-url"><?php _e('Google+ URL') ?></label></th>
                <td><input type="text" name="podcast-google-plus-url" id="podcast-google-plus-url" value="" class="regular-text" placeholder="https://plus.google.com/..." pattern="https?://(www\.)?plus\.google\.com/.+"/></td>
            </tr>
        </table>
        <h2><?php _e('Administrative information'); ?></h2>
        <table class="form-table">
            <tr class="podcast-active-wrap">
                <th><?php _e('Active') ?></th>
                <td><label for="podcast-active"><input name="podcast-active" type="checkbox" id="podcast-active" value="true" checked="checked"/><?php _e( 'Mark this podcast as active'); ?></label></td>
            </tr>
        </table>
        <input type="hidden" name="action" value="ospn-member-new" />
        <?php submit_button(__('Add Member')); ?>
    </form>
</div>