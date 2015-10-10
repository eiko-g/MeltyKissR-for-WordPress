<?php get_header(); ?>

  <div id="main" class="site-main" role="main">
    <div class="main-content">
      <header class="archive-header">
        <?php
          the_archive_title( '<h1 class="archive-title">', '</h1>' );
          the_archive_description( '<div class="taxonomy-description">', '</div>' );
        ?>
      </header>
      <?php
        if (have_posts()) :
          while (have_posts()) :
            the_post();
            get_template_part( 'content', get_post_format() );
          endwhile;
        the_posts_pagination( array(
          ' mid_size'          => '2',
          'prev_text'          => 'Previous page',
          'next_text'          => 'Next page',
          'before_page_number' => '<span class="meta-nav screen-reader-text">' .'Page' . ' </span>',
        ) );
        else :
          get_template_part( 'content', 'none' );
        endif;
      ?>
    </div>
    <?php get_sidebar(); ?>
  </div>

<?php get_footer(); ?>