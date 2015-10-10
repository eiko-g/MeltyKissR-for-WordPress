<?php get_header(); ?>

  <div id="main" class="site-main" role="main">
    <div class="main-content">
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="post-header">
          <h2 class="post-title">
            404 - Not Found
          </h2>
        </header>

        <div class="entry-content content-layout">
          <p>Maybe you get a wrong link, you can <a href="<?php bloginfo('url'); ?>">back to homepage</a>.</p>
        </div>

      </article>

    </div>
    <?php get_sidebar(); ?>
  </div>

<?php get_footer(); ?>