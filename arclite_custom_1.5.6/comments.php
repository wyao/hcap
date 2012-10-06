<?php /* Arclite/digitalnature */ ?>
<?php if ( !empty($post->post_password) && $_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) : ?>
<p class="error"><?php _e('Enter your password to view comments','arclite'); ?></p>
<?php return; endif; ?>
<?php if ($comments || comments_open()) : ?>
<?php
  /* Count the totals */
  $numPingBacks = 0;
  $numComments  = 0;

  /* Loop throught comments to count these totals */
  foreach ($comments as $comment)
    if (get_comment_type() != "comment") $numPingBacks++; else $numComments++;
?>


    <!-- comments and trackback tabs -->
   <h3><?php _e('Comments','arclite'); echo ' ('.$numComments.')'; ?></h3>
   <!--<h3><?php _e('Trackbacks','arclite'); echo ' ('.$numPingBacks.')';?></h3>-->
    <!-- /comments and trackback tabs -->

  <!-- comments -->

   <ul id="comments">
    <?php
      if ($numComments > 0) {

      // for WordPress 2.7 or higher
  	if (function_exists('wp_list_comments')) { wp_list_comments('type=comment&callback=list_comments');	}
      else
        { // for WordPress 2.6.3 or lower
  	    foreach ($comments as $comment)
    		  if($comment->comment_type != 'pingback' && $comment->comment_type != 'trackback') list_comments($comment, null, null);
        }
  	}
      else { ?>
  	  <li><?php _e('No comments yet.','arclite'); ?></li>
  	<?php }	?>
    </ul>
            <div class="clear"></div>
    <?php if ($numPingbacks > 0) { ?>
    <!-- trackbacks -->
   <h3><?php _e('Trackbacks','arclite'); echo ' ('.$numPingBacks.')'; ?></h3>
    <ul id="trackbacks">
     <?php
      if ($numPingBacks > 0) wp_list_comments('type=pings&callback=list_pings'); else { ?>
       <li><?php _e("No trackbacks yet.","arclite"); ?></li>
     <?php } ?>
    </ul>
    <!-- /trackbacks -->
    <?php } ?>


    <?php
      if (get_option('page_comments')) {
       $comment_pages = paginate_comments_links('echo=0');
      if ($comment_pages) { ?>
       <div class="commentnavi">
  	    <div class="commentpager">
  	    	<?php echo $comment_pages; ?>
  	    </div>
       </div>
      <?php
  	  }
  	 }
      ?>
    <?php
    if (comments_open()) :
     if (get_option('comment_registration') && !$user_ID ) { // If registration required and not logged in. ?>
  	<div id="comment_login" class="messagebox">
  	  <?php if (function_exists('wp_login_url')) $login_link = wp_login_url(); else $login_link = get_option('siteurl') . '/wp-login.php?redirect_to=' . urlencode(get_permalink()); ?>
    	  <p><?php printf(__('You must be <a href="%s">logged in</a> to post a comment.', 'arclite'), $login_link); ?></p>
  	</div>

     <?php } else { ?>

      <div id="respond">
      <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform" name="tcommentform">
        <?php if (function_exists('cancel_comment_reply_link')) { ?><div class="cancel-comment-reply"><?php cancel_comment_reply_link(__('Cancel Reply','arclite')); ?></div><?php } ?>
        <?php if ($user_ID) : ?>
          <?php if (function_exists('wp_logout_url')) $logout_link = wp_logout_url(); else $logout_link = get_option('siteurl') . '/wp-login.php?action=logout';	?>
      	  <p>
           <?php
            $login_link = get_option('siteurl')."/wp-admin/profile.php";
            printf(__('Logged in as %s.', 'arclite'), '<a href="'.$login_link.'"><strong>'.$user_identity.'</strong></a>');
           ?>
           <a href="<?php echo $logout_link; ?>" title="<?php _e('Log out of this account', 'arclite'); ?>"><?php _e('Logout &raquo;', 'arclite'); ?></a>
          </p>
       	  <?php else : ?>
  	      <?php if ($comment_author != "") : ?>
  		  <p><?php printf(__('Welcome back <strong>%s</strong>.', 'arclite'), $comment_author) ?> <span id="show_author_info"><a href="javascript:void(0);" onclick="MGJS.setStyleDisplay('author_info','');MGJS.setStyleDisplay('show_author_info','none');MGJS.setStyleDisplay('hide_author_info','');"> <?php _e('Change &raquo;','arclite'); ?></a></span> <span id="hide_author_info"><a href="javascript:void(0);" onclick="MGJS.setStyleDisplay('author_info','none');MGJS.setStyleDisplay('show_author_info','');MGJS.setStyleDisplay('hide_author_info','none');"><?php _e('Close &raquo;','arclite'); ?></a></span></p>
          <?php endif; ?>
          <div id="author_info">
            <div class="row">
              <label for="author" class="small"><?php _e("Name","arclite"); ?> <?php if ($req) _e("(required)","arclite"); ?></label>
              <input type="text" name="author" id="author" class="textfield" value="<?php echo $comment_author; ?>" size="24" tabindex="1" />
            </div>
            <div class="row">
              <label for="email" class="small"><?php _e("E-Mail","arclite"); ?> <?php if ($req) _e("(required)","arclite"); ?> <em><?php _e("(will not be published)","arclite"); ?></em></label>
              <input type="text" name="email" id="email" class="textfield" value="<?php echo $comment_author_email; ?>" size="24" tabindex="2" />
            </div>
            <div class="row">
              <label for="url" class="small"><?php _e("Website","arclite"); ?></label>
              <input type="text" name="url" id="url" class="textfield" value="<?php echo $comment_author_url; ?>" size="24" tabindex="3" />
            </div>
  		  </div>
          <?php if ( $comment_author != "" ) : ?>
  	   	  <script type="text/javascript">MGJS.setStyleDisplay('hide_author_info','none');MGJS.setStyleDisplay('author_info','none');</script>
  	  	  <?php endif; ?>
        <?php endif; ?>

        <!-- comment input -->
        <div class="row">
        	<textarea name="comment" id="comment" tabindex="4" rows="8" cols="50"></textarea>
        	<?php if (function_exists('highslide_emoticons')) : ?><div id="emoticon"><?php highslide_emoticons(); ?></div><?php endif; ?>
        	<?php if (function_exists('comment_id_fields')) : comment_id_fields(); endif; do_action('comment_form', $post->ID); ?>
        </div>
        <!-- /comment input -->

        <!-- comment submit and rss -->
        <div id="submitbox" class="left">
		<input name="submit" type="submit" id="submit" class="button" tabindex="5" value="<?php _e('Submit Comment', 'arclite'); ?>" />

         <input type="hidden" name="formInput" />
        </div>
      </form>
    </div>
    <?php } ?>
    <?php endif;  ?>

  <!-- /comments -->

<?php endif; ?>

<?php if (!comments_open()): // If comments are closed. ?>
 <?php if (is_page() && (!$comments)):
  else: ?>
 <p><?php _e("Comments are closed.","arclite"); ?></p>
 <?php endif; ?>
<?php endif; ?>