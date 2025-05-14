<?php


function get_html_block_content($atts) {
    // Extract shortcode attributes
    $attributes = shortcode_atts(array(
        'id' => 0 // Default block ID
    ), $atts);

    $block_id = absint($attributes['id']);

    // Return empty if no ID provided
    if ($block_id === 0) {
        return '';
    }

    // Get the block content
    $block = get_post($block_id);

    // Check if block exists and is of type 'wp_block'
    if (!$block || $block->post_type !== 'wp_block') {
        return '';
    }

    // Return the parsed content
    return do_blocks($block->post_content);
}

// Register the shortcode
add_shortcode('html_block', 'get_html_block_content');
