<?php
// My custom API endpoint for 'like'

add_action('rest_api_init', 'universityLikeRoutes');

function universityLikeRoutes() {
    // Register two new routes
    register_rest_route('university/v1', 'manageLike', [
        'methods' => 'POST', //'methods' mean the type of HTTP request that this route is responsible for
        'callback' => 'createLike'
    ]);

    register_rest_route('university/v1', 'manageLike', [
        'methods' => 'DELETE',
        'callback' => 'deleteLike'
    ]);
}

function createLike($data) {
    if(is_user_logged_in()) { // Also look up the function current_user_can('publish_note')
        $professor = sanitize_text_field($data['professorId']);

        $existsQuery = new WP_Query([
            'author' => get_current_user_id(), // if not logged in then author evaluates to 0
            'post_type' => 'like',
            'meta_query' => [
                [
                    'key' => 'liked_professor_id',
                    'compare' => '=',
                    'value' => $professor
                ]
            ]
        ]);

        if($existsQuery->found_posts == 0 AND get_post_type($professor) == 'professor') {
            // create new like post
            return wp_insert_post([ // Allows us to create a new post right from our php code
                'post_type' => 'like',
                'post_status' => 'publish',
                'post_title' => '💟', // type ":" followed by emoji name and choose from the options
                'meta_input' => [
                    'liked_professor_id' => $professor
                ]
            ]);
        } else {
            die("Invalid professor ID");
        }

    } else {
        die("You must be logged in to like.");
    }

}

function deleteLike($data) {
    $likeId = sanitize_text_field($data['like']);
    if(get_current_user_id() == get_post_field('post_author', $likeId) AND get_post_type($likeId) == 'like') {
        wp_delete_post($likeId, true); // if 2nd argument is true, it skips the trash and just deletes the like post
        return 'Gone';
    } else {
        die("You do not have permission to delete this like.");
    }
}