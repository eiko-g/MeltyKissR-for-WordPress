<?php get_header(); ?>

  <div id="main" class="site-main" role="main">
    <div class="main-content">
      <?php
        if (have_posts()) :
          while (have_posts()) :
            the_post();
      ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="post-header">
          <h1 class="post-title">
              <?php the_title(); ?>
          </h1>
        </header>

        <div class="entry-content content-layout">
          <?php
            the_content('');

            if ( is_single() ) :
            wp_link_pages( array(
              'before'      => '<div class="page-links"><span class="page-links-title">' . ( '分页：' ) . '</      span>',
              'after'       => '</div>',
              'link_before' => '<span class="page-links-number">',
              'link_after'  => '</span>',
              'pagelink'    => '<span class="screen-reader-text">' . ( 'Page' ) . ' </span>%',
              'separator'   => '<span class="screen-reader-text">, </span>',
            ) );
            endif;
          endwhile;
          ?>
        </div>

        <footer class="post-footer clear">
          <div class="post-meta left">
            <?php the_time('Y.m.d'); ?>
             <?php edit_post_link('[编辑]', ' &bull; ', ''); ?>
          </div>
          <div class="post-meta right">
            <?php comments_popup_link('无回应', '1 回应', '% 回应'); ?>
          </div>
        </footer>

</article>

      <?php
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