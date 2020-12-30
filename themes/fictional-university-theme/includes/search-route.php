<?php
 
 add_action('rest_api_init', 'universityRegisterSearch');

 function universityRegisterSearch() {
     register_rest_route('university/v1', 'search', [
         'methods' => WP_REST_SERVER::READABLE, // Substitutes for 'GET' http method
         'callback' => 'universitySearchResults'
     ]);

 }

 function universitySearchResults($data) {
     $mainQuery = new WP_Query([
        'post_type' => ['post', 'page', 'professor', 'program', 'campus', 'event'],
        's' => sanitize_text_field($data['term'])  //always sanitize data that comes from the browser that could potentially be querying our database
     ]);

     $results = [
         'generalInfo' => [],
         'professors' => [],
         'programs' => [],
         'events' => [],
         'campuses' => []
     ];

     while($mainQuery->have_posts()) {
         $mainQuery->the_post(); // gets all the relevant data for the current post ready and accessible

         if (get_post_type() == 'post' OR get_post_type() == 'page') {
            array_push($results['generalInfo'], [
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'postType' => get_post_type(),
                'authorName' => get_the_author()
            ]);
         }

         if (get_post_type() == 'professor') {
            array_push($results['professors'], [
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0, 'professorLandscape') // 0 is a wordpress term for the current post, professorLandscape is the size of the image we want to use
            ]);
         }

         if (get_post_type() == 'program') {

            $relatedCampuses = get_field('related_campus');

            if ($relatedCampuses) {
                foreach($relatedCampuses as $campus) {
                    array_push($results['campuses'], [
                        'title' => get_the_title($campus),
                        'permalink' => get_the_permalink($campus)
                    ]);
                }
            }


            array_push($results['programs'], [
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'id' => get_the_ID()
            ]);
         }

         if (get_post_type() == 'event') {
            $eventDate = new DateTime(get_field('event_date'));
            $description = null;
            if (has_excerpt()) {
                $description = get_the_excerpt();
            } else {
                $description = wp_trim_words(get_the_content(), 10);
            }
            array_push($results['events'], [
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'month' => $eventDate->format('M'),
                'day' => $eventDate->format('d'),
                'description' => $description
            ]);
         }

         if (get_post_type() == 'campus') {
            array_push($results['campuses'], [
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ]);
         }

     }


     if ($results['programs']) {

        // Build this array to handle whether there's only 1 or 20 programs in the university
        $programsMetaQuery = ['relation' => 'OR'];

        foreach($results['programs'] as $item) {
            array_push($programsMetaQuery,             [
                'key' => 'related_programs',
                'compare' => 'LIKE',
                'value' => '"' . $item['id'] . '"' // Explanation for why we use the '""': section 9, nos. 42, 17:00mins
            ]);
        }

        $programRelationshipQuery = new WP_Query([
            'post_type' => ['professor', 'event'],
            'meta_query' => $programsMetaQuery
        ]);

        while($programRelationshipQuery->have_posts()) {
            $programRelationshipQuery->the_post();

            if (get_post_type() == 'event') {
                $eventDate = new DateTime(get_field('event_date'));
                $description = null;
                if (has_excerpt()) {
                    $description = get_the_excerpt();
                } else {
                    $description = wp_trim_words(get_the_content(), 10);
                }
                array_push($results['events'], [
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'month' => $eventDate->format('M'),
                    'day' => $eventDate->format('d'),
                    'description' => $description
                ]);
             }

            if (get_post_type() == 'professor') {
                array_push($results['professors'], [
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'image' => get_the_post_thumbnail_url(0, 'professorLandscape') // 0 is a wordpress term for the current post, professorLandscape is the size of the image we want to use
                ]);
            }

        }

        // Remove duplicate values
        $results['professors'] = array_values(array_unique($results['professors'], SORT_REGULAR));

        $results['events'] = array_values(array_unique($results['events'], SORT_REGULAR));

    }

     return $results;
 }