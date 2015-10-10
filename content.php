<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <header class="post-header">
    <?php if ( has_post_thumbnail() ) : ?>
      <div class="thumbnail-img">
        <?php the_post_thumbnail('thumbnail-img');  ?>
      </div>
    <?php endif; ?>

    <?php if ( !is_singular() ) : ?>
      <h2 class="post-title">
        <a href="<?php the_permalink() ?>" title="查看《<?php the_title(); ?>》的全文" rel="bookmark">
          <?php the_title(); ?>
        </a>
      </h2>
    <?php else : ?>
      <h1 class="post-title">
          <?php the_title(); ?>
      </h1>
    <?php endif; ?>
  </header>

  <div class="entry-content content-layout">
    <?php
      the_content('');

      if ( is_single() ) :
      wp_link_pages( array(
        'before'      => '<div class="page-links"><span class="page-links-title">' . ( '分页：' ) . '</span>',
        'after'       => '</div>',
        'link_before' => '<span class="page-links-number">',
        'link_after'  => '</span>',
        'pagelink'    => '<span class="screen-reader-text">' . ( 'Page' ) . ' </span>%',
        'separator'   => '<span class="screen-reader-text">, </span>',
      ) );
      endif;
    ?>
  </div>

  <footer class="post-footer clear">
    <div class="post-meta left">
      <?php the_time('Y.m.d'); ?>
       &#47;
       <?php the_category(', ') ?>
       <?php edit_post_link('[编辑]', ' &bull; ', ''); ?>
    </div>
    <div class="post-meta right">
      <?php if ( is_sticky() ) : ?>
        <span class="toptip">【置顶】</span> &bull;
      <?php endif; ?>
      <?php comments_popup_link('无回应', '1 回应', '% 回应'); ?>
    </div>
  </footer>

</article>
