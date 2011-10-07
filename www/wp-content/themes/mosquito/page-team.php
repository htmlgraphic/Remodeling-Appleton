<?php get_header(); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<section id="team">
			<article id="post-<?php the_ID(); ?>">
              <header>
                <h1><?php the_title(); ?></h1>
                <?php the_breadcrumb(); ?>                    
              </header>
				<section>
                
                <div>
                <img src="http://placehold.it/360x460" />
                </div>
                
                <div id="overview">
				<?php the_content('Read more on "'.the_title('', '', false).'" &raquo;'); ?>    
                </div>
                
                <div>
                <h2>Photo Galleries</h2>
                <ul id="galleries">
                <li><img src="http://placehold.it/160x160" /></li>
                <li><img src="http://placehold.it/160x160" /></li>
                <li><img src="http://placehold.it/160x160" /></li>
                <li><img src="http://placehold.it/160x160" /></li>
                <li><img src="http://placehold.it/160x160" /></li>
                </ul>
				</div>

				</section>

			</article>
		</section>
        
	<?php endwhile; endif; ?>

<?php //get_sidebar(); ?>

<?php get_footer(); ?>