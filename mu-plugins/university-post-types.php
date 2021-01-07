<?php

function university_post_types() {

    // Campus Post Type
    register_post_type('campus', [
        'show_in_rest' => true,
        'supports' => ['title', 'editor', 'excerpt'],
        'rewrite' => ['slug' => 'campuses'],
        'has_archive' => true,
        'public' => true,
        'labels' => [
            'name' => 'Campuses',
            'add_new_item' => 'Add New Campus',
            'edit_item' => 'Edit Campus',
            'all_items' => 'All Campuses',
            'singular_name' => 'Campus'
        ],
        'menu_icon' => 'dashicons-location-alt'
    ]);



    // Event Post Type
    register_post_type('event', [
        'capability_type' => 'event', // creates new unique capabilities for 'event' post type when granting user roles and permisssions
        'map_meta_cap' => true, // goes with the capability_type above
        'show_in_rest' => true,
        'supports' => ['title', 'editor', 'excerpt'],
        'rewrite' => ['slug' => 'events'],
        'has_archive' => true,
        'public' => true,
        'labels' => [
            'name' => 'Events',
            'add_new_item' => 'Add New Event',
            'edit_item' => 'Edit Event',
            'all_items' => 'All Events',
            'singular_name' => 'Event'
        ],
        'menu_icon' => 'dashicons-calendar-alt'
    ]);


    // Program Post Type
    register_post_type('program', [
        'show_in_rest' => true,
        'supports' => ['title'], // remove 'editor' so wordpress won't use the main default content field for programs
        'rewrite' => ['slug' => 'programs'],
        'has_archive' => true,
        'public' => true,
        'labels' => [
            'name' => 'Programs',
            'add_new_item' => 'Add New Program',
            'edit_item' => 'Edit Program',
            'all_items' => 'All Programs',
            'singular_name' => 'Program'
        ],
        'menu_icon' => 'dashicons-awards'
    ]);


    // Professor Post Type
    register_post_type('professor', [
        'show_in_rest' => true,
        'supports' => ['title', 'editor', 'thumbnail'],
        // 'rewrite' => ['slug' => 'professors'], // No need to rewrite the slug since we won't be needing professor archive.
        // 'has_archive' => true,  // No professor archive. There won't be a nav-menu-item for professor in the header
        'public' => true,
        'labels' => [
            'name' => 'Professors',
            'add_new_item' => 'Add New Professor',
            'edit_item' => 'Edit Professor Details',
            'all_items' => 'All Professors',
            'singular_name' => 'Professor'
        ],
        'menu_icon' => 'dashicons-welcome-learn-more'
    ]);


        // Note Post Type
        register_post_type('note', [
            'capability_type' => 'note',
            'map_meta_cap' => true,
            'show_in_rest' => true,
            'supports' => ['title', 'editor'],
            'public' => false, // Hides the posttype in the admin dashboard but makes the note private
            'show_ui' => true, // Shows the posttype in the admin dashboard
            'labels' => [
                'name' => 'Notes',
                'add_new_item' => 'Add New Note',
                'edit_item' => 'Edit Note',
                'all_items' => 'All Notes',
                'singular_name' => 'Note'
            ],
            'menu_icon' => 'dashicons-welcome-write-blog'
        ]);

        
        // Like Post Type
        
        /** When you want to implement custom restrictions and logic it is better not to use the built-in API endpoint
         * but rather create your own custom endpoint.
         */
        register_post_type('like', [
            'supports' => ['title'],
            'public' => false, // Hides the posttype in the admin dashboard but makes the note private
            'show_ui' => true, // Shows the posttype in the admin dashboard
            'labels' => [
                'name' => 'Likes',
                'add_new_item' => 'Add New Like',
                'edit_item' => 'Edit Like',
                'all_items' => 'All Likes',
                'singular_name' => 'Like'
            ],
            'menu_icon' => 'dashicons-heart'
        ]);


}

add_action('init', 'university_post_types');
