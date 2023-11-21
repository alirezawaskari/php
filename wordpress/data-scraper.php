<?php
/*
Plugin Name: Data Scraper
Description: A WordPress plugin for web scraping.
Version: 1.0
Author: alirezawaskari
*/

function get_external_html()
{
    // URL of the external website's index file
    $external_url = 'https://example.com';

    // Fetch the entire HTML content of the external website's index file
    $external_content = @file_get_contents($external_url);

    if ($external_content === false) {
        return 'Unable to fetch content.';
    }

    // Use DOMDocument to parse the HTML content
    $doc = new DOMDocument();
    libxml_use_internal_errors(true); // Enable internal error handling
    $doc->loadHTML(mb_convert_encoding($external_content, 'HTML-ENTITIES', 'UTF-8'));
    libxml_clear_errors();

    // Get the specific section by its ID or class or any other selector
    $data = $doc->getElementById('your-section-id'); // Replace 'your-section-id' with the actual ID

    if ($data) {
        // Get the HTML of the section
        $data_html = $doc->saveHTML($data);
        return $data_html; // Return the extracted HTML section
    } else {
        return 'Data not found or ID mismatch.';
    }
}

class Scraped_Data_Widget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'scraped_data_widget',
            __('Scraped Data Widget', 'text_domain'),
            array('description' => __('Displays scraped data in the sidebar', 'text_domain'))
        );
    }

    public function widget($args, $instance)
    {
        echo '<div class="widget scraped-data-widget">';
        echo do_shortcode('[get_external]');
        echo '</div>';
    }
}

function register_scraped_data_widget()
{
    register_widget('Scraped_Data_Widget');
}
add_action('widgets_init', 'register_scraped_data_widget');


add_shortcode('get_external', 'get_external_html');
?>
