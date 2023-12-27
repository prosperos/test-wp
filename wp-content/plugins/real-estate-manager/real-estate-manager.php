<?php
/**
 * Plugin Name: Real Estate Manager
 * Description: Plugin to manage real estate properties with ACF fields, shortcode, and widget.
 * Version: 1.0
 * Author: Vitaliy
 */

class RealEstateManager
{

    public function __construct()
    {
        add_action('template_redirect', array($this, 'custom_template_redirect'));

        add_action('wp_dashboard_setup', array($this, 'add_dashboard_widget'));

        // Register custom post type and taxonomy
        add_action('init', array($this, 'register_real_estate_post_type'));
        add_action('init', array($this, 'register_district_taxonomy'));

        // Add ACF fields for Real Estate Property
        add_action('acf/init', array($this, 'add_acf_fields'));


        // Add shortcode for the filter
        add_shortcode('real_estate_filter', array($this, 'real_estate_filter_shortcode'));


        // Register widget for the filter
        add_action('widgets_init', array($this, 'register_real_estate_filter_widget'));

        add_action('wp_enqueue_scripts', array($this, 'enqueue_ajax_script'));

        // Ajax handler for real estate search
        add_action('wp_ajax_real_estate_search', array($this, 'ajax_filter_function'));
        add_action('wp_ajax_nopriv_real_estate_search', array($this, 'ajax_filter_function'));
    }


    public function enqueue_ajax_script() {
        wp_enqueue_script('real-estate-ajax', plugin_dir_url(__FILE__) . 'real-estate-ajax.js', array('jquery'), '1.0', true);
        wp_localize_script('real-estate-ajax', 'realEstateAjax', array('ajaxurl' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('real_estate_filter_nonce')));
    }
    function custom_template_redirect()
    {
        global $post;

        if ($post->post_type == 'real_estate' && is_single()) {
            include(plugin_dir_path(__FILE__) . 'templates/single-real_estate.php');
            exit;
        }
    }

    public function add_dashboard_widget()
    {
        $this->register_real_estate_filter_widget();

        wp_add_dashboard_widget(
            'real_estate_filter_widget',
            'Real Estate Filter',
            array($this, 'render_dashboard_widget')
        );
    }

    public function register_real_estate_post_type()
    {
        $labels = array(
            'name' => 'Real Estate Properties',
            'singular_name' => 'Real Estate Property',
            'menu_name' => 'Real Estate',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Real Estate Property',
            'edit_item' => 'Edit Real Estate Property',
            'new_item' => 'New Real Estate Property',
            'view_item' => 'View Real Estate Property',
            'search_items' => 'Search Real Estate Properties',
            'not_found' => 'No Real Estate Properties found',
            'not_found_in_trash' => 'No Real Estate Properties found in trash',
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-admin-home', // Icon for admin menu
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
            'taxonomies' => array('district'), // Associate with the "District" taxonomy
        );

        register_post_type('real_estate', $args);
    }

    // Registering a new taxonomy "District"
    public function register_district_taxonomy()
    {
        $labels = array(
            'name' => 'Districts',
            'singular_name' => 'District',
            'search_items' => 'Search Districts',
            'all_items' => 'All Districts',
            'parent_item' => 'Parent District',
            'parent_item_colon' => 'Parent District:',
            'edit_item' => 'Edit District',
            'update_item' => 'Update District',
            'add_new_item' => 'Add New District',
            'new_item_name' => 'New District Name',
            'menu_name' => 'Districts',
        );

        $args = array(
            'labels' => $labels,
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
        );

        register_taxonomy('district', array('real_estate'), $args);
    }

    // ACF fields for Real Estate Property
    public function add_acf_fields()
    {
        if (function_exists('acf_add_local_field_group')):

            acf_add_local_field_group(array(
                'key' => 'group_61c3e7c42523e',
                'title' => 'Real Estate Details',
                'fields' => array(
                    array(
                        'key' => 'field_61c3e7c4b8de1',
                        'label' => 'House Name',
                        'name' => 'house_name',
                        'type' => 'text',
                        'instructions' => 'Enter the name of the house.',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                        'readonly' => 0,
                        'disabled' => 0,
                    ),
                    array(
                        'key' => 'field_61c3e7c4b8de2',
                        'label' => 'Location Coordinates',
                        'name' => 'location_coordinates',
                        'type' => 'text',
                        'instructions' => 'Enter the coordinates of the location.',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                        'readonly' => 0,
                        'disabled' => 0,
                    ),
                    array(
                        'key' => 'field_61c3e7c4b8de3',
                        'label' => 'Number of Floors',
                        'name' => 'number_of_floors',
                        'type' => 'number',
                        'instructions' => 'Select the number of floors (1-20).',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => 1,
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'min' => 1,
                        'max' => 20,
                        'step' => 1,
                        'readonly' => 0,
                        'disabled' => 0,
                    ),
                    array(
                        'key' => 'field_61c3e7c4b8de4',
                        'label' => 'Building Type',
                        'name' => 'building_type',
                        'type' => 'radio',
                        'instructions' => 'Select the type of building.',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array(
                            'panel' => 'Panel',
                            'brick' => 'Brick',
                            'foam_block' => 'Foam Block',
                        ),
                        'allow_null' => 0,
                        'other_choice' => 0,
                        'default_value' => '',
                        'layout' => 'vertical',
                        'return_format' => 'value',
                        'save_other_choice' => 0,
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'real_estate',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'shortcode' => 'real_estate_filter', // Shortcode for the filter
            ));

        endif;
    }

    public function real_estate_filter_shortcode()
    {
        ob_start();
        include(plugin_dir_path(__FILE__) . 'templates/real-estate-filter.php');

        return ob_get_clean();
    }

    public function register_real_estate_filter_widget()
    {
        require_once plugin_dir_path(__FILE__) . 'widget-real-estate-filter.php';
        register_widget('RealEstateFilterWidget');
    }

    public static function ajax_filter_function()
    {
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'real_estate_filter_nonce' ) ) {
            die( 'Permission check failed or nonce verification failed.' );
        }

        $args = array(
            'post_type' => 'real_estate',
            'posts_per_page' => -1,
        );

        if (isset($_POST['district']) && $_POST['district'] !== 'all') {
            $args['tax_query'][] = array(
                'taxonomy' => 'district',
                'field' => 'slug',
                'terms' => $_POST['district']
            );
        }

        if (isset($_POST['building_type']) && $_POST['building_type'] !== 'all') {
            $args['meta_query'][] = array(
                'key' => 'building_type',
                'value' => $_POST['building_type']
            );
        }

        if (isset($_POST['number_of_floors']) && $_POST['number_of_floors'] !== 'all') {
            $args['meta_query'][] = array(
                'key' => 'number_of_floors',
                'value' => $_POST['number_of_floors'],
                'type' => 'NUMERIC',
                'compare' => '='
            );
        }

        $query = new WP_Query($args);

        if ($query->have_posts()) :
            while ($query->have_posts()): $query->the_post();


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
            endwhile;
        else :
            echo 'No real estate posts found.';
        endif;

        wp_reset_postdata();
        die();
    }

}

// Initialize the plugin
$real_estate_manager = new RealEstateManager();
