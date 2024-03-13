<?php
require_once WRS_CHAT_DIR . '/includes/wrs-chat-cpt.php';

add_action('admin_footer', function () {
    require WRS_CHAT_DIR . '/templates/chat.php';
});

add_action('admin_enqueue_scripts', function () {
    wp_enqueue_style('wrs-chat', WRS_CHAT_URL . '/build/index.css', array(), '1.0');

    wp_enqueue_script('wrs-chat', WRS_CHAT_URL . '/build/index.js', array(), null, true);
    wp_localize_script('wrs-chat', 'wrsChat', array(
        'ajaxUrl'   => admin_url('admin-ajax.php'),
        'nonce'     => wp_create_nonce('wrs_chat_nonce'),
        'userId'    => get_current_user_id(),
    ));
});

add_filter('get_the_date', function () {
    if ('wrs_chat' !== get_post_type()) {
        return get_the_time();
    }
    return human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago';
});

// Hook for handling AJAX request
add_action('wp_ajax_submit_wrs_chat', function () {
    check_ajax_referer('wrs_chat_nonce', 'nonce');

    $wrs_chat_title = sanitize_text_field($_POST['wrs_chat_title']);
    $wrs_chat_message = sanitize_text_field($_POST['wrs_chat_message']);
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

    $post_data = [
        'post_type'     => 'wrs_chat',
        'post_title'    => $wrs_chat_title,
        'post_content'  => $wrs_chat_message,
        'post_status'   => 'publish',
    ];

    // Update or Insert Custom Post Type
    if ($post_id > 0) {
        $post_data['ID'] = $post_id; // Set the ID to update the post
        $post_id = wp_update_post($post_data, true); // true to return WP_Error on failure
    } else {
        $post_id = wp_insert_post($post_data, true); // true to return WP_Error on failure
    }

    if (!is_wp_error($post_id)) {
        // Trigger action when the custom post type is inserted/updated
        do_action('wrs_chat_inserted_or_updated', $post_id, $wrs_chat_message, $post_id > 0);

        // Return a success response
        wp_send_json_success(array('post_id' => $post_id));
    } else {
        // Return an error response
        wp_send_json_error(array('message' => $post_id->get_error_message()));
    }
});

add_action('wrs_chat_inserted', function ($post_id, $wrs_chat_message) {
    // Additional logic when a chat post is inserted.
    // This is where you can hook custom functionality.
}, 10, 2);
