<?php get_header(); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<section id="interior">
			<article id="post-<?php the_ID(); ?>">
              <header>
                <h1><?php the_title(); ?></h1>
                <?php hg_bread_crumbs(); ?>                    
              </header>
				<section>
                
				<?php the_content('Read more on "'.the_title('', '', false).'" &raquo;'); ?>    
                
                  <div>
                  <div class="image"><a href="/interior/kitchen"><img src="/images/bg/kitchen_sm.jpg" />
                  <h3><div>Kitchen</div></h3></a></div>
                  </div>
                    
                  <div>
                  <div class="image"><a href="/interior/bath"><img src="/images/bg/bathroom_sm.jpg" />
                  <h3><div>Bath</div></h3></a></div>
                  </div>
                  
                  <div>
                  <div class="image"><a href="/interior/team"><img src="/images/bg/design_team_sm.jpg" />
                  <h3><div>Our Design Team</div></h3></a></div>
                  </div>
               

				</section>

			</article>
		</section>
        
	<?php endwhile; endif; ?>

<?php //get_sidebar(); ?>

<?php get_footer(); ?>