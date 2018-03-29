<?php

// Hiding Warning and Notices

ini_set('log_errors','On');
ini_set('display_errors','Off');
ini_set('error_reporting', E_ALL );
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);

function bookplugin_enqueue_styles()
{
  wp_enqueue_style( 'bookplugin-style', plugins_url( '/asset/css/style.css', __FILE__ ) );
}
function bookplugin_enqueue() {
    wp_enqueue_style( 'bookplugin-jq-ui-style', 'https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.css');
    wp_enqueue_style( 'bookplugin-style-front', plugins_url( '/asset/css/style.css', __FILE__ ) );
    wp_enqueue_script( 'bookplugin-js', 'https://code.jquery.com/jquery-1.11.3.min.js');
    wp_enqueue_script( 'bookplugin-jq-ui', 'https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js');
    wp_enqueue_script( 'bookplugin-js-front', plugins_url( '/asset/js/custom.js', __FILE__ ) );
}

add_action( 'wp_enqueue_scripts', 'bookplugin_enqueue' );

add_action('admin_enqueue_scripts', 'bookplugin_enqueue_styles' );

//Adding Custom WP Query Variables

add_filter( 'posts_where', 'bookName_posts_where', 10, 2 );
function bookName_posts_where( $where, &$wp_query )
{
    global $wpdb;
    if ( $bookName_title = $wp_query->get( 'bookName_title' ) ) {
        $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'' . esc_sql( $wpdb->esc_like( $bookName_title ) ) . '%\'';
    }
    return $where;
}

// get taxonomy LIKE

function authorName_get_terms_fields( $clauses, $taxonomies, $args ) {
    if ( ! empty( $args['authors'] ) ) {
        global $wpdb;
        $authors_like = $wpdb->esc_like( $args['authors'] );
        if ( ! isset( $clauses['where'] ) )
            $clauses['where'] = '1=1';
        $clauses['where'] .= $wpdb->prepare( " AND t.name LIKE %s OR t.name LIKE %s", "$authors_like%", "% $authors_like%" );
    }
    return $clauses;
}

add_filter( 'terms_clauses', 'authorName_get_terms_fields', 10, 3 );

function publisherName_get_terms_fields( $clauses, $taxonomies, $args ) {
    if ( ! empty( $args['publishers'] ) ) {
        global $wpdb;
        $publishers_like = $wpdb->esc_like( $args['publishers'] );
        if ( ! isset( $clauses['where'] ) )
            $clauses['where'] = '1=1';
        $clauses['where'] .= $wpdb->prepare( " AND t.name LIKE %s OR t.name LIKE %s", "$publishers_like%", "% $publishers_like%" );
    }
    return $clauses;
}

add_filter( 'terms_clauses', 'publisherName_get_terms_fields', 10, 3 );

// Our custom post type function
function create_posttype_book() {

    register_post_type( 'books',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Books' ),
                'singular_name' => __( 'Book' )
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'book'),
        )
    );
}
// Hooking up our function to theme setup
add_action( 'init', 'create_posttype_book' );

//hook into the init action and call create_book_taxonomies when it fires
add_action( 'init', 'create_authors_hierarchical_taxonomy', 0 );
add_action( 'init', 'create_publisher_hierarchical_taxonomy', 0 );

//create a custom taxonomy name it authors for your posts

function create_authors_hierarchical_taxonomy() {

// Add new taxonomy, make it hierarchical like categories
//first do the translations part for GUI

  $labels_author = array(
    'name' => _x( 'Authors', 'taxonomy general name' ),
    'singular_name' => _x( 'Author', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Authors' ),
    'all_items' => __( 'All Authors' ),
    'parent_item' => __( 'Parent Author' ),
    'parent_item_colon' => __( 'Parent Author:' ),
    'edit_item' => __( 'Edit Author' ),
    'update_item' => __( 'Update Author' ),
    'add_new_item' => __( 'Add New Author' ),
    'new_item_name' => __( 'New Author Name' ),
    'menu_name' => __( 'Authors' ),
  );

// Now register the taxonomy

  register_taxonomy('authors',array('books'), array(
    'hierarchical' => true,
    'labels' => $labels_author,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'author' ),
  ));

  wp_insert_term('Anita Desai','authors');
  wp_insert_term('Arundhati Roy','authors');
  wp_insert_term('Arvind Adiga','authors');
  wp_insert_term('Chitra Banerjee Divakaruni','authors');
  wp_insert_term('Kamala Markandaya','authors');
  wp_insert_term('Khushwant Singh','authors');
  wp_insert_term('R.K. Narayan','authors');
  wp_insert_term('Robin Sharma','authors');
  wp_insert_term('Rohinton Mistry','authors');
  wp_insert_term('Shashi Tharoor','authors');

}

function create_publisher_hierarchical_taxonomy() {

// Add new taxonomy, make it hierarchical like categories
//first do the translations part for GUI

  $labels_publishers = array(
    'name' => _x( 'Publishers', 'taxonomy general name' ),
    'singular_name' => _x( 'Publisher', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Publishers' ),
    'all_items' => __( 'All Publishers' ),
    'parent_item' => __( 'Parent Publisher' ),
    'parent_item_colon' => __( 'Parent Publisher:' ),
    'edit_item' => __( 'Edit Publisher' ),
    'update_item' => __( 'Update Publisher' ),
    'add_new_item' => __( 'Add New Publisher' ),
    'new_item_name' => __( 'New Publisher Name' ),
    'menu_name' => __( 'Publishers' ),
  );

// Now register the taxonomy

  register_taxonomy('publishers',array('books'), array(
    'hierarchical' => true,
    'labels' => $labels_publishers,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'publisher' ),
  ));

  wp_insert_term('Jaico Publishing House','publishers');
  wp_insert_term('Penguin Random House India','publishers');
  wp_insert_term('Roli Books','publishers');
  wp_insert_term('Rupa Publications','publishers');
  wp_insert_term('Westland Publications','publishers');

}
add_action( 'add_meta_boxes', 'meta_box_add' );
function meta_box_add()
{
    add_meta_box( 'otherDetails', 'Other Details', 'meta_box_fields', 'books', 'normal', 'high' );
}

function meta_box_fields()
{
   global $post;
   $price = get_post_meta($post->ID, 'price', true);
   $rating = get_post_meta($post->ID, 'rating', true);
  ?>
  <div class="mb-20">
    <label for="price" class="control-label">Price</label>
    <input type="number" name="price" id="price" value="<?php echo $price;?>"/>
  </div>
   <div class="mb-20">
     <label for="rating" class="control-label">Rating</label>
     <?php
     for ($i=1; $i<=5 ; $i++) {
       ?>
        <label class="redio_button"><input type="radio" name="rating" <?php echo ($rating == $i ? 'checked' : '') ;?> value="<?php echo $i;?>"><?php echo $i;?></label>
       <?php
     }
      ?>
   </div>
 <?php
}

//Save and Update Post Data
function save_custom_meta($post_id) {

  // Make sure your data is set before trying to save it
  if( isset( $_POST['price'] ) ){
    update_post_meta( $post_id, 'price', $_POST['price']);
  }
  if( isset( $_POST['rating'] ) ){
    update_post_meta( $post_id, 'rating', $_POST['rating']);
  }
}
add_action('save_post', 'save_custom_meta');

add_filter( 'single_template', 'wpsites_custom_post_type_template' );
function wpsites_custom_post_type_template($single_template) {
     global $post;

     if ($post->post_type == 'books' ) {
          $single_template = dirname( __FILE__ ) . '/post-type-template.php';
     }
     return $single_template;
}
 ?>
