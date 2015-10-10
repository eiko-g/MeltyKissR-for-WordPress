<?php get_header(); ?>

  <div id="main" class="site-main" role="main">
    <div class="main-content">
      <?php
        if (have_posts()) :
          while (have_posts()) :
            the_post();
            get_template_part( 'content', get_post_format() );
          endwhile;

          if ( comments_open() || get_comments_number() ) :
            comments_template();
          endif;

        else :
          get_template_part( 'content', 'none' );
        endif;
      ?>
    </div>
    <?php get_sidebar(); ?>
  </div>

<?php get_footer(); ?>