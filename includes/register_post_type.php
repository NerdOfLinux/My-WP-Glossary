<?php
/*
  Class to register the glossary post type.
 */
if ( !class_exists( 'My_WP_Glossary_Type' ) ) {
    class My_WP_Glossary_Type {
        private $options;
        /*
          Class constructure. Registers the hook.
         */
        public function __construct() {
            // Load the plugin options
            $default_options = array(
                'show_on_home_page' => 'off',
                'show_in_search' => 'off',
                'show_in_api' => 'off'
            );
            $this->options = wp_parse_args( get_option( 'my_wp_glossary_options' ), $default_options );

            add_action( 'init', array( $this, 'create_glossary_type' ) );
            // Add glossary items to front page if necessary
            if ( 'on' === $this->options['show_on_home_page'] ) {
                add_filter( 'pre_get_posts', array( $this, 'add_glossary_items_to_home' ) );
            }

            // Order archives alphabetically
            add_filter( 'pre_get_posts', array( $this, 'order_archive_alphabetically' ) );
        }

        /*
          Add glossary items to the home page
         */
        public function add_glossary_items_to_home( $query ) {
            // Only apply to the home page
            if ( is_home() ) {
                $query->set( 'post_type', array( 'post', 'glossary' ) );
            }
            return $query;
        }

        /*
          Order glossary archives alphabetically instead of by date
         */
        public function order_archive_alphabetically( $query ) {
            if ( is_post_type_archive( 'glossary' ) ) {
                $query->set( 'order', 'ASC' );
                $query->set( 'orderby', 'title' );
            }
            return $query;
        }

        /*
          Create the glossary type.
         */
        public function create_glossary_type() {
            register_post_type( 'glossary',
                                array(
                                    'labels' => array(
                                        'name' => __( 'Glossary Items', 'textdomain' ),
                                        'singular_name' => __( 'Glossary Item', 'textdomain' )
                                    ),
                                    'public' => TRUE,
                                    'has_archive' => TRUE,
                                    // Custom slug
                                    'rewrite' => array( 'slug' => 'glossary' ),
                                    'menu_icon' => 'dashicons-book-alt',
                                    // Show in API
                                    'show_in_rest' => 'on' === $this->options['show_in_api'],
                                    // Test if glossary items should be excluded from search
                                    'exclude_from_search' => 'on' !== $this->options['show_in_search']
                                )
            );
        }

    }
    new My_WP_Glossary_Type;
}
