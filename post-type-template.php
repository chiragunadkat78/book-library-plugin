<?php
get_header();
 ?>
 <div class="content-area">
 	<main id="main" class="site-main" role="main">
 			<?php
 				// Start the Loop.
 			while ( have_posts() ) :
 				the_post();
				$authors = wp_get_object_terms($post->ID, 'authors');
			  $publishers = wp_get_object_terms($post->ID, 'publishers');
				?>
				<article class="twentyseventeen-panel page type-page status-publish hentry">
				 	<div class="panel-content">
				 		<div class="wrap">
				 			<header class="entry-header">
				 				<h2 class="entry-title"><?php the_title(); ?></h2>
				 			</header><!-- .entry-header -->
				 			<div class="entry-content">
				 				 <?php the_content(); ?>
								 <hr>
								 <p><strong>Price :</strong> <?= get_post_meta($post->ID, 'price', true).'/-'; ?></p>
								 <p><strong>Rating :</strong> <?php
					       $rating = get_post_meta($post->ID, 'rating', true);
					       for ($i=1; $i <= $rating; $i++) {
					         ?>
					         <img src="<?php echo plugins_url( '/asset/img/star.png', __FILE__ ) ?>">
					         <?php
					       } ?></p>
							 	 <p><strong>Author :</strong> <?= $authors[0]->name; ?></p>
								 <p><strong>Publisher :</strong> <?= $publishers[0]->name; ?></p>
							 </div>
				 			</div><!-- .entry-content -->

				 		</div><!-- .wrap -->
				 	</article>
				<?php
 				endwhile;
 			?>
		</main>
		</div>
 <?php get_footer(); ?>
