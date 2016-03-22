<?php

if (!current_user_can('manage_options')) {
    wp_die(
        '<h1>' . __( 'Cheatin&#8217; uh?' ) . '</h1>' .
        '<p>' . __( 'You are not allowed here.' ) . '</p>',
        403
    );
}

use OSPN\Table\OSPN_Podcasts_Table;

/**
 * @global OSPN\Table\OSPN_Podcasts_Table $ospn_podcasts_table
 */
global $ospn_podcasts_table;

$ospn_podcasts_table->prepare_items();?>
<div class="wrap"><h1><?php echo __('Podcasts'); ?></h1>
    <?php $ospn_podcasts_table->views();?>
    <form method="post">
        <input type="hidden" name="page" value="ospn-admin-members">
        <?php
        $ospn_podcasts_table->search_box(__('Search Podcasts'), 'podcast');
        $ospn_podcasts_table->display();
        ?>
    </form>
    <br class="clear" />
</div>
