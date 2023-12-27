<?php
get_header();

while (have_posts()) :
    the_post();

    $house_name = get_field('house_name');
    $location_coordinates = get_field('location_coordinates');
    $number_of_floors = get_field('number_of_floors');
    $building_type = get_field('building_type');
    ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="entry-header">
            <h1 class="entry-title"><?php echo esc_html($house_name); ?></h1>
        </header>

        <div class="entry-content">
            <p><strong>Location Coordinates:</strong> <?php echo esc_html($location_coordinates); ?></p>
            <p><strong>Number of Floors:</strong> <?php echo esc_html($number_of_floors); ?></p>
            <p><strong>Building Type:</strong> <?php echo esc_html($building_type); ?></p>

            <?php the_content(); ?>
        </div>
    </article>

<?php endwhile;

get_footer();
