<?php

$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );

if (isset($_GET["doFilter"]) && !empty($_GET["doFilter"])) {
  $bookName = $_POST['bookName'];
  $authorName = trim($_POST['authorName']);
  $publisherName = trim($_POST['publisherName']);
  $rating = $_POST['rating'];
  $priceMin = $_POST['price-min'];
  $priceMax = $_POST['price-max'];
  $authors = get_terms('authors', 'authors='.$authorName.'');
  $publishers = get_terms('publishers', 'authors='.$publisherName.'');
  $author_slugs = wp_list_pluck( $authors, 'slug' );
  $publisher_slugs = wp_list_pluck( $publishers, 'slug' );

  print_r($term_slugs);

  global $wpdb;



  $args = array(
                'post_type'      => 'books',
                'bookName_title' => $bookName,
                'tax_query' => array(
                                      'relation' => 'AND',
                                      array(
                                            'taxonomy' => 'authors',
                                            'field'    => 'slug',
                                            'terms'    => $author_slugs,
                                            ),
                                      array(
                                            'taxonomy' => 'publishers',
                                            'field'    => 'slug',
                                            'terms'    => $publisher_slugs,
                                            ),
                                      ),
                                      'meta_query' => array(
                                                      array(
                                                        'key' => 'price',
                                                        'value'   => array( $priceMin, $priceMax ),
                                                  			'type'    => 'numeric',
                                                  			'compare' => 'BETWEEN',
                                                      )
                                      )
                );
if($rating != 0){
  $args['meta_query'][1] = array(
                              array(
                              'key'     => 'rating',
                              'value'   => $rating,
                              )
                        );
}

  $the_query = new WP_Query( $args );

  $number = 1;

  global $post;

  if($the_query->have_posts() ){
    while ( $the_query->have_posts() ) :
      $the_query->the_post();
      $authors = wp_get_object_terms($post->ID, 'authors');
      $publishers = wp_get_object_terms($post->ID, 'publishers');
  ?>
      <tr>
        <td><?= $number; ?></td>
        <td><a href="<?= the_permalink();?>"><?= the_title();?></a></td>
        <td><?php echo get_post_meta($post->ID, 'price', true).'/-'; ?></td>
        <td><a href=""><?= $authors[0]->name; ?></a></td>
        <td><a href=""><?= $publishers[0]->name; ?></a></td>
        <td><?php
        $rating = get_post_meta($post->ID, 'rating', true);
        for ($i=1; $i <= $rating; $i++) {
          ?>
          <img src="<?php echo plugins_url( '/asset/img/star.png', __FILE__ ) ?>">
          <?php
        } ?>
          </td>
      </tr>

  <?php
  $number++;
  endwhile;
  }
else {
  echo "Sorry!! We couldn't find book for you.";
  }
}
 ?>
