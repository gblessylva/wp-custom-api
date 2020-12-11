<?php
/**
 * Plugin Name:       WP Custom API Endpoint
 * Plugin URI:        https://github.com/wp-custom-api
 * Description:       A Basic Custom API Generator
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            gblessylva
 * Author URI:        https://github.com/gblessylva/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp_custom_plugin
 * Domain Path:       /languages
 */

//Create a custom post type on init

add_action('init', 'create_portfolio');

function create_portfolio(){
    register_post_type('portfolio', array(
        'labels'=> array(
            'name' => 'Custom Portfolios',
            'singular_name' => 'Custom Portfolio',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Custom Portfolio',
            'edit' => 'Edit',
            'edit_item' => 'Edit Custom Portfolio',
            'new_item' => 'New Custom Portfolio',
            'view' => 'View',
            'view_item' => 'View Custom Portfolios',
            'search_items' => 'Search Custom Portfolios',
            'not_found' => 'No Custom Portfolios found',
            'not_found_in_trash' => 'No Custom Portfolios found in Trash',
            'parent' => 'Parent Custom Portfolio'
        ),
        'public' => true,
        'description'=> 'Create an Awsome Custom Portfolio',
        'menu_position' => 15,
        'supports' => array( 'title', 'editor', 'comments', 'thumbnail', 'custom-fields' ),
        'taxonomies' => array( '' ),
        'menu_icon' => plugins_url( 'images/image.png', __FILE__ ),
        'has_archive' => true

    ));
};




 function get_custom_posts(){

    $args =[
        'numberofposts' => 9999,
        'post_type' => 'portfolio'
    ];
    $posts = get_posts($args);
    $data = [];
    $i=0;
    foreach ($posts as $post) {
        $data[$i]['id'] = $post->ID;
        $data[$i]['title'] = $post->post_title;
        $data[$i]['content']= $post->post_content;
        $data[$i]['name']= $post->post_name;
        $data[$i]['slug']= $post->slug;
        $data[$i]['featured_image']['thumbnail'] = get_the_post_thumbnail_url($post->ID, 'thumbnail');
        $data[$i]['featured_image']['medium'] = get_the_post_thumbnail_url($post->ID, 'medium');
        $data[$i]['featured_image']['large'] = get_the_post_thumbnail_url($post->ID, 'large');

        $i++;

    }
    
    return $data;
 }

 //Function to get Single post by slug
function get_single_post($slug){
    $data = [];
    $args = [
        'name'=> $slug['slug'],
        'post_type'=> 'portfolio'
    ];

    $post= get_posts($args);

    $data['id'] = $post[0]->ID;
    $data['title'] = $post[0]->post_title;
    $data['content']= $post[0]->post_content;
    $data['name']= $post[0]->post_name;
    $data['slug']= $post[0]->slug;
    $data['featured_image']['thumbnail'] = get_the_post_thumbnail_url($post[0]->ID, 'thumbnail');
    $data['featured_image']['medium'] = get_the_post_thumbnail_url($post[0]->ID, 'medium');
    $data['featured_image']['large'] = get_the_post_thumbnail_url($post[0]->ID, 'large');

    return $data;
};


//Gel ALL Posts Here
 add_action('rest_api_init', function() {
    register_rest_route('custom-route/v1', 'portfolios', [
        'methods' => 'GET',
        'callback' => 'get_custom_posts'
    ]);
 });

 //Get SIngle post by slug
 add_action('rest_api_init', function(){ 
     register_rest_route('custom-route/v1', 'portfolios/(?P<slug>[a-zA-Z0-9-]+)', array(
    'methods' => 'GET',
    'callback' => 'get_single_post'
));
 }
);