<?php get_header(); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<section id="renovations">
			<article id="post-<?php the_ID(); ?>">
              <header>
                <h1><?php the_title(); ?></h1>
                <?php hg_bread_crumbs(); ?>                    
              </header>
				<section>
                
				<?php the_content('Read more on "'.the_title('', '', false).'" &raquo;'); ?>    
                
                  <div class="interior">
                  <h3 class="transparent">interior</h3>
                  <ul>
					<li class="transparent-lite"><a href="/exterior/aging-in-place">Aging in Place</a></li>
                    <li class="transparent-lite"><a href="/interior/basement">Basement Renovations</a></li>
                    <li class="transparent-lite"><a href="/interior/bath">Bath Renovations</a></li>
                    <li class="transparent-lite"><a href="/interior/kitchen">Kitchen Renovations</a></li>
                  </ul>
                  </div>  
                    

                  <div class="exterior">
                  <h3 class="transparent">exterior</h3>
                  <ul>
					<li class="transparent-lite"><a href="/exterior/energy-solutions">Energy Solutions</a></li>
                    <li class="transparent-lite"><a href="/exterior/additions">Home Additions</a></li>
                    <li class="transparent-lite"><a href="/exterior/outdoor-living">Outdoor Living</a></li>
                    <li class="transparent-lite"><a href="/exterior/improvements">Roofing &amp; Siding</a></li>
                  </ul>
                  </div>
               

				</section>

			</article>
		</section>
        
	<?php endwhile; endif; ?>

<?php //get_sidebar(); ?>

<?php get_footer(); ?>