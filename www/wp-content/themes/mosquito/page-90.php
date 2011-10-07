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

<style>
  #slider {
    position: relative;
    float: left;
  }
  .buttons {
    cursor: pointer;
    float: left;
    position: absolute;
    z-index: 100;
    left: 0px;
    top: 5px;
  }
  .buttons.left {
    margin-left: -20px;
  }
  .buttons.right {
    margin-left: 475px;
  }
  #timeline {
    height: 88px;
    width: 438px;
    overflow: auto;
    position: relative;
    float: left;
    margin-left: 25px;
  }
  #scrollCon {
  }
</style>

<script>
  $(function(){
    $('#slider .flickr-thumb img').flightbox({size_callback: get_sizes});
  });
  
  $(function() {
    var $panels = $('#slider .stage');
    var $container = $('#slider #scrollCon');
    var $scroll = $('#slider #timeline').css('overflow', 'hidden');

    // if false, we'll float all the panels left and fix the width 
    // of the container
    var horizontal = true;

    // float the panels left if we're going horizontal
    if (horizontal) {
      // calculate a new width for the container (so it holds all panels)
      var panel_width = ($panels.width() + parseInt($panels.css('margin-left')) + parseInt($panels.css('margin-right')) + parseInt($panels.css('padding-left')) + parseInt($panels.css('padding-right')));
      $container.css('width', panel_width * $panels.length + 450);
    }

    //alert($.browser.browser() + $.browser.version.string());
    // apply our left + right buttons
    $scroll
    .before('<a class="buttons left" href="javascript:void(0);">Back</a>')
    .after('<a class="buttons right" href="javascript:void(0);">Next</a>');

    function selectNav() {
      $(this)
      .parents('ul:first') // find the first UL parent
      .find('a') // find all the A elements
      .removeClass('sel') // remove from all
      .end() // go back to all A elements
      .end() // go back to 'this' element
      .addClass('sel');
    }

    $('#slider .navigation').find('a').click(selectNav);

		
    function trigger(data) {
      // within the .navigation element, find the A element
      // whose href ends with ID ($= is ends with)
      var el = $('#slider .navigation').find('a[href$="' + data.id + '"]').get(0);
  
      // we're passing the actual element, and not the jQuery instance.
      selectNav.call(el);
    }

    if (window.location.hash) {
      trigger({ id : window.location.hash.substr(1)});
    } else {
      $('#slider .navigation a:first').click();
    }

    // offset is used to move to *exactly* the right place, since I'm using
    // padding on my example, I need to subtract the amount of padding to
    // the offset.  Try removing this to get a good idea of the effect
    var offset = parseInt((horizontal ? 
      $container.css('paddingTop') : 
      $container.css('paddingLeft')) 
      || 0) * -1;
 
    var scrollOptions = {
      target: $scroll,
      items: $panels,
      navigation: '.navigation a',
      prev: 'a.left', 
      next: 'a.right',
      axis: 'xy',
      onAfter: trigger,
      offset: offset,
      duration: 500,
      easing: 'swing'
      //hash:true //adds the hash to the url but drop the page to meet the ID.
    };

    // apply serialScroll to the slider - we chose this plugin because it 
    // supports// the indexed next and previous scroll along with hooking 
    // in to our navigation.
    $('#slider').serialScroll(scrollOptions);

    // now apply localScroll to hook any other arbitrary links to trigger 
    // the effect
    $.localScroll(scrollOptions);

	
    // finally, if the URL has a hash, move the slider in to position, 
    // setting the duration to 1 because I don't want it to scroll in the
    // very first page load.  We don't always need this, but it ensures
    // the positioning is absolutely spot on when the pages loads.
    scrollOptions.duration = 1;
    $.localScroll.hash(scrollOptions);	
  });
</script>

<?php
$flickr_photoset_id = '72157626951352847';
$flickr_api_key = '2d589541fd6f617409903e97d0e10bbe';
$flickr_username = 'remodelingappleton';
$flickr_gallery = new phpFlickr($flickr_api_key);
$flickr_gallery->enableCache('fs', 'cache');
$flickr_gallery_people = $flickr_gallery->people_findByUsername($flickr_username);
$flickr_gallery_user_id = $flickr_gallery_people['id'];
$flickr_gallery_photos = $flickr_gallery->photosets_getPhotos($flickr_photoset_id, null, null);
?>

<div id="slider" class="flickr-gallery">
  <div class="stage_nav"></div>
  <div id="timeline" style="overflow-x:hidden;overflow-y:hidden;">
    <div id="scrollCon" style="width:2660px;">
      <?php foreach ($flickr_gallery_photos['photoset']['photo'] as $photo): ?>
        <div class="stage flickr-thumb">
          <a href="<?php echo $flickr_gallery->buildPhotoURL($photo, 'Large'); ?>" title="<?php echo $photo['title']; ?>">
            <img class="photo" src="<?php echo $flickr_gallery->buildPhotoURL($photo, 'Square'); ?>" width="75" height="75" />
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<?php get_footer(); ?>
