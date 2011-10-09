		<footer>
			<ul>
            	<li><strong><a href="/renovations">Renovations</a></strong> | <a href="/interior/kitchen">Kitchen</a> <a href="/interior/bath">Bath</a> <a href="/interior/basement">Basement</a></li>
                <li><strong><a href="/renovations">Exterior</a></strong> | <a href="/exterior/outdoor-living">Outdoor Living</a> <a href="/exterior/aging-in-place">Aging in Place</a> <a href="/exterior/energy-solutions">Energy Solutions</a></li>
                <li><strong><a href="/about">About</a></strong> | <a href="/about#awards" rel="awards">Awards</a> <a href="/about#certificates" rel="certificates">Certificates</a> <a href="/about#testimonials" rel="testimonials">Testimonials</a></li>
                <?php if ( !is_home() ) { ?><li><strong><a href="/">Home</a></strong></li><?php } ?>
                <li>&copy; <?= date('Y'); ?> <?php bloginfo('name'); ?></li>
            </ul>
            
            <div id="contact">
              <img src="/images/footer_woodage.png" alt="Contact and address information" />
              <div>
                <h2>Mosquito Creek</h2>
                <strong>Home Renovations & Outdoor Living</strong>
                <ul>
                <li class="addr">1120 East Wisconsin Ave<br />
                    Appleton, WI 54911</li>
                <li class="phone"><a href="tel:9207308519">920 730-8519</a></li>
                </ul>
                <a href="/about#contact" rel="contact" class="submit">Ask a Question</a>
              </div>
            </div>
            
		</footer>
		<?php wp_footer(); ?>

	</body>
</html>