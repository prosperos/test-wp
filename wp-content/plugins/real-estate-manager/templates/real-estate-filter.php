<div class="real-estate-filter">
    <h2>Filter Real Estate</h2>
    <form class="real-estate-filter-form">
        <div class="real-estate-filter__select">
            <label for="district">District</label>
            <select name="district" class="district">
                <option value="all">All</option>
                <?php
                $districts = get_terms(array(
                    'taxonomy' => 'district',
                    'hide_empty' => false,
                ));
                foreach ($districts as $district) {
                    echo '<option value="' . esc_attr($district->slug) . '">' . esc_html($district->name) . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="real-estate-filter__select">
            <label for="building_type">Building Type</label>
            <select class="building_type"  name="building_type">
                <option value="all">All</option>
                <option value="panel">Panel</option>
                <option value="brick">Brick</option>
                <option value="foam_block">Foam Block</option>
            </select>
        </div>
        <div class="real-estate-filter__select">
            <label for="number_of_floors">Number of floors</label>

            <select class="number_of_floors" name="number_of_floors">
                <option value="all">All</option>
                <?php
                for ($i = 1; $i <= 20; $i++) {
                    echo '<option value="' . esc_attr($i) . '">' . esc_html($i) . '</option>';
                }
                ?>
            </select>
        </div>
        <button type="submit">Show</button>
    </form>
</div>
<div  class="real-estate-posts realEstateResults">
    <?php
    $args = array(
        'post_type' => 'real_estate',
        'posts_per_page' => -1,
    );

    $real_estate_query = new WP_Query($args);

    if ($real_estate_query->have_posts()) {
        while ($real_estate_query->have_posts()) {
            $real_estate_query->the_post();

            the_title();
            the_content();

            $location_coordinates = get_field('location_coordinates');
            $number_of_floors = get_field('number_of_floors');
            $building_type = get_field('building_type');
            ?>
            <div>
                <p><strong>Location Coordinates:</strong> <?php echo esc_html($location_coordinates); ?></p>
                <p><strong>Number of Floors:</strong> <?php echo esc_html($number_of_floors); ?></p>
                <p><strong>Building Type:</strong> <?php echo esc_html($building_type); ?></p>
            </div>
            <br>
            <hr>
            <br>
            <?php

        }

        wp_reset_postdata();
    } else {
        echo 'No real estate posts found.';
    }

    ?>
</div>
