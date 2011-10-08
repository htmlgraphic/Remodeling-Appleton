<?php get_header(); ?>

<section id="about">
  <article id="post-<?php the_ID(); ?>">
    <header>
      <h1><?php the_title(); ?></h1>
      <?php /* ?><p>Posted on <?php the_time('F jS, Y'); ?> by <?php the_author(); ?></p><?php */ ?>
      <?php hg_bread_crumbs(); ?>              
    </header>
    <section>
      <div id="about-us-navigation-menu">
        <ul>
          <li><a href="#history" rel="history" class="sel">History</a></li>
          <li><a href="#awards" rel="awards">Awards</a></li>
          <li><a href="#accreditation" rel="accreditation">Accreditation</a></li>
          <li><a href="#certificates" rel="certificates">Certificates</a></li>
          <li><a href="#subcontractors" rel="subcontractors">Subcontractors</a></li>
          <li><a href="#testimonials" rel="testimonials">Testimonials</a></li>
          <li><a href="#contact" rel="contact">Contact</a></li>
        </ul>
      </div>
      <?php
      $about_page_ids = array(
          18 => 'history',
          20 => 'awards',
          22 => 'accreditation',
          24 => 'certificates',
          26 => 'subcontractors',
          28 => 'testimonials',
          30 => 'contact'
      );
      ?>

      <?php foreach ($about_page_ids as $about_page_id => $about_page_name): ?>
        <?php
        $page_data = get_page($about_page_id);
        ?>
        <div id="about-subpage-<?php echo $about_page_name; ?>" class="about-subpage">
          <?php
          $content = apply_filters('the_content', $page_data->post_content);
          $title = $page_data->post_title;
          ?>
          <?php echo $content; ?>
        </div>
        <?php
        ?>
      <?php endforeach; ?>
    </section>
  </article>
</section>

<?php get_footer(); ?>
