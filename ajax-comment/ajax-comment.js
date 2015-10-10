jQuery(document).ready(function(jQuery) {
  var $commentform = jQuery('#commentform'),
  $comments = jQuery('#comments-title'),
  $cancel = jQuery('#cancel-comment-reply-link'),
  cancel_text = $cancel.text();
  jQuery('#comment').after('<p id="comment_message" class="comment_message" style="display: none;"></p>');
  var message = jQuery('#comment_message');
  var submit_button = jQuery('#submit');
  jQuery(document).on("submit", "#commentform",
  function() {
    submit_button.attr('disabled',true).fadeTo(500,.5);
    message.slideDown(500).html("Submiting...");
    jQuery.ajax({
      url: ajaxcomment.ajax_url,
      data: jQuery(this).serialize() + "&action=ajax_comment",
      type: jQuery(this).attr('method'),
      error: function(request) {
        message.addClass('error').html(request.responseText);
        setTimeout(function() {
          message.slideUp(500);
          submit_button.attr('disabled',false).fadeTo(500,1);
          setTimeout(function() {
            message.removeClass('error');
          }, 500);
        }, 3000);
      },
      success: function(data) {
        jQuery('textarea').each(function() {
          this.value = ''
        });
        var t = addComment,
        cancel = t.I('cancel-comment-reply-link'),
        temp = t.I('wp-temp-form-div'),
        respond = t.I(t.respondId),
        post = t.I('comment_post_ID').value,
        parent = t.I('comment_parent').value;
        if (parent != '0') {
          jQuery('#respond').before('<ol class="children">' + data + '</ol>');
        } else if ( jQuery('.comment-list').length != '0') {
          jQuery('#respond').before('<ol class="comment-list">' + data + '</ol>');//comment-list is your comments wrapper,check your container ul or ol
        } else {
          var new_comment = jQuery('.comment-list').hide().append(data);
          new_comment.slideDown(1000); // your comments wrapper
        }
        message.addClass('success').html("Success");
        setTimeout(function() {
          message.slideUp(500);
          submit_button.attr('disabled',false).fadeTo(500,1);
          setTimeout(function() {
            message.removeClass('success');
          }, 500);
        }, 3000);

        cancel.style.display = 'none';
        cancel.onclick = null;
        t.I('comment_parent').value = '0';
        if (temp && respond) {
          temp.parentNode.insertBefore(respond, temp);
          temp.parentNode.removeChild(temp)
        }
      }
    });
    return false;
  });
  addComment = {
    moveForm: function(commId, parentId, respondId) {
      var t = this,
      div,
      comm = t.I(commId),
      respond = t.I(respondId),
      cancel = t.I('cancel-comment-reply-link'),
      parent = t.I('comment_parent'),
      post = t.I('comment_post_ID');
      $cancel.text(cancel_text);
      t.respondId = respondId;
      if (!t.I('wp-temp-form-div')) {
        div = document.createElement('div');
        div.id = 'wp-temp-form-div';
        div.style.display = 'none';
        respond.parentNode.insertBefore(div, respond)
      } ! comm ? (temp = t.I('wp-temp-form-div'), t.I('comment_parent').value = '0', temp.parentNode.insertBefore(respond, temp), temp.parentNode.removeChild(temp)) : comm.parentNode.insertBefore(respond, comm.nextSibling);
      jQuery("body").animate({
        scrollTop: jQuery('#respond').offset().top - 180
      },
      400);
      parent.value = parentId;
      cancel.style.display = '';
      cancel.onclick = function() {
        var t = addComment,
        temp = t.I('wp-temp-form-div'),
        respond = t.I(t.respondId);
        t.I('comment_parent').value = '0';
        if (temp && respond) {
          temp.parentNode.insertBefore(respond, temp);
          temp.parentNode.removeChild(temp);
        }
        this.style.display = 'none';
        this.onclick = null;
        return false;
      };
      try {
        t.I('comment').focus();
      }
       catch(e) {}
      return false;
    },
    I: function(e) {
      return document.getElementById(e);
    }
  };
});
