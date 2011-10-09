<?php
/*
Template Name: Large Photo Layout
*/
?>

<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    
    <section id="general">
      <article id="post-<?php the_ID(); ?>">
        <header>
          <h1><?php the_title(); ?></h1>
          <?php /* ?><p>Posted on <?php the_time('F jS, Y'); ?> by <?php the_author(); ?></p><?php */ ?>
          <?php hg_bread_crumbs(); ?>  
        </header>
      <section>
    
    <div id="overview">
        <?php the_content('Read more on "'.the_title('', '', false).'" &raquo;'); ?> 
    </div>
    
    <div class="slideshow"><img src="http://placehold.it/500x300" /></div>
 
        </section>
        </article>
    </section>

<?php endwhile; endif; ?>

<?php get_footer(); ?>
