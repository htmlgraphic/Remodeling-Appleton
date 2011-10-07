<?php get_header(); ?>

<section>
  <article id="post-<?php the_ID(); ?>">
    <header>
      <h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
      <?php /* ?><p>Posted on <?php the_time('F jS, Y'); ?> by <?php the_author(); ?></p><?php */ ?>
      <?php the_breadcrumb(); ?>
    </header>
  </article>
</section>

<?php

?>

<?php get_footer(); ?>
