<?php
if ( !class_exists( 'My_WP_Glossary_Shortcode' ) ) {
    class My_WP_Glossary_Shortcode {
        private $cache_key = 'my_wp_glossary_index_cache';
        
        /*
          Register the shortcode
        */
        public function __construct() {
            // Add custom title_starts_with to WP_Query
            add_filter( 'posts_where', array( $this, 'title_starts_with' ), 10, 2 );
            // Register the shortcode
            add_shortcode( 'my_wp_glossary_index', array( $this, 'shortcode_action' ) );
            // Clear the cache on post update
            add_action( 'save_post', array( $this, 'clear_cache' ), 10, 3 );
        }

        /*
          Custom SQL for WP Query to get all titles that start with a letter
        */
        public function title_starts_with( $where, $query ) {
            global $wpdb;
            
            $title_starts_with = $query->get( 'title_starts_with' );
            
            // If title_starts_with isn't empty
            if ( !empty( $title_starts_with ) ) {
                $where .= " AND $wpdb->posts.post_title LIKE '$title_starts_with%'";
            }

            return $where;
        }

        /*
          Generate the glossary index page
        */
        public function shortcode_action() {
            // First, check for the cache
            $this->cache_key = 'my_wp_glossary_index_cache';
            // get_transient returns false on no value
            if ( false !== ( $page_html = get_transient( $this->cache_key ) ) ) {
                return $page_html;
            }
            
            // Create a variable to store the page data
            $page_html = '';
            $page_heading = '<!-- Generated at ' . time() . ' -->';

            foreach( range( 'A', 'Z' ) as $letter ) {
                // Set query arguments
                $args = array(
                    'post_type' => 'glossary',
                    'orderby' => 'title',
                    'order' => 'ASC',
                    'title_starts_with' => $letter
                );
            
                // Generate a new query
                $the_query = new WP_Query( $args );

                // Loop
                if ( $the_query->have_posts() ) {
                    // Add the letter the the heading
                    $page_heading .= '<a href="#my_wp_glossary_' . $letter . '">' . $letter . '</a>&nbsp;';
                    // Add a header
                    $page_html .= "<h2 id=\"my_wp_glossary_$letter\">$letter</h2><p>";
                    while ( $the_query->have_posts() ) {
                        $the_query->the_post();
                        $page_html .= '<a href="' . get_post_permalink() . '">' . get_the_title() . '</a><br>';
                    }
                }
                $page_html .= '</p>';
                wp_reset_postdata();
            }
            // Save to cache
            $page_html = $page_heading . $page_html;
            set_transient( $this->cache_key, $page_html, HOUR_IN_SECONDS );
            return $page_html;
        }

        /*
          Clears the cache if a glossary item is updated
         */
        public function clear_cache( $post_id, $post, $update ) {
            // Check if the post was a glossary
            if ( 'glossary' === get_post_type( $post ) ) {
                // Clear the cache
                delete_transient( $this->cache_key );
            }
        }
    }
    // Create a new class instance
    new My_WP_Glossary_Shortcode;
}
