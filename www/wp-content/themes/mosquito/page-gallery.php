<?php get_header(); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<section id="gallery">
			<article id="post-<?php the_ID(); ?>">
              <header>
                <h1><?php the_title(); ?></h1>
                <?php hg_bread_crumbs(); ?>                    
              </header>
				<section>
                
                <div>
                <ul id="galleries">
                <li><img src="http://placehold.it/300x200" /></li>
                <li><img src="http://placehold.it/300x200" /></li>
                <li><img src="http://placehold.it/300x200" /></li>
                <li><img src="http://placehold.it/300x200" /></li>
                <li><img src="http://placehold.it/300x200" /></li>
                <li><img src="http://placehold.it/300x200" /></li>
                </ul>
				</div>

				</section>

			</article>
		</section>
        
	<?php endwhile; endif; ?>

<?php //get_sidebar(); ?>

<?php get_footer(); ?>