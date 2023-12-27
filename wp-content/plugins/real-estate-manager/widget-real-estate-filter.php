<?php

class RealEstateFilterWidget extends WP_Widget
{
    function __construct()
    {

        parent::__construct(
            'real_estate_filter_widget',
            __('Real Estate Filter', 'text_domain'),
            array('description' => __('Display a filter for real estate properties.', 'text_domain'))
        );
    }

    public function widget($args, $instance)
    {

        echo '<div class="real-estate-filter-widget entry-content">';
        include(plugin_dir_path(__FILE__) . 'templates/real-estate-filter.php');
        echo '</div>';

    }
}

// Register the widget
function register_real_estate_filter_widget() {
    register_widget('RealEstateFilterWidget');
}
add_action('widgets_init', 'register_real_estate_filter_widget');



