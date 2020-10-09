<?php
/**
 * Register new endpoint to use inside My Account page.
 *
 * @see https://developer.wordpress.org/reference/functions/add_rewrite_endpoint/
 */
function my_custom_endpoints() {
	add_rewrite_endpoint( 'animals', EP_ROOT | EP_PAGES );
}

add_action( 'init', 'my_custom_endpoints' );

/**
 * Add new query var.
 *
 * @param array $vars
 * @return array
 */
function my_custom_query_vars( $vars ) {
	$vars[] = 'animals';

	return $vars;
}

add_filter( 'query_vars', 'my_custom_query_vars', 0 );

/**
 * Flush rewrite rules on theme activation.
 */
function my_custom_flush_rewrite_rules() {
	add_rewrite_endpoint( 'animals', EP_ROOT | EP_PAGES );
	flush_rewrite_rules();
}

add_action( 'after_switch_theme', 'my_custom_flush_rewrite_rules' );

/**
 * Insert the new endpoint into the My Account menu.
 *
 * @param array $items
 * @return array
 */
function my_custom_my_account_menu_items( $items ) {
	// Remove the logout menu item.
	$logout = $items['customer-logout'];
	unset( $items['customer-logout'] );

	// Insert your custom endpoint.
	$items['animals'] = __( 'Animals', 'woocommerce' );

	// Insert back the logout item.
	$items['customer-logout'] = $logout;

	return $items;
}

add_filter( 'woocommerce_account_menu_items', 'my_custom_my_account_menu_items' );

/**
 * Endpoint HTML content.
 */
function my_custom_endpoint_content() {
	echo '<p>Hello World!</p>';
}

add_action( 'woocommerce_account_animals_endpoint', 'my_custom_endpoint_content' );

/*
 * Change endpoint title.
 *
 * @param string $title
 * @return string
 */
function my_custom_endpoint_title( $title ) {
	global $wp_query;

	$is_endpoint = isset( $wp_query->query_vars['animals'] );

	if ( $is_endpoint && ! is_admin() && is_main_query() && in_the_loop() && is_account_page() ) {
		// New page title.
		$title = __( 'Animals', 'woocommerce' );

		remove_filter( 'the_title', 'my_custom_endpoint_title' );
	}

	return $title;
}

add_filter( 'the_title', 'my_custom_endpoint_title' );







?>
