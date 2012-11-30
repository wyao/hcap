<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php /* Arclite/digitalnature */
  if(get_option('arclite_meta')<>'') {
   if (is_home()) {
  	$metakeywords = get_option('arclite_meta');
   } else if (is_single()) {
  	$metakeywords = "";
  	$tags = wp_get_post_tags($post->ID);
  	foreach ($tags as $tag ) {
  	  $metakeywords = $metakeywords . $tag->name . ", ";
  	}
   }
  }
?>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<?php if($metakeywords<>'') { ?>
<meta name="keywords" content="<?php print $metakeywords; ?>" />
<meta name="description" content="<?php bloginfo('description'); ?>" />
<?php } ?>

<title><?php wp_title('&laquo;', true, 'right'); ?><?php bloginfo('name'); ?></title>

<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/favicon.ico" />

<?php if(get_option('arclite_imageless')=='yes') { ?>
<style type="text/css" media="all">@import "<?php bloginfo('template_url'); ?>/style-imageless.css";</style>
<?php } else { ?>
<style type="text/css" media="all">@import "<?php bloginfo('stylesheet_url'); ?>";</style>
<?php } ?>

<!--[if lte IE 6]>
<style type="text/css" media="screen">
  @import "<?php bloginfo('template_url'); ?>/ie6.css";
</style>
<![endif]-->

<?php if(get_option('arclite_imageless')<>'yes') { ?>
 <?php if(get_option('arclite_header')=='user') { ?>
   <style type="text/css" media="all">
    <?php if(get_option('arclite_headerimage')<>'') { ?>#header{ background: transparent url(<?php print get_option('arclite_headerimage'); ?>) no-repeat center top; }<?php } ?>
    <?php if(get_option('arclite_headerimage2')<>'') { ?>#header-wrap{ background: transparent url(<?php print get_option('arclite_headerimage2'); ?>) repeat center top; }<?php } ?>
   </style>
 <?php } else if((get_option('arclite_header')=='default') || (get_option('arclite_header')=='')) { ?>
  <style type="text/css" media="all">@import "<?php bloginfo('template_url'); ?>/options/header-default.css";</style>
 <?php } else { ?>
  <style type="text/css" media="all">@import "<?php bloginfo('template_url'); ?>/options/header-<?php print get_option('arclite_header'); ?>.css";</style>
 <?php } ?>

 <?php if(get_option('arclite_widgetbg')<>'') { ?>
  <style type="text/css" media="all">@import "<?php bloginfo('template_url'); ?>/options/side-<?php print get_option('arclite_widgetbg'); ?>.css";</style>
 <?php } ?>

 <?php if(get_option('arclite_contentbg')<>'') { ?>
  <style type="text/css" media="all">@import "<?php bloginfo('template_url'); ?>/options/content-<?php print get_option('arclite_contentbg'); ?>.css";</style>
 <?php } else { ?>
  <style type="text/css" media="all">@import "<?php bloginfo('template_url'); ?>/options/content-default.css";</style>
 <?php } ?>

<?php } ?>
<?php if(get_option('arclite_header')=='user2') { ?>
   <style type="text/css" media="all">
    #header, #header-wrap{ background: #<?php print get_option('arclite_headercolor'); ?>; }
   </style>
<?php } ?>

<?php if(get_option('arclite_sidebarpos')=='left') { ?><style type="text/css" media="all">@import "<?php bloginfo('template_url'); ?>/options/leftsidebar.css";</style><?php } ?>

<?php // custom css?
  $usercss = get_option('arclite_css');
  if($usercss<>'') { ?>
<style type="text/css" media="screen">
  <?php echo $usercss; ?>
</style>
<?php } ?>

<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php if(get_option('arclite_jquery')<>'no') { ?>
 <?php wp_enqueue_script('jquery'); ?>
 <?php wp_enqueue_script('arclite',get_bloginfo('template_url').'/js/arclite.js'); ?>
<?php } ?>

<?php wp_head(); ?>

<link href="<?php bloginfo('template_url'); ?>/willie.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/willie.js"></script>	

</head>
<body <?php if (is_home()) { ?>class="home"<?php } else { ?>class="<?php echo $post->post_name; ?>"<?php } ?>>

<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    // init the FB JS SDK
    FB.init({
      appId      : '435704813143438', // App ID from the App Dashboard
      channelUrl : '//WWW.harvardcollegeinasia.org/channel.html', // Channel File for x-domain communication
      status     : true, // check the login status upon init?
      cookie     : true, // set sessions cookies to allow your server to access the session?
      xfbml      : true  // parse XFBML tags on this page?
    });

    // Additional initialization code such as adding Event Listeners goes here

  };

  // Load the SDK's source Asynchronously
  (function(d){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     ref.parentNode.insertBefore(js, ref);
   }(document));
</script>

 <!-- page wrap -->
 <div id="page"<?php if(!is_page_template('page-nosidebar.php')) { ?> class="with-sidebar"<?php } ?>>

  <!-- header -->
  <div id="header-wrap">
   <div id="header" class="block-content">
     <div id="pagetitle">


      <?php
      // logo image?
      if(get_option('arclite_logo')=='yes' && get_option('arclite_logoimage')) { ?>
      <h1 class="logo"><a href="<?php bloginfo('url'); ?>/"><img src="<?php print get_option('arclite_logoimage'); ?>" title="<?php bloginfo('name');  ?>" alt="<?php bloginfo('name');  ?>" /></a></h1>
      <?php } else { ?>
	 <h1 class="logo"><a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?>
</a></h1>
      <?php }  ?>

      <?php if(get_bloginfo('description')<>'') { ?><h4><?php bloginfo('description'); ?></h4><?php } ?>
      <div class="clear"></div>

      <?php if(get_option('arclite_search')<>'no') { ?>
      <?php // get_search_form(); ?>

      <div class = "img-nav">
	 <div class="landmark"><a href="http://www.harvardcollegeinasia.org/partners/istanbul/"><img id="istanbul" src="<?php bloginfo('template_url'); ?>/images/istanbul_black.png"></a></div>
	 <div class="landmark"><a href="http://www.harvardcollegeinasia.org/partners/dubai/"><img id="dubai" src="<?php bloginfo('template_url'); ?>/images/dubai_black.png"></a></div>
	 <div class="landmark"><a href="http://www.harvardcollegeinasia.org/partners/mumbai/"><img id="mumbai" src="<?php bloginfo('template_url'); ?>/images/mumbai_black.png"></a></div>
	 <div class="landmark"><a href="http://www.harvardcollegeinasia.org/partners/hong-kong/"><img id="kong" src="<?php bloginfo('template_url'); ?>/images/kong_black.png"></a></div>
	 <div class="landmark"><a href="http://www.harvardcollegeinasia.org/partners/seoul/"><img id="seoul" src="<?php bloginfo('template_url'); ?>/images/seoul_black.png"></a></div>
	 <div class="landmark"><a href="http://www.harvardcollegeinasia.org/partners/tokyo/"><img id="tokyo" src="<?php bloginfo('template_url'); ?>/images/tokyo_black.png"></a></div>
	 <div class="landmark"><a href="http://www.harvardcollegeinasia.org/"><img id="harvard" src="<?php bloginfo('template_url'); ?>/images/harvard_black.png"></a></div>
	 <div class="img-info" id="harvard_i"><h3>Cambridge</h3>Harvard University</div>
	 <div class="img-info" id="dubai_i"><h3>Dubai</h3>American University in Dubai</div>
	 <div class="img-info" id="istanbul_i"><h3>Istanbul</h3>Boğaziçi University</div>
	 <div class="img-info" id="mumbai_i"><h3>Mumbai</h3>St Xaviers College</div>
	 <div class="img-info" id="kong_i"><h3>Hong Kong</h3>The University Of Hong Kong</div>
	 <div class="img-info" id="seoul_i"><h3>Seoul</h3>Ewha Womans University</div>
	 <div class="img-info" id="tokyo_i"><h3>Tokyo</h3>University of Tokyo</div>
      </div>


      <!-- HCAP - removed search -->
      <!-- search form 
      <div class="search-block">
        <div class="searchform-wrap">
          <form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
            <input type="text" name="s" id="searchbox" class="searchfield" value="<?php _e("Search","arclite"); ?>" onfocus="if(this.value == '<?php _e("Search","arclite"); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e("Search","arclite"); ?>';}" />
             <input type="submit" value="Go" class="go" />
          </form>
        </div>
      </div>
       /search form -->
      <?php } ?>

     </div>

     <!-- main navigation -->
     <div id="nav-wrap1">
      <div id="nav-wrap2">
        <ul id="nav">
         <?php
          if((get_option('show_on_front')<>'page') && (get_option('arclite_topnav')<>'categories')) {
            if(is_home() && !is_paged()){ ?>
           <li id="nav-homelink" class="current_page_item"><a class="fadeThis" href="<?php echo get_settings('home'); ?>" title="<?php _e('You are Home','arclite'); ?>"><span><?php _e('Home','arclite'); ?></span></a></li>
         <?php } else { ?>
          <li id="nav-homelink"><a class="fadeThis" href="<?php echo get_option('home'); ?>" title="<?php _e('Click for Home','arclite'); ?>"><span><?php _e('Home','arclite'); ?></span></a></li>
         <?php
           }
          }
          ?>
         <?php
           if(get_option('arclite_topnav')=='categories') {
            echo preg_replace('@\<li([^>]*)>\<a([^>]*)>(.*?)\<\/a>@i', '<li$1><a class="fadeThis"$2><span>$3</span></a>', wp_list_categories('show_count=0&echo=0&title_li='));
            }
           else {
             echo preg_replace('@\<li([^>]*)>\<a([^>]*)>(.*?)\<\/a>@i', '<li$1><a class="fadeThis"$2><span>$3</span></a>', wp_list_pages('echo=0&orderby=name&title_li=&'));
           }
          ?>
        </ul>
      </div>
     </div>
     <!-- /main navigation -->

   </div>
  </div>
  <!-- /header -->