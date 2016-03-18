<?php

if (!current_user_can('manage_options')) {
	wp_die(
		'<h1>' . __( 'Cheatin&#8217; uh?' ) . '</h1>' .
		'<p>' . __( 'You are not allowed to manage members.' ) . '</p>',
		403
	);
}

if (!class_exists('OSPN_Members_List_Table')) {
    require_once(dirname(dirname(__FILE__)) . '/includes/class-ospn-members-list-table.php');
}

global $wp_list_table;

$wp_list_table->prepare_items();?>
<div class="wrap"><h1><?php echo __('Members'); ?><a href="<?php echo admin_url('admin.php?page=ospn-admin-member-new'); ?>" class="page-title-action"><?php echo esc_html_x('Add New', 'member'); ?></a></h1>
<?php $wp_list_table->views();?>
<form method="post">
    <input type="hidden" name="page" value="ospn-admin-members">
    <?php
    $wp_list_table->search_box(__('Search Members'), 'member');
    $wp_list_table->display();
    ?>
</form>
<br class="clear" />
</div>
