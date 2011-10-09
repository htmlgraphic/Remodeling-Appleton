<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>

		<!-- "H5": The HTML-5 WordPress Template Theme -->
		<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>">
		<title><?php bloginfo('name'); ?> <?php wp_title(); ?></title>
		<meta name="description" content="<?php bloginfo('description'); ?>">

		<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" media="screen">
		<link rel="alternate" type="text/xml" title="<?php bloginfo('name'); ?> RSS 0.92 Feed" href="<?php bloginfo('rss_url'); ?>">
<?php /*?>		
		<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>">
		<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS 2.0 Feed" href="<?php bloginfo('rss2_url'); ?>">
<?php */?>
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
        
        <script type="text/javascript" src="http://use.typekit.com/iby7rdb.js"></script>
		<script type="text/javascript">try{Typekit.load();}catch(e){}</script>

		<?php wp_enqueue_script('jquery'); ?>
		<?php wp_head(); ?>

		<script src="<?php bloginfo('template_directory'); ?>/javascript/h5.js"></script>
        
        <?php if (get_page($page_id)->ID == 14): // About Us Page ?>
		<script>
          $(function() {
			var elem = location.hash.replace('#','');
			
			if (elem) {
				$('#about-subpage-' + (elem)).show();
				$('a[rel="'+elem+'"]').addClass("sel");
			} else {
				// Default to the history page if nothing selected.
				$('#about-subpage-history').show();
				$('a[rel="history"]').addClass("sel");
			}
			
			$('a[rel]').click(function(event) {
			  var elem = $(this).attr('rel');
			  $("#about-us-navigation-menu ul li a").each(function(){
					$('ul li a[rel!="'+elem+'"]').removeClass("sel");	 
			  });
	 
	  			  $('ul li a[rel="'+elem+'"]').addClass("sel");			  
    	          $('.about-subpage').hide();
	       	      $('#about-subpage-' + $(this).attr('rel')).show();
				  event.preventDefault();
            });
          });
        </script>
        <?php endif; ?>
        
		<?php if ((get_page($page_id)->ID == 90) || (get_page($page_id)->ID == 94) || (get_page($page_id)->ID == 87)): // Kitchen Renovation Page & Interior Design ?>
        <script src="<?php bloginfo('stylesheet_directory'); ?>/js/facebox.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_directory'); ?>/facebox.css" />
        <script>
$(function(){
  $('a[rel*=facebox]').facebox()
});
  
(function () {
    $.fn.infiniteCarousel = function () {
        function repeat(str, n) {
            return new Array( n + 1 ).join(str);
        }
        
        return this.each(function () {
            // magic!
            var $wrapper = $('> div', this).css('overflow', 'hidden'),
                $slider = $wrapper.find('> ul').width(9999),
                $items = $slider.find('> li'),
                $single = $items.filter(':first')
                
                singleWidth = $single.outerWidth(),
                visible = Math.ceil($wrapper.innerWidth() / singleWidth),
                currentPage = 1,
                pages = Math.ceil($items.length / visible);
                
            /* TASKS */
            
            // 1. pad the pages with empty element if required
            if ($items.length % visible != 0) {
                // pad
                $slider.append(repeat('<li class="empty" />', visible - ($items.length % visible)));
                $items = $slider.find('> li');
            }
            
            // 2. create the carousel padding on left and right (cloned)
            $items.filter(':first').before($items.slice(-visible).clone().addClass('cloned'));
            $items.filter(':last').after($items.slice(0, visible).clone().addClass('cloned'));
            $items = $slider.find('> li');
            
            // 3. reset scroll
            $wrapper.scrollLeft(singleWidth * visible);
            
            // 4. paging function
            function gotoPage(page) {
                var dir = page < currentPage ? -1 : 1,
                    n = Math.abs(currentPage - page),
                    left = singleWidth * dir * visible * n;
                
                $wrapper.filter(':not(:animated)').animate({
                    scrollLeft : '+=' + left
                }, 500, function () {
                    // if page == last page - then reset position
                    if (page > pages) {
                        $wrapper.scrollLeft(singleWidth * visible);
                        page = 1;
                    } else if (page == 0) {
                        page = pages;
                        $wrapper.scrollLeft(singleWidth * visible * pages);
                    }
                    
                    currentPage = page;
                });
            }
            
            // 5. insert the back and forward link
            $wrapper.after('<a href="#" class="arrow back">&lt;</a><a href="#" class="arrow forward">&gt;</a>');
            
            // 6. bind the back and forward links
            $('a.back', this).click(function () {
                gotoPage(currentPage - 1);
                return false;
            });
            
            $('a.forward', this).click(function () {
                gotoPage(currentPage + 1);
                return false;
            });
            
            $(this).bind('goto', function (event, page) {
                gotoPage(page);
            });
            
            // THIS IS NEW CODE FOR THE AUTOMATIC INFINITE CAROUSEL
            $(this).bind('next', function () {
                gotoPage(currentPage + 1);
            });
        });
    };
})(jQuery);

$(document).ready(function () {
    // THIS IS NEW CODE FOR THE AUTOMATIC INFINITE CAROUSEL
    var autoscrolling = false;
    
    $('.infiniteCarousel').infiniteCarousel().mouseover(function () {
        autoscrolling = false;
    }).mouseout(function () {
        autoscrolling = true;
    });
    
    setInterval(function () {
        if (autoscrolling) {
           // $('.infiniteCarousel').trigger('next');
        }
    }, 4000);
});
</script>
        <?php endif; ?>

		<style type="text/css">
        <?php if (is_home()): // About Us Page ?>
           html{ background:#090502 url(/images/bg/home.jpg) top center no-repeat;}
        <?php elseif ((get_page($page_id)->ID == 76) || (get_page($page_id)->ID == 94)): // Interior Design ?>
           html{ background:#090502 url(/images/bg/interior.jpg) top center no-repeat;}
        <?php elseif (get_page($page_id)->ID == 90): // Kitchen Renovations ?>
           html{ background:#090502 url(/images/bg/kitchen.jpg) top center no-repeat;}
        <?php elseif (get_page($page_id)->ID == 87): // Bathroom Renovations ?>
           html{ background:#090502 url(/images/bg/bathroom.jpg) top center no-repeat;}
        <?php elseif (get_page($page_id)->ID == 14): // About ?>
           html{ background:#090502 url(/images/bg/about.jpg) top center no-repeat;}
        <?php elseif (get_page($page_id)->ID == 68): // Renovations Index ?>
           html{ background:#090502 url(/images/bg/renovations.jpg) top center no-repeat;}
        <?php else : // Renovations Index ?>
           html{ background:#090502;}
        <?php endif; ?>
        </style>
        

        
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