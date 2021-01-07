<?php

require get_theme_file_path('/includes/search-route.php');
require get_theme_file_path('/includes/like-route.php');

// add cutom field(key and value) to the wordpress api json response. In this case, authorName
function university_custom_rest() {
    register_rest_field('post', 'authorName', [
        'get_callback' => function () {
            return get_the_author();
        }
    ]);

    register_rest_field('note', 'userNoteCount', [
        'get_callback' => function () {
            return count_user_posts(get_current_user_id(), 'note');
        }
    ]);
}

add_action('rest_api_init', 'university_custom_rest');

// NULL sets args as optional instead of required
function page_banner($args = NULL) { 
    if(!$args['title']) {
        $args['title'] = get_the_title();
    }

    if(!$args['subtitle']) {
        $args['subtitle'] = get_field('page_banner_subtitle');
    }

    if(!$args['photo']) {
        if(get_field('page_banner_background_image') AND !is_archive() AND !is_home() ) {
            $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
        } else {
            $args['photo'] = get_theme_file_uri('images/ocean.jpg');
        }
    }
?>

    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>);"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
            <div class="page-banner__intro">
                <!-- <p><?php the_field('page_banner_subtitle') ?></p> -->
                <p><?php echo $args['subtitle']; ?></p>
            </div>
        </div>
    </div>

<?php
}

/**
 * university_files
 *
 * @return void
 */
function university_files() {
    // wp_enqueue_script('main-univeristy-js', get_theme_file_uri('/js/scripts-bundled.js'), NULL, '1.0', true);
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    // wp_enqueue_style('university_main_styles', get_stylesheet_uri());

    wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key=xxx', NULL, '1.0', true);

    if (strstr($_SERVER['SERVER_NAME'], 'fictional-university.local')) {
        wp_enqueue_script('main-univeristy-js', 'http://localhost:3000/bundled.js', NULL, '1.0', true);
    } else {
        wp_enqueue_script('our-vendors-js', get_theme_file_uri('/bundled-assets/vendors~scripts.9678b4003190d41dd438.js'), NULL, '1.0', true);
        wp_enqueue_script('main-univeristy-js', get_theme_file_uri('/bundled-assets/scripts.90426e75e602d1d0813b.js'), NULL, '1.0', true);
        wp_enqueue_style('our-main-styles', get_theme_file_uri('/bundled-assets/styles.90426e75e602d1d0813b.css'));
    }

    wp_localize_script('main-univeristy-js', 'universityData', [ // Defined universityData to store root_url
        'root_url' => get_site_url(),
        'nonce' => wp_create_nonce('wp_rest')
    ]);


}

add_action('wp_enqueue_scripts', 'university_files');

function university_features() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_image_size('professorLandscape', 450, 350, ['center', 'top']);
    add_image_size('professorPortrait', 480, 650, true);
    add_image_size('pageBanner', 1500, 350, true);
}

add_action('after_setup_theme', 'university_features');



/**
 * university_adjust_queries
 *
 * @param  mixed $query
 * @return void
 */
function university_adjust_queries($query) {

    if (!is_admin() and is_post_type_archive('campus') and $query->is_main_query()) {
        $query->set('posts_per_page', -1);
    }

    if (!is_admin() and is_post_type_archive('program') and $query->is_main_query()) {
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);
    }

    if (!is_admin() and is_post_type_archive('event') and $query->is_main_query()) {
        $today = date('Ymd');
        $query->set('meta_key', 'event_date');
        $query->set('orderby', 'meta_value_num ');
        $query->set('order', 'ASC');
        $query->set('meta_query', [
            [
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric'
            ]
        ]);
    }
}

add_action('pre_get_posts', 'university_adjust_queries');

function universityMapKey($api) {
    $api['key'] = 'xxx';
    return $api;
}

add_filter('acf/fields/google_map/api', 'universityMapKey');


// Redirect subscriber accounts out of admin dashboard onto homepage
add_action('admin_init', 'redirectSubsToFrontEnd');

function redirectSubsToFrontEnd() {
    $ourCurrentUser = wp_get_current_user();
    if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
        wp_redirect(site_url('/'));
        exit;
    }
}


add_action('wp_loaded', 'noSubsAdminBar');

function noSubsAdminBar() {
    $ourCurrentUser = wp_get_current_user();
    if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
        show_admin_bar(false);
    }
}


// Customize login screen
add_filter('login_headerurl', 'ourHeaderUrl');

function ourHeaderUrl() {
    return esc_url(site_url('/'));
}


// Use our custom css files
add_action('login_enqueue_scripts', 'ourLoginCSS');

function ourLoginCSS() {
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('our-main-styles', get_theme_file_uri('/bundled-assets/styles.90426e75e602d1d0813b.css'));
}


// Customize the title of the login page from 'Powered By WordPress'
add_filter('login_headertitle', 'ourLoginTitle');

function ourLoginTitle() {
    return get_bloginfo('name');
}


// Force note post to be private
add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2); /* 2 means the function should be able to work with 2 params, 
while 10 isthe priority, i.e if you have multple fiters. The lower number has higher priority and runs first. */
function makeNotePrivate($data, $postarr) {
    if($data['post_type'] == 'note') {
        if(count_user_posts(get_current_user_id(), 'note') > 3 AND !$postarr['ID']) {
            die("You have reached your note limit.");
        }

        $data['post_content'] = sanitize_textarea_field($data['post_content']);
        $data['post_title'] = sanitize_text_field($data['post_title']);
    }

    if($data['post_type'] == 'note' AND $data['post_status'] != 'trash') {
        $data['post_status'] = "private";
    }
    return $data;
}