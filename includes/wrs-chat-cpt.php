<?php
/**
 * WP Redis SSE Chat, custom post type
 * 
 * @package WpRedisSseChat
 */

namespace WpRedisSseChat\wrs_chat_cpt;

/**
 * Registers the `wrs_chat` post type.
 */
function init() {
	register_post_type(
		'wrs_chat',
		[
			'labels'                => [
				'name'                  => __( 'Chats', 'wp-redis-sse-chat' ),
				'singular_name'         => __( 'Chat', 'wp-redis-sse-chat' ),
				'all_items'             => __( 'All Chats', 'wp-redis-sse-chat' ),
				'archives'              => __( 'Chat Archives', 'wp-redis-sse-chat' ),
				'attributes'            => __( 'Chat Attributes', 'wp-redis-sse-chat' ),
				'insert_into_item'      => __( 'Insert into Chat', 'wp-redis-sse-chat' ),
				'uploaded_to_this_item' => __( 'Uploaded to this Chat', 'wp-redis-sse-chat' ),
				'featured_image'        => _x( 'Featured Image', 'wrs_chat', 'wp-redis-sse-chat' ),
				'set_featured_image'    => _x( 'Set featured image', 'wrs_chat', 'wp-redis-sse-chat' ),
				'remove_featured_image' => _x( 'Remove featured image', 'wrs_chat', 'wp-redis-sse-chat' ),
				'use_featured_image'    => _x( 'Use as featured image', 'wrs_chat', 'wp-redis-sse-chat' ),
				'filter_items_list'     => __( 'Filter Chats list', 'wp-redis-sse-chat' ),
				'items_list_navigation' => __( 'Chats list navigation', 'wp-redis-sse-chat' ),
				'items_list'            => __( 'Chats list', 'wp-redis-sse-chat' ),
				'new_item'              => __( 'New Chat', 'wp-redis-sse-chat' ),
				'add_new'               => __( 'Add New', 'wp-redis-sse-chat' ),
				'add_new_item'          => __( 'Add New Chat', 'wp-redis-sse-chat' ),
				'edit_item'             => __( 'Edit Chat', 'wp-redis-sse-chat' ),
				'view_item'             => __( 'View Chat', 'wp-redis-sse-chat' ),
				'view_items'            => __( 'View Chats', 'wp-redis-sse-chat' ),
				'search_items'          => __( 'Search Chats', 'wp-redis-sse-chat' ),
				'not_found'             => __( 'No Chats found', 'wp-redis-sse-chat' ),
				'not_found_in_trash'    => __( 'No Chats found in trash', 'wp-redis-sse-chat' ),
				'parent_item_colon'     => __( 'Parent Chat:', 'wp-redis-sse-chat' ),
				'menu_name'             => __( 'Chats', 'wp-redis-sse-chat' ),
			],
			'public'                => true,
			'hierarchical'          => false,
			'show_in_menu'          => false,
			'show_ui'               => true,
			'show_in_nav_menus'     => true,
			'supports'              => [ 'title', 'editor', 'author' ],
			'has_archive'           => true,
			'rewrite'               => true,
			'query_var'             => true,
			'menu_position'         => null,
			'menu_icon'             => 'dashicons-admin-post',
			'show_in_rest'          => true,
			'rest_base'             => 'wrs_chat',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
		]
	);

}

add_action( 'init', __NAMESPACE__ . '\\init' );

/**
 * Sets the post updated messages for the `wrs_chat` post type.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `wrs_chat` post type.
 */
function updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['wrs_chat'] = [
		0  => '', // Unused. Messages start at index 1.
		/* translators: %s: post permalink */
		1  => sprintf( __( 'Chat updated. <a target="_blank" href="%s">View Chat</a>', 'wp-redis-sse-chat' ), esc_url( $permalink ) ),
		2  => __( 'Custom field updated.', 'wp-redis-sse-chat' ),
		3  => __( 'Custom field deleted.', 'wp-redis-sse-chat' ),
		4  => __( 'Chat updated.', 'wp-redis-sse-chat' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Chat restored to revision from %s', 'wp-redis-sse-chat' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		/* translators: %s: post permalink */
		6  => sprintf( __( 'Chat published. <a href="%s">View Chat</a>', 'wp-redis-sse-chat' ), esc_url( $permalink ) ),
		7  => __( 'Chat saved.', 'wp-redis-sse-chat' ),
		/* translators: %s: post permalink */
		8  => sprintf( __( 'Chat submitted. <a target="_blank" href="%s">Preview Chat</a>', 'wp-redis-sse-chat' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
		9  => sprintf( __( 'Chat scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Chat</a>', 'wp-redis-sse-chat' ), date_i18n( __( 'M j, Y @ G:i', 'wp-redis-sse-chat' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		/* translators: %s: post permalink */
		10 => sprintf( __( 'Chat draft updated. <a target="_blank" href="%s">Preview Chat</a>', 'wp-redis-sse-chat' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	];

	return $messages;
}

add_filter( 'post_updated_messages', __NAMESPACE__ . '\\updated_messages' );

/**
 * Sets the bulk post updated messages for the `wrs_chat` post type.
 *
 * @param  array $bulk_messages Arrays of messages, each keyed by the corresponding post type. Messages are
 *                              keyed with 'updated', 'locked', 'deleted', 'trashed', and 'untrashed'.
 * @param  int[] $bulk_counts   Array of item counts for each message, used to build internationalized strings.
 * @return array Bulk messages for the `wrs_chat` post type.
 */
function bulk_updated_messages( $bulk_messages, $bulk_counts ) {
	global $post;

	$bulk_messages['wrs_chat'] = [
		/* translators: %s: Number of Chats. */
		'updated'   => _n( '%s Chat updated.', '%s Chats updated.', $bulk_counts['updated'], 'wp-redis-sse-chat' ),
		'locked'    => ( 1 === $bulk_counts['locked'] ) ? __( '1 Chat not updated, somebody is editing it.', 'wp-redis-sse-chat' ) :
						/* translators: %s: Number of Chats. */
						_n( '%s Chat not updated, somebody is editing it.', '%s Chats not updated, somebody is editing them.', $bulk_counts['locked'], 'wp-redis-sse-chat' ),
		/* translators: %s: Number of Chats. */
		'deleted'   => _n( '%s Chat permanently deleted.', '%s Chats permanently deleted.', $bulk_counts['deleted'], 'wp-redis-sse-chat' ),
		/* translators: %s: Number of Chats. */
		'trashed'   => _n( '%s Chat moved to the Trash.', '%s Chats moved to the Trash.', $bulk_counts['trashed'], 'wp-redis-sse-chat' ),
		/* translators: %s: Number of Chats. */
		'untrashed' => _n( '%s Chat restored from the Trash.', '%s Chats restored from the Trash.', $bulk_counts['untrashed'], 'wp-redis-sse-chat' ),
	];

	return $bulk_messages;
}

add_filter( 'bulk_post_updated_messages', __NAMESPACE__ . '\\bulk_updated_messages', 10, 2 );
