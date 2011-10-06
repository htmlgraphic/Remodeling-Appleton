<?php get_header(); ?>

		<section>
			<article id="post-<?php the_ID(); ?>">
				<section>
                	
                    <div><a href="/interior"><img src="http://www.placehold.it/295x270" /></a>
                  <h3>Interior Design</h3>
                  <p>Whether you are interested in a kitchen make over are considering a major residential renovation, our design team can help your new space reflect your individual need, taste, interest, and budget.</p></div>
                    <div><a href="/renovations"><img src="http://www.placehold.it/295x270" /></a>
                  <h3>Renovations</h3>
                  <p>Our award winning renovation team can help you remaster any project from kitchens, baths, basements, home additions or even a new outdoor living area. From conception to finish we will be there with you.</p></div>
	                <div><a href="/outdoor-living"><img src="http://www.placehold.it/295x270" /></a>
                  <h3>Green Remodeling</h3>
                  <p>Home performance solutions and environmentally friendly interior and exterior projects designed to help you reduce energy costs and improve indoor air quality. Learn what using the right products and processes can do for you from a Green Certified Professional and Green Remodeling Educator of the National Association of the Remodeling Industry.</p></div>
                
					<?php //the_content('Read more on "'.the_title('', '', false).'" &raquo;'); ?>

				</section>
				<footer>
					<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
				</footer>
			</article>
		</section>

<?php //get_sidebar(); ?>

<?php get_footer(); ?>