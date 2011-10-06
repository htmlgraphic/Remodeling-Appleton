<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>

		<!-- "H5": The HTML-5 WordPress Template Theme -->
		<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>">
		<title><?php bloginfo('name'); ?> <?php wp_title(); ?></title>
		<meta name="description" content="<?php bloginfo('description'); ?>">

		<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" media="screen">
		<link rel="alternate" type="text/xml" title="<?php bloginfo('name'); ?> RSS 0.92 Feed" href="<?php bloginfo('rss_url'); ?>">
		<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>">
		<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS 2.0 Feed" href="<?php bloginfo('rss2_url'); ?>">
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

		<?php wp_enqueue_script('jquery'); ?>
		<?php wp_head(); ?>

		<script src="<?php bloginfo('template_directory'); ?>/javascript/h5.js"></script>
        
        <?php if (get_page($page_id)->ID == 14): // Digital / Paper Subscription ?>
		<script>
          $(function() {
            $('#about-us-navigation-menu ul li a').click(function(event) {
              event.preventDefault();
              $('.about-subpage').hide();
              $('#about-subpage-' + $(this).attr('rel')).show();
            });
          });
        </script>
        <?php endif; ?>
        
	</head>
	<body <?php body_class(); ?>>

		<header>
        	<a href="/"><img src="/images/logos/mosquito.png" alt="<?php bloginfo('description'); ?>"></a>
			<?php /*?>	
				<h1><a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a></h1>
				<h2><?php bloginfo('description'); ?></h2>
            <?php */?>
            <div class="phone"><a href="tel:9207308519">(920) 730-8519</a></div>
		</header>
		<nav>
			<ul>
                <?php if ( is_home() ) { ?>
				<?php wp_nav_menu( array( 'theme_location' => 'home-menu' ) ); ?>
				<?php } else { ?>
                <?php wp_nav_menu( array( 'theme_location' => 'primary-menu' ) ); ?>
				<?php } ?>
			</ul>
		</nav>