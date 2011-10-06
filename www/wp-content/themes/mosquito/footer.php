		<footer>
			<ul>
            	<li><strong><a href="/renovations">Renovations</a></strong> | <a href="/interior/kitchen">Kitchen</a> <a href="/interior/bath">Bath</a> <a href="/interior/basement">Basement</a></li>
                <li><strong><a href="/renovations">Exterior</a></strong> | <a href="/exterior/outdoor-living">Outdoor Living</a> <a href="/exterior/aging-in-place">Aging in Place</a> <a href="/exterior/energy-solutions">Energy Solutions</a></li>
                <li><strong><a href="/about">About</a></strong> | <a href="/about#awards">Awards</a> <a href="/about#certificates">Certificates</a> <a href="/about#testimonials">Testimonials</a></li>
                <?php if ( !is_home() ) { ?><li><strong><a href="/">Home</a></strong></li><?php } ?>
                <li>&copy; <?= date('Y'); ?> <?php bloginfo('name'); ?></li>
            </ul>
            
            <div>
            <img src="http://www.placehold.it/330x190" />
            </div>
            
		</footer>
		<?php wp_footer(); ?>

	</body>
</html>