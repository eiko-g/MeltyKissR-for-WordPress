<!doctype html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<meta name = "viewport" content ="initial-scale=1.0, maximum-scale=1, user-scalable=no, minimal-ui">
<?php if( is_single() || is_page() ) {
    if( function_exists('get_query_var') ) {
        $cpage = intval(get_query_var('cpage'));
        $commentPage = intval(get_query_var('comment-page'));
    }
    if( !empty($cpage) || !empty($commentPage) ) {
        echo '<meta name="robots" content="noindex, nofollow" />';
        echo "\n";
    }
}
//禁止搜索引擎收录评论分页
?>

<!--wp_head-->
<?php wp_head();?>
<!--end wp_head-->
</head>

<body <?php body_class(); ?>>

<header id="main-header" class="main-header">
  <div class="main-header-content clear">
    <?php if (!is_singular()) : ?>
      <h1 id="blog-title" class="blog-title">
        <a href="<?php bloginfo('url'); ?>">
          <?php bloginfo('name'); ?>
        </a>
      </h1>
    <?php else : ?>
      <a id="blog-title" class="blog-title" href="<?php bloginfo('url'); ?>">
        <?php bloginfo('name'); ?>
      </a>
    <?php endif; ?>

    <a id="switch-menu" href="#">
      + Menu
    </a>

    <nav id="main-nav" class="main-nav">
      <?php
      if ( !has_nav_menu( 'header-menu' ) ) :
        if ( is_user_logged_in() ) :
          echo '<ul class="header-menu"><li><a href="' . admin_url( 'nav-menus.php' ) . '">Add a menu</a></li></ul>';
        endif;
      else : ?>
        <ul class="header-menu">
          <?php wp_nav_menu( array(
            'container' => false,
            'items_wrap' => '%3$s',
            'menu' => 'header-menu'
          )); ?>
        </ul>
      <?php endif; ?>
    </nav>
  </div>
</header>