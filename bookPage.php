<?php
 ?>
 <h2>Book Search</h2>
 <form id="BookFilter">
   <div class="row mb-20">
     <div class="col-4">
        <label>Book Name</label>
         <input type="text" name="bookName">
     </div>
     <div class="col-4">
        <label>Author Name</label>
         <input type="text" name="authorName">
     </div>
     <div class="col-4">
        <label>Publisher Name</label>
         <input type="text" name="publisherName">
     </div>
     <div class="col-4">
        <label>Rating</label>
         <select name="rating">
           <option value="0">Select</option>
           <option value="1">1</option>
           <option value="2">2</option>
           <option value="3">3</option>
           <option value="4">4</option>
           <option value="5">5</option>
         </select>
     </div>
   </div>
   <?php
   $args = array(
     'post_type'=> 'books',
     'order'    => 'ASC'
     );

 $the_query = new WP_Query( $args );
 global $post;
 $price = array();
 if($the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();
 $price[] = get_post_meta($post->ID, 'price', true);
endwhile;
endif;
$minPrice = min($price);
$maxPrice = max($price);
  ?>
   <div class="ui-content">
     <div data-role="rangeslider">
         <label for="price-min">Price:</label>
         <input type="range" name="price-min" class="w-40" id="price-min" value="<?= $minPrice; ?>" min="<?= $minPrice; ?>" max="<?= $maxPrice; ?>">
         <label for="price-max">Price:</label>
         <input type="range" name="price-max" class="w-40" id="price-max" value="<?= $maxPrice; ?>" min="<?= $minPrice; ?>" max="<?= $maxPrice; ?>">
       </div>
       </div>
       <div class="row mb-20">
     <div class="col-4">
       <button class="btn sendQuery" data-url="<?php echo plugin_dir_url( __FILE__ );?>ajax.php" type="button">Search</div>
     </div>
   </div>
 </form>
 <hr>
 <div id="bookTable">
   <table>
     <thead>
       <tr>
         <th>No.</th>
         <th>Book Name</th>
         <th>Price</th>
         <th>Author</th>
         <th>Publisher</th>
         <th>Rating</th>
       </tr>
     </thead>
     <tbody>
    <?php
    $args = array(
      'post_type'=> 'books',
      'order'    => 'ASC'
      );

  $the_query = new WP_Query( $args );
  $number = 1;
  global $post;
  $price = array();
  if($the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();
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
  endif;
    ?>
</tbody>
   </table>
 </div>

 <?php

?>
