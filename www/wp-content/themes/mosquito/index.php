<?php get_header(); ?>

		<section>
			<article id="post-<?php the_ID(); ?>">
				<section>
                	
                  <div>
                  <div class="image"><a href="/interior/"><img src="/images/interior_design.jpg" /></a>
                  <h3><div>Interior Design</div></h3></div>
                  <p>Whether you are interested in a kitchen make-over or are considering a major residential renovation, or a new bathroom oasis, from indoor to outdoor and back again, our design team can help your new space reflect your individual need, taste, interest, and budget from start to finish. After all this is your home and all should reflect who you are.</p>
                  <ul>
                  <li><a href="/testimonials/">Testimonials</a></li>
                  <li><a href="/gallery/">Gallery</a></li>
                  </ul>
                  </div>
                  
                  <div>
                  <div class="image"><a href="/renovations/"><img src="/images/renovations.jpg" /></a>
                  <h3><div>Renovations</div></h3></div>
                  <p>Our award winning renovation team can help you remaster any project from kitchens, baths, basements, home additions or even a new outdoor living area. From conception to finish we will be there with you. From the elaborate to simply updating your home to fit today's needs or tomorrow's future our team can help bring the luster back to your home.</p>
                  <ul>
                  <li><a href="/testimonials/">Testimonials</a></li>
                  <li><a href="/gallery/">Gallery</a></li>
                  </ul>
                  </div>
                  
	              <div>
                  <div class="image"><a href="/outdoor-living/"><img src="/images/green_remodeling.jpg" /></a>
                  <h3><div>Green Remodeling</div></h3></div>
                  <p>Home performance solutions and environmentally friendly interior and exterior projects designed to help you reduce energy costs and improve indoor air quality. Learn what using the right products and processes can do for you from a Green Certified Professional of the National Association of the Remodeling Industry.</p>
                  <ul>
                  <li><a href="/testimonials/">Testimonials</a></li>
                  <li><a href="/gallery/">Gallery</a></li>
                  </ul>
                  </div>
                
					<?php //the_content('Read more on "'.the_title('', '', false).'" &raquo;'); ?>

				</section>
				<footer>
					<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
				</footer>
			</article>
		</section>

<?php //get_sidebar(); ?>

<?php get_footer(); ?>