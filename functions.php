<?php
/*
  各种 Theme support
  https://codex.wordpress.org/Function_Reference/add_theme_support
*/
add_theme_support( 'title-tag' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'automatic-feed-links' );
add_theme_support( 'html5', array(
  'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
) );

add_image_size('thumbnail-img', 1000, 9999);

/*
  注册菜单
*/
register_nav_menus(array('header-menu' => '主菜单'));

/*
  注册边栏
*/
function theme_widgets_init() {
  register_sidebar( array(
    'name'          => '边栏-上半部分',
    'id'            => 'sidebar-top',
    'description'   => '上半部分的边栏。',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget'  => '</aside>',
    'before_title'  => '<h2 class="widget-title">',
    'after_title'   => '</h2>',
  ) );
  register_sidebar( array(
    'name'          => '边栏-下半部分',
    'id'            => 'sidebar-bottom',
    'description'   => '下半部分的边栏。',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget'  => '</aside>',
    'before_title'  => '<h2 class="widget-title">',
    'after_title'   => '</h2>',
  ) );
}
add_action( 'widgets_init', 'theme_widgets_init' );

/*
  注册各种
*/
function eiko_scripts() {
  wp_enqueue_style( 'normalize', get_template_directory_uri() . '/normalize.css' );
  wp_enqueue_style( 'eiko_style', get_stylesheet_uri() );
  wp_register_script( 'ajax_url_all', get_template_directory_uri() . '/script/script.js',array( 'jquery' ) );
  wp_localize_script( 'ajax_url_all', 'ajaxurl', array(
        'ajax_url'   => admin_url('admin-ajax.php')
    ) );
  wp_enqueue_script( 'ajax_url_all' );
}
add_action( 'wp_enqueue_scripts', 'eiko_scripts' );

/*
  禁用 WordPress 自带 Emoji
  http://fatesinger.com/75634
*/
/**
* Disable the emoji's
*/
if (!function_exists('disable_emojis')) {
  function disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
  }
  function disable_emojis_tinymce( $plugins ) {
    return array_diff( $plugins, array( 'wpemoji' ) );
  }
  add_action( 'init', 'disable_emojis' );
}


/*
  Gravatar 头像地址替换
  http://fatesinger.com/74030
*/
function get_ssl_avatar($avatar) {
  $avatar = str_replace(array("http://www.gravatar.com", "http://0.gravatar.com", "http://1.gravatar.com", "http://2.gravatar.com"), "https://cn.gravatar.com", $avatar);
  return $avatar;
}
add_filter('get_avatar', 'get_ssl_avatar');

/*
  只搜索文章，排除页面
*/
function search_filter($query) {
  if ($query->is_search) {$query->set('post_type', 'post');}
  return $query;
}
add_filter('pre_get_posts','search_filter');

/*
  去除链接的版本号
*/
if(!function_exists('cwp_remove_script_version')){
  function cwp_remove_script_version( $src ){  return remove_query_arg( 'ver', $src ); }
  add_filter( 'script_loader_src', 'cwp_remove_script_version' );
  add_filter( 'style_loader_src', 'cwp_remove_script_version' );
}

/* Archives list v2014 by zwwooooo | http://zww.me */
function zww_archives_list() {
  if( !$output = get_option('zww_db_cache_archives_list') ){
    $output = '<div id="archives">';
    $args = array(
      'post_type' => 'post', //如果你有多个 post type，可以这样 array('post', 'product', 'news')
      'posts_per_page' => -1, //全部 posts
      'ignore_sticky_posts' => 1 //忽略 sticky posts

    );
    $the_query = new WP_Query( $args );
    $posts_rebuild = array();
    $year = $mon = 0;
    while ( $the_query->have_posts() ) : $the_query->the_post();
      $post_year = get_the_time('Y');
      $post_mon = get_the_time('m');
      $post_day = get_the_time('d');
      if ($year != $post_year) $year = $post_year;
      if ($mon != $post_mon) $mon = $post_mon;
      $posts_rebuild[$year][$mon][] = '<li><h3 class="al_title"><a href="'. get_permalink() .'"><span class="date">' .get_the_time('Y.m.d: ') . get_the_title() .'</span></a></h3><p class="last-edit">最后编辑: ' . get_the_modified_date('Y.m.d') . '</p></li>';
    endwhile;
    wp_reset_postdata();

    foreach ($posts_rebuild as $key_y => $y) {
      $output .= '<h2 class="al_year">'. $key_y .'</h2><ul class="al_mon_list">'; //输出年份
      foreach ($y as $key_m => $m) {
        $posts = ''; $i = 0;
        foreach ($m as $p) {
          ++$i;
          $posts .= $p;
        }
        $output .= $posts; //输出 posts
      }
      $output .= '</ul>';
    }

    $output .= '</div>';
    update_option('zww_db_cache_archives_list', $output);
  }
  echo $output;
}
function clear_db_cache_archives_list() {
  update_option('zww_db_cache_archives_list', ''); // 清空 zww_archives_list
}
add_action('save_post', 'clear_db_cache_archives_list'); // 新发表文章/修改文章时

/* Ajax 评论分页 */
add_action('wp_ajax_nopriv_ajax_comment_page_nav', 'ajax_comment_page_nav');
add_action('wp_ajax_ajax_comment_page_nav', 'ajax_comment_page_nav');
function ajax_comment_page_nav(){
    global $post,$wp_query, $wp_rewrite;
    $postid = $_POST["um_post"];
    $pageid = $_POST["um_page"];
    $comments = get_comments('post_id='.$postid);
    $post = get_post($postid);
    if( 'desc' != get_option('comment_order') ){
        $comments = array_reverse($comments);
    }
    $wp_query->is_singular = true;
    $baseLink = '';
    if ($wp_rewrite->using_permalinks()) {
        $baseLink = '&base=' . user_trailingslashit(get_permalink($postid) . 'comment-page-%#%', 'commentpaged');
    }
    echo '<ol class="comment-list">';
    wp_list_comments('style=ol&type=all&callback=themecomment&max_depth=500&page=' . $pageid . '&per_page=' . get_option('comments_per_page'), $comments);//注意修改mycomment这个callback
    echo '</ol>';
    echo '<nav class="navigation comment-navigation pagination" data-post-id='.$postid.'">';
    paginate_comments_links('current=' . $pageid . '&prev_next=0');
    echo '</nav>';
    die;
}
// 评论回复构架
require get_template_directory() . '/ajax-comment/do.php';

function themecomment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment;
    global $commentcount;
    if(!$commentcount) {
       $page = ( !empty($in_comment_loop) ) ? get_query_var('cpage')-1 : get_page_of_comment( $comment->comment_ID, $args )-1;
       $cpp = get_option('comments_per_page');
       $commentcount = $cpp * $page;
    }
    /* 区分普通评论和Pingback */
    switch ($pingtype=$comment->comment_type) {
    case 'pingback' : /* 标识Pingback */
    case 'trackback' : /* 标识Trackback */

?>

<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
  <div id="comment-<?php comment_ID(); ?>">
    <div class="comment-author vcard pingback">
      <span class="fn pingback"><?php comment_date('Y-m-d') ?> &raquo; <?php comment_author_link(); ?></span>
    </div>
  </div>

  <?php
    break;
    /* 标识完毕 */
    default : /* 普通评论部分 */
    if(!$comment->comment_parent){ ?>

<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">

  <article id="comment-<?php comment_ID(); ?>" class="comment-body" data-floor="<?php printf('%1$s L', ++$commentcount); ?>">
    <footer class="comment-meta">
      <div class="commenet-author vcard">
        <?php echo get_avatar($comment, $size = '44') ?>
        <?php printf( ( '<b class="fn">%s</b><span class="says"> 说：</span>'), get_comment_author_link() ); ?>
      </div>
      <div class="comment-metadata">
        <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>">
          <time datetime="<?php comment_time('c') ?>"><?php comment_date('Y-m-d') ?> <?php comment_time('H:i') ?></time>
        </a>
      </div>
    </footer>

    <div class="comment-content">
      <?php if ( $comment->comment_approved == '0' ) : ?>
        <p style="color:#C00;">您的评论正在等待审核中。</p>
      <?php endif; ?>
      <?php comment_text(); ?>
    </div>
    <div class="reply">
      <?php comment_reply_link(
          array(
            'depth' => $depth,
            'max_depth' => $args['max_depth'],
            'reply_text' => __('Reply'),
            )
        ); ?>
    </div>

  </article>

<?php }else{?>

<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">

  <article id="comment-<?php comment_ID(); ?>" class="comment-body comment-children-body" data-floor="<?php if( $depth > 1){printf('B%1$s', $depth-1);} ?>">
    <footer class="comment-meta">
      <div class="commenet-author vcard">
        <?php echo get_avatar($comment, $size = '44') ?>
        <?php $parent_id = $comment->comment_parent; $comment_parent = get_comment($parent_id); printf('<b class="fn">%s</b>', get_comment_author_link()) ?> 回复 <a href="<?php echo "#comment-".$parent_id;?>"><b class="fn"><?php echo $comment_parent->comment_author;?></b></a><span class="says">:</span>
      </div>
      <div class="comment-metadata">
        <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>">
          <time datetime="<?php comment_time('c') ?>"><?php comment_date('Y-m-d') ?> <?php comment_time('H:i') ?></time>
        </a>
      </div>
    </footer>

    <div class="comment-content">
      <?php if ( $comment->comment_approved == '0' ) : ?>
        <p style="color:#C00;">您的评论正在等待审核中。</p>
      <?php endif; ?>
      <?php comment_text(); ?>
    </div>

    <div class="reply">
      <?php comment_reply_link(
          array(
            'depth' => $depth,
            'max_depth' => $args['max_depth'],
            'reply_text' => __('Reply'),
            )
        ); ?>
    </div>

  </article>


<?php }
break; /* 普通评论标识完毕 */
  }
}

//移除评论信息中的网站地址
function remove_comment_fields($fields) {
    unset($fields['url']);
    return $fields;
}
add_filter('comment_form_default_fields','remove_comment_fields');

//去除评论中的链接
remove_filter('comment_text', 'make_clickable', 9);

add_filter('comment_text', 'auto_nofollow');

//给评论中的链接自动加上nofollow
function auto_nofollow($content) {
    //return stripslashes(wp_rel_nofollow($content));

    return preg_replace_callback('/<a>]+/', 'auto_nofollow_callback', $content);
}

function auto_nofollow_callback($matches) {
    $link = $matches[0];
    $site_link = get_bloginfo('url');

    if (strpos($link, 'rel') === false) {
        $link = preg_replace("%(href=S(?!$site_link))%i", 'rel="nofollow" $1', $link);
    } elseif (preg_match("%href=S(?!$site_link)%i", $link)) {
        $link = preg_replace('/rel=S(?!nofollow)S*/i', 'rel="nofollow"', $link);
    }
    return $link;
}

// 反全英文垃圾评论
  function scp_comment_post( $incoming_comment ) {
    $pattern = '/[一-龥]/u';
    
    if(!preg_match($pattern, $incoming_comment['comment_content'])) {
      ajax_comment_err( "You should type some Chinese word (like \"你好\") in your comment to pass the spam-check, thanks for your patience! 您的评论中必须包含汉字!" );
    }
    return( $incoming_comment );
  }
  add_filter('preprocess_comment', 'scp_comment_post');
  
  /**
   * when comment check the comment_author comment_author_email
   * @param unknown_type $comment_author
   * @param unknown_type $comment_author_email
   * @return unknown_type
   *防止访客冒充博主发表评论 by Winy
   */
  function CheckEmailAndName(){
    global $wpdb;
    $comment_author       = ( isset($_POST['author']) )  ? trim(strip_tags($_POST['author'])) : null;
    $comment_author_email = ( isset($_POST['email']) )   ? trim($_POST['email']) : null;
    if(!$comment_author || !$comment_author_email){
      return;
    }
    $result_set = $wpdb->get_results("SELECT display_name, user_email FROM $wpdb->users WHERE display_name = '" . $comment_author . "' OR user_email = '" . $comment_author_email . "'");
    if ($result_set) {
      if ($result_set[0]->display_name == $comment_author){
        ajax_comment_err(__('You CANNOT use this name.'));//昵称
      }else{
        ajax_comment_err(__('You CANNOT use this email.'));//邮箱
      }
      fail($errorMessage);
    }
  }
  add_action('pre_comment_on_post', 'CheckEmailAndName');

/* -----------------------------------------------
<;<小牆>> Anti-Spam v1.9 by Willin Kan.
*/
//建立
 class anti_spam {
   function anti_spam() {
     if ( !is_user_logged_in() ) {
       add_action('template_redirect', array($this, 'w_tb'), 1);
       add_action('pre_comment_on_post', array($this, 'gate'), 1);
       add_action('preprocess_comment', array($this, 'sink'), 1);
     }
   }
   //設欄位
   function w_tb() {
     if ( is_singular() ) {
       ob_start(create_function('$input', 'return preg_replace("#textarea(.*?)name=([\"\'])comment([\"\'])(.+)/textarea>#",
     "textarea$1name=$2w$3$4/textarea><textarea name=\"comment\" cols=\"60\" rows=\"4\" style=\"display:none\"></textarea>", $input);') );
      }
   }
   //檢查
function gate() {
    if ( !empty($_POST['w']) && empty($_POST['comment']) ) {
      $_POST['comment'] = $_POST['w'];
    } else {
      $request = $_SERVER['REQUEST_URI'];
      $referer = isset($_SERVER['HTTP_REFERER'])         ? $_SERVER['HTTP_REFERER']         : '隐瞒';
      $IP      = isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] . ' (透过代理)' : $_SERVER["REMOTE_ADDR"];
      $way     = isset($_POST['w'])                      ? '手动操作'                       : '未经评论表格';
      $spamcom = isset($_POST['comment'])                ? $_POST['comment']                : null;
      $_POST['spam_confirmed'] = "请求: ". $request. "\n来路: ". $referer. "\nIP: ". $IP. "\n方式: ". $way. "\n內容: ". $spamcom. "\n -- 记录成功 --";
    }
  }
   //處理
   function sink( $comment ) {
     if ( !empty($_POST['spam_confirmed']) ) {
       //方法一:直接擋掉, 將 die(); 前面兩斜線刪除即可.
       //die();
       //方法二:標記為spam, 留在資料庫檢查是否誤判.
       add_filter('pre_comment_approved', create_function('', 'return "spam";'));
       $comment['comment_content'] = "[ 小墙判断这是 Spam! ]\n". $_POST['spam_confirmed'];
     }
     return $comment;
   } 
}
$anti_spam = new anti_spam();

// -- END ----------------------------------------

/*
  去除链接的版本号
*/
if(!function_exists('cwp_remove_script_version')){
    function cwp_remove_script_version( $src ){  return remove_query_arg( 'ver', $src ); }
    add_filter( 'script_loader_src', 'cwp_remove_script_version' );
    add_filter( 'style_loader_src', 'cwp_remove_script_version' );
}

/*
  干掉 WordPress 登陆错误提示
*/
add_filter('login_errors',create_function('$a', "return null;"));