<?php
/**
 * 为WordPress后台的文章、分类等显示ID
 * http://www.wpdaxue.com/simply-show-ids.html
 */
// 添加一个新的列 ID
function cmp_column($cols) {
	$cols['ssid'] = 'ID';
	return $cols;
}

// 显示 ID
function cmp_value($column_name, $id) {
	if ($column_name == 'ssid')
		echo $id;
}

function cmp_return_value($value, $column_name, $id) {
	if ($column_name == 'ssid')
		$value = $id;
	return $value;
}

// 为 ID 这列添加css
function cmp_css() {
?>
<style type="text/css">
	#ssid { width: 50px; } /* Simply Show IDs */
</style>
<?php
}

// 通过动作/过滤器输出各种表格和CSS
function cmp_add() {
	add_action('admin_head', 'cmp_css');

	add_filter('manage_posts_columns', 'cmp_column');
	add_action('manage_posts_custom_column', 'cmp_value', 10, 2);

	add_filter('manage_pages_columns', 'cmp_column');
	add_action('manage_pages_custom_column', 'cmp_value', 10, 2);

	add_filter('manage_media_columns', 'cmp_column');
	add_action('manage_media_custom_column', 'cmp_value', 10, 2);

	add_filter('manage_link-manager_columns', 'cmp_column');
	add_action('manage_link_custom_column', 'cmp_value', 10, 2);

	add_action('manage_edit-link-categories_columns', 'cmp_column');
	add_filter('manage_link_categories_custom_column', 'cmp_return_value', 10, 3);

	foreach ( get_taxonomies() as $taxonomy ) {
		add_action("manage_edit-${taxonomy}_columns", 'cmp_column');
		add_filter("manage_${taxonomy}_custom_column", 'cmp_return_value', 10, 3);
	}

	add_action('manage_users_columns', 'cmp_column');
	add_filter('manage_users_custom_column', 'cmp_return_value', 10, 3);

	add_action('manage_edit-comments_columns', 'cmp_column');
	add_action('manage_comments_custom_column', 'cmp_value', 10, 2);
}

add_action('admin_init', 'cmp_add');