<?php
// Find users
$users = get_users([
    'role__in'  => array('Administrator', 'Editor'),
    'fields'    => array('ID', 'display_name'),
    'orderby'   => 'display_name',
    'order'     => 'ASC'
]);
// You
$current_user_id = get_current_user_id();
// Create user select control
$user_select = '';
if (!empty($users)) {
    $user_select .= '<select name="wrs-chat__user-select" id="wrs-chat-user-select">';
    $user_select .= '<option value="">-- No User Selected --</option>';
    foreach ($users as $user) {
        if ((int) $user->ID === $current_user_id) {
            continue;
        }
        $user_select .= sprintf('<option value="%s">%s</option>', esc_attr($user->ID), esc_html($user->display_name));
    }
    $user_select .= '</select>';
}
// Get chats
$chats = new WP_Query([
    'post_type'      => 'wrs_chat',
    'name'           => '1-3',
    'order'          => 'ASC',
    'posts_per_page' => 100
]);

?>
<!-- Component Start -->
<div class="wrs-chat__container">
    <div id="wrs-chat" class="flex flex-col flex-grow w-full max-w-xl bg-white shadow-xl rounded-lg overflow-hidden fixed bottom-0 right-4 h-[50vh]">
        <div class="flex flex-col flex-grow h-0 p-4 overflow-auto">
            <?php if ($chats->have_posts()) : ?>
                <?php while ($chats->have_posts()) : $chats->the_post(); ?>
                    <?php if ((int) get_post_field('post_author') === $current_user_id) : ?>
                        <?php require WRS_CHAT_DIR . '/templates/chat-mine.php'; ?>
                    <?php else : ?>
                        <?php require WRS_CHAT_DIR . '/templates/chat-theirs.php'; ?>
                    <?php endif; ?>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>

        <div class="bg-gray-300 p-4">
            <form id="wrs-chat-form">
                <input class="flex items-center h-10 w-full rounded px-3 text-sm" type="text" name="wrs_chat_message" placeholder="Type your message…" required>
                <input type="submit" value="Submit Chat" />
            </form>
        </div>
        <?php echo esc_html($user->ID . "-" . $current_user_id); ?>
        <?php echo $user_select; ?>
    </div>
</div>
<!-- Component End  -->
<?php wp_reset_postdata(); ?>