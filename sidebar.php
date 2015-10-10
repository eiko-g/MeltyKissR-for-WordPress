<div class="sidebar-main">
  <?php if ( !is_active_sidebar( 'sidebar-top' ) ) : ?>
    <div id="sidebar-top" class="sidebar sidebar-top">
      <aside id="side-preset-top" class="widget">
        <h2 class="widget-title">文章归档</h2>
        <ul>
        <?php wp_get_archives('type=monthly&format=html&limit=10&show_post_count=true'); ?>
        </ul>

        <h2 class="widget-title">文章分类</h2>
        <ul>
        <?php wp_list_categories('title_li='); ?>
        </ul>
      </aside>
    </div>
  <?php else : ?>
    <div id="sidebar-top" class="sidebar sidebar-top">
      <?php dynamic_sidebar( 'sidebar-top' ); ?>
    </div>
  <?php endif; ?>

  <?php if ( !is_active_sidebar( 'sidebar-bottom' ) ) : ?>
    <div id="sidebar-bottom" class="sidebar sidebar-bottom">
      <aside id="side-preset-bottom" class="widget">
        <h2 class="widget-title">传送门</h2>
        <ul>
        <?php wp_register(); ?>
        <li><?php wp_loginout(); ?></li>
        <?php wp_meta(); ?>
        </ul>
      </aside>
    </div>
  <?php else : ?>
    <div id="sidebar-bottom" class="sidebar sidebar-bottom">
      <?php dynamic_sidebar( 'sidebar-bottom' ); ?>
    </div>
  <?php endif; ?>

  <div id="sidebar-search" class="sidebar sidebar-search">
    <aside id="search" class="widget widget_search">
      <form role="search" method="get" class="search-form" action="<?php bloginfo('url'); ?>">
        <label>
          <span class="screen-reader-text">搜索：</span>
          <input type="search" class="search-field" placeholder="Search and Enter..." value="" name="s" autocomplete="off">
        </label>
        <input type="submit" class="search-submit screen-reader-text" value="搜索">
      </form>
    </aside>
  </div>
</div>