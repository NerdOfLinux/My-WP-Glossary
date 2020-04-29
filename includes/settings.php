<?php
/*
  Createa and register the settings page.
*/
if ( !class_exists( 'Register_Glossary_Settings_Page' ) ) {
    class Register_Glossary_Settings_Page {
        /*
          Constructor. Registers the settings page.
        */
        public function __construct() {
            add_action( 'admin_menu', array( $this, 'create_settings_page' ) );
            add_action( 'admin_init', array( $this, 'create_settings_options' ) );
        }

        /*
          Create the settings page
        */
        public function create_settings_page() {
            add_options_page(
                // Page title
                __( 'Glossary Settings', 'textdomain' ),
                // Menu title
                __( 'Glossary', 'textdomain' ),
                // Require capability
                'manage_options',
                // Menu slug
                'my_wp_glossary',
                // Function to run to generate the page
                array( $this, 'settings_page_content' )
            );
        }

        /*
          Regsiter settings
        */
        public function create_settings_options() {
            // Plugin settings
            register_setting( 'my_wp_glossary', 'my_wp_glossary_options' );

            // Load the settings
            $default_options = array(
                'show_on_home_page' => 'off',
                'show_in_search' => 'off',
                'show_in_api' => 'off'
            );
            $this->options = wp_parse_args( get_option( 'my_wp_glossary_options' ), $default_options );
        
            // Register the visibility section
            add_settings_section(
                // ID
                'my_wp_glossary_options',
                // Title
                __( 'Visibility Settings', 'wordpress' ),
                // Callback
                array( $this, 'visibility_settings_section' ),
                // Page
                'my_wp_glossary'
            );

            // Register the settings fields
            add_settings_field(
                //ID
                'my_wp_glossary_show_on_home_page_option',
                // Title
                __( 'Show Glossary Items On Home Page', 'wordpress' ),
                // Callback
                array( $this, 'show_on_home_page_settings_field' ),
                // Page
                'my_wp_glossary',
                // Section
                'my_wp_glossary_options'
            );

            add_settings_field(
                //ID
                'my_wp_glossary_show_in_search_option',
                // Title
                __( 'Show Glossary Items In Search', 'wordpress' ),
                // Callback
                array( $this, 'show_in_search_settings_field' ),
                // Page
                'my_wp_glossary',
                // Section
                'my_wp_glossary_options'
            );

            add_settings_field(
                //ID
                'my_wp_glossary_show_in_rest_option',
                // Title
                __( 'Show Glossary Items In API (Enables Gutenberg)', 'wordpress' ),
                // Callback
                array( $this, 'show_in_api_settings_field' ),
                // Page
                'my_wp_glossary',
                // Section
                'my_wp_glossary_options'
            );
        }

        /*
          Visbility Settings Section Header
        */
        public function visibility_settings_section() {
            echo '<p>These settings control where your glossary entries can be seen.</p>';
        }

        /*
          Show the home page option field
        */
        public function show_on_home_page_settings_field() {
            echo '<input type="checkbox" name="my_wp_glossary_options[show_on_home_page]" ' . checked( 'on', $this->options['show_on_home_page'], false ) . '>';
        }

        /*
          Show the search option field
        */
        public function show_in_search_settings_field() {
            echo '<input type="checkbox" name="my_wp_glossary_options[show_in_search]" ' . checked( 'on', $this->options['show_in_search'], false ) . '>';
        }

        /*
          Show the API option field
         */
        public function show_in_api_settings_field() {
            echo '<input type="checkbox" name="my_wp_glossary_options[show_in_api]" ' . checked( 'on', $this->options['show_in_api'], false ) . '>';
        }

        /*
          Build the settings page
        */
        public function settings_page_content() {
            echo '<form action="options.php" method="post">';
            echo '<h1>My WP Glossary Settings</h1>';
            settings_fields( 'my_wp_glossary' );
            do_settings_sections( 'my_wp_glossary' );
            submit_button();
            echo '</form>';
        }
    }
    new Register_Glossary_Settings_Page;
}
