jQuery(document).ready( function($) {
/* - bof - */
/* Ajax 评论翻页 */
jQuery(document).on("click", ".comment-navigation a",
  function() {
    var baseUrl = jQuery(this).attr("href"),
    commentsHolder = jQuery("#comment-box"),
    commentsNavigation = jQuery(".comment-navigation"),
    id = jQuery(this).parent().data("post-id"),
    page = 1,
    concelLink = jQuery("#cancel-comment-reply-link");
    /comment-page-/i.test(baseUrl) ? page = baseUrl.split(/comment-page-/i)[1].split(/(\/|#|&).*jQuery/)[0] : /cpage=/i.test(baseUrl) && (page = baseUrl.split(/cpage=/)[1].split(/(\/|#|&).*jQuery/)[0]);
    concelLink.click();
    var ajax_data = {
      action: "ajax_comment_page_nav",
      um_post: id,
      um_page: page
    };
    //add loading
    commentsHolder.html('<div class="loading-comments"><i></i></div>');
    commentsNavigation.html('');
    jQuery.post(ajaxcomment.ajax_url, ajax_data,
    function(data) {
      commentsHolder.html(data);
      //remove loading
      jQuery("body, html").animate({
        scrollTop: commentsHolder.offset().top - 50
      },
      1e3)
    });
    return false;
  }
);

jQuery(document).on("click", "#switch-menu", function() {
  jQuery('body').toggleClass('nav-show');
  return false;
});

jQuery(document).on("click", "#blog-title, #main, #main-footer", function() {
  if ( jQuery('body').hasClass('nav-show') ) {
    jQuery('body').removeClass('nav-show');
    return false;
  };
});

//回到顶部
var bigfa_scroll = {
    drawCircle: function(id, percentage, color) {
        var width = jQuery(id).width();
        var height = jQuery(id).height();
        var radius = parseInt(width / 2.20);
        var position = width;
        var positionBy2 = position / 2;
        var bg = jQuery(id)[0];
        id = id.split("#");
        var ctx = bg.getContext("2d");
        var imd = null;
        var circ = Math.PI * 2;
        var quart = Math.PI / 2;
        ctx.clearRect(0, 0, width, height);
        ctx.beginPath();
        ctx.strokeStyle = color;
        ctx.lineCap = "square";
        ctx.closePath();
        ctx.fill();
        ctx.lineWidth = 3;
        imd = ctx.getImageData(0, 0, position, position);
        var draw = function(current, ctxPass) {
            ctxPass.putImageData(imd, 0, 0);
            ctxPass.beginPath();
            ctxPass.arc(positionBy2, positionBy2, radius, -(quart), ((circ) * current) - quart, false);
            ctxPass.stroke();
        }
        draw(percentage / 100, ctx);
    },
    backToTop: function($this) {
        $this.click(function() {
            jQuery("body,html").animate({
                scrollTop: 0
            },
            800);
            return false;
        });
    },
    scrollHook: function($this, color) {
        color = color ? color: "#AAB0C6";
        $this.scroll(function() {
            var docHeight = (jQuery(document).height() - jQuery(window).height()),
            $windowObj = $this,
            $per = jQuery(".per"),
            percentage = 0;
            defaultScroll = $windowObj.scrollTop();
            percentage = parseInt((defaultScroll / docHeight) * 100);
            var backToTop = jQuery("#backtoTop");
            if (backToTop.length > 0) {
                if ($windowObj.scrollTop() > 200) {
                    backToTop.addClass("button--show");
                } else {
                    backToTop.removeClass("button--show");
                }
                $per.attr("data-percent", percentage);
                bigfa_scroll.drawCircle("#backtoTopCanvas", percentage, color);
            }

        });
    }
}

jQuery(document).ready(function() {
    jQuery("body").append('<div id="backtoTop" data-action="gototop"><canvas id="backtoTopCanvas" width="48" height="48"></canvas><div class="per"></div></div>');
    var T = bigfa_scroll;
    T.backToTop(jQuery("#backtoTop"));
    T.scrollHook(jQuery(window), "#AAB0C6");
});

/* - eof - */});