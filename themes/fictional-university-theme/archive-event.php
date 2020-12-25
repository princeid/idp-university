<?php get_header();
pageBanner([
    'title' => 'All Events',
    'subtitle' => "There's an event for everyone"
]);
?>

<div class="container container--narrow page-section">

    <?php
    while (have_posts()) {
        the_post();

        get_template_part('template-parts/content', 'event');

    }

    echo paginate_links();
    ?>

    <hr class="section-break">
    <p>Looking for a recap of past events? <a href="<?php echo site_url('/past-events') ?>">Checkout our past events archive</a>.</p>

</div>

<?php get_footer(); ?>