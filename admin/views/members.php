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

$wp_list_table = new OSPN_Members_List_Table();
echo '<div class="wrap"><h2>My list table test</h2>';
$wp_list_table->prepare_items();
$wp_list_table->display();
echo '</div>';

/*
$pagenum = $wp_list_table->get_pagenum();
$title = __('Members');
$parent_file = 'users.php';
add_screen_option('per_page');

switch ( $wp_list_table->current_action() ) {
default:
	if ( !empty($_GET['_wp_http_referer']) ) {
		wp_redirect( remove_query_arg( array( '_wp_http_referer', '_wpnonce'), wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
		exit;
	}

	$wp_list_table->prepare_items();
	$total_pages = $wp_list_table->get_pagination_arg('total_pages');
	if ($pagenum > $total_pages && $total_pages > 0) {
		wp_redirect(add_query_arg('paged', $total_pages));
		exit;
	}

	$messages = array();
	if ( isset($_GET['update']) ) :
		switch($_GET['update']) {
		}
	endif; ?>

<?php if ( isset($errors) && is_wp_error( $errors ) ) : ?>
	<div class="error">
		<ul>
		<?php
			foreach ( $errors->get_error_messages() as $err )
				echo "<li>$err</li>\n";
		?>
		</ul>
	</div>
<?php endif;

if ( ! empty($messages) ) {
	foreach ( $messages as $msg )
		echo $msg;
} ?>

<div class="wrap">
<h1>
<?php
echo esc_html($title);
?>
	<a href="member-new.php" class="page-title-action"><?php echo esc_html_x('Add new', 'member'); ?></a>
</h1>
<?php
$wp_list_table->views();
?>

<form method="get">

<?php $wp_list_table->search_box(__('Search Members'), 'member'); ?>

<?php $wp_list_table->display(); ?>
</form>

<br class="clear" />
</div>
<?php
break;

} // end of the $doaction switch
*/
