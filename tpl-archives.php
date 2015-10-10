<?php
  /**
    * 模板：归档页面
    * Template Name: 文章归档
    */
  get_header(); ?>

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
            zww_archives_list();

          endwhile;
          endif;
          ?>
        </div>
      </article>
    </div>
    <?php get_sidebar(); ?>
  </div>

<?php get_footer(); ?>
