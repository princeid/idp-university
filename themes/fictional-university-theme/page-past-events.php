<?php get_header(); 
pageBanner([
    'title' => 'Past Events',
    'subtitle' => 'A recap of our past events.'
]);
?>

    <div class="container container--narrow page-section">
        
        <?php
            
            $today = date('Ymd');
            $pastEvents = new WP_Query([
                'post_type' => 'event',
                'paged' => get_query_var('paged', 1),  //page number in the url is what should be used here
                // 'posts_per_page' => 2,
                'meta_key' => 'event_date',
                'orderby' => 'meta_value_num',
                'order' => 'ASC',
                'meta_query' => [
                    [
                        'key' => 'event_date',
                         'compare' => '<',
                         'value' => $today,
                         'type' => 'numeric'
                    ]
                ]

            ]);
            
            
            while($pastEvents->have_posts()) {
                $pastEvents->the_post(); ?>

                    <div class="event-summary">
                            <a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
                                <span class="event-summary__month"><?php 
                                        $eventDate = new DateTime(get_field('event_date'));
                                        echo $eventDate->format('M');
                                    ?>
                                </span>
                                <span class="event-summary__day"><?php echo $eventDate->format('d');?></span>
                            </a>
                        <div class="event-summary__content">
                            <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                            <p><?php echo wp_trim_words(get_the_content(), 17); ?><a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a></p>
                        </div>
                    </div>

        <?php        
            }

            echo paginate_links([
                'total' => $pastEvents->max_num_pages
            ]);
        ?>


    </div>

<?php get_footer(); ?>