<?php get_header(); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<section id="team">
			<article id="post-<?php the_ID(); ?>">
              <header>
                <h1><?php the_title(); ?></h1>
                <?php hg_bread_crumbs(); ?>                    
              </header>
				<section>
                
                <div id="tease">
                <img src="http://placehold.it/360x460" />
                </div>
                
                <div id="overview">
				<?php the_content('Read more on "'.the_title('', '', false).'" &raquo;'); ?>    
                </div>

    <?php
    $flickr_photoset_id = '72157626966312775';
    $flickr_api_key = '2d589541fd6f617409903e97d0e10bbe';
    $flickr_username = 'remodelingappleton';
    $flickr_gallery = new phpFlickr($flickr_api_key);
    $flickr_gallery->enableCache('fs', 'cache');
    $flickr_gallery_people = $flickr_gallery->people_findByUsername($flickr_username);
    $flickr_gallery_user_id = $flickr_gallery_people['id'];
    $flickr_gallery_photos = $flickr_gallery->photosets_getPhotos($flickr_photoset_id, null, null);
    ?>

        <div id="slider" class="flickr-gallery">
        <h2>Interior Design Gallery</h2>
          <div class="infiniteCarousel">
              <div class="wrapper">
                  <ul>
                    <?php foreach ($flickr_gallery_photos['photoset']['photo'] as $photo): ?>
                      <li><a href="<?php echo $flickr_gallery->buildPhotoURL($photo, 'Large'); ?>" rel="facebox" title="<?php echo $photo['title']; ?>"><img class="photo" src="<?php echo $flickr_gallery->buildPhotoURL($photo, 'Square'); ?>" width="140" height="100" /></a></li>
                    <?php endforeach; ?>
                  </ul>
              </div>
          </div>          
        </div>

				</section>

			</article>
		</section>
        
	<?php endwhile; endif; ?>

<?php //get_sidebar(); ?>

<?php get_footer(); ?>