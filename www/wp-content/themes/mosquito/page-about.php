<?php get_header(); ?>

<div id="page">
  <div id="title"><?php the_title(); ?></div>

  <?php the_breadcrumb(); ?>

  <script type="text/javascript">
    $(function() {
      $('#about-us-navigation-menu ul li a').click(function(event) {
        event.preventDefault();
        $('.about-subpage').hide();
        $('#about-subpage-' + $(this).attr('rel')).show();
      });
    });
  </script>

  <div id="about-us-navigation-menu" style="float:left;">
    <ul>
      <li><a href="#history" rel="history">History</a></li>
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
    <div id="about-subpage-<?php echo $about_page_name; ?>" class="about-subpage" style="display:none;float:left;">
      <?php
      $content = apply_filters('the_content', $page_data->post_content);
      $title = $page_data->post_title;
      ?>
      <?php echo $content; ?>
    </div>
    <?php
    ?>
  <?php endforeach; ?>
</div>

<?php get_footer(); ?>
