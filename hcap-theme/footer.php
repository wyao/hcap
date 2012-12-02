<?php /* Arclite/digitalnature */ ?>
 <!-- footer -->
 <div id="footer">

  <!-- page block -->
  <div class="block-content">

    <?php include(TEMPLATEPATH . '/footer-widgets.php'); ?>

    <?php if(get_option('arclite_footer')<>'') { ?>
    <div class="add-content">
      <?php print get_option('arclite_footer'); ?>
    </div>
     <?php } ?>

    <!-- HCAP start -->
    <div class = "sitemap">
    <div class="map">
    <h6>About</h6>
	<ul>
    <li><a href="http://www.harvardcollegeinasia.org/">Home</a></li>
    <li><a href="http://www.harvardcollegeinasia.org/about/mission/">Mission</a></li>
    <li><a href="http://www.harvardcollegeinasia.org/about/the-conferences/">The Conferences</a></li>
    <li><a href="http://www.harvardcollegeinasia.org/about/executive-board/">Board</a></li>
    <li><a href="http://www.harvardcollegeinasia.org/about/history/">History</a></li>
    <li><a href="http://www.harvardcollegeinasia.org/about/impact/">Reflections</a></li>
	</ul>
    </div>
    <div class="map">
    <h6>Conferences</h6>
    <ul>
    <li><a href="http://www.harvardcollegeinasia.org/conferences/2012-theme/">2012 Theme</a></li>
    <li><a href="http://www.harvardcollegeinasia.org/conferences/2012-schedule/">2012 Schedule</a></li>
    <li><a href="http://www.harvardcollegeinasia.org/conferences/past-conferences/">Past Conferences</a></li>
    <li><a href="http://www.harvardcollegeinasia.org/conferences/service-trip/">Service Trip</a></li>
    <li><a href="http://www.harvardcollegeinasia.org/conferences/harvard-students-conference-portal/">Student Portal</a></li>
	</ul>
    </div>

    <div class="map">
    <h6>Partners</h6>
    <ul>
    <li><a href="http://www.harvardcollegeinasia.org/partners/istanbul/">Istanbul</a></li>
    <li><a href="http://www.harvardcollegeinasia.org/partners/dubai/">Dubai</a></li>
    <li><a href="http://www.harvardcollegeinasia.org/partners/mumbai/">Mumbai</a></li>
    <li><a href="http://www.harvardcollegeinasia.org/partners/hong-kong/">Hong Kong</a></li>
    <li><a href="http://www.harvardcollegeinasia.org/partners/tokyo/">Tokyo</a></li>
    <li><a href="http://www.harvardcollegeinasia.org/partners/seoul/">Seoul</a></li>
    </ul>
    </div>

    <div class="map">
    <h6>Supporters</h6>
    <ul>
    <li><a href="http://www.harvardcollegeinasia.org/supporters/advisers/">Advisors</a></li>
    <li><a href="http://www.harvardcollegeinasia.org/supporters/sponsors/">Supporters & Supporters</a></li>
    <li><a href="http://www.harvardcollegeinasia.org/supporters/donate-online/">Donate Online</a></li>
    </ul>
    </div>

    <div class="map">
    <h6>News</h6>
    <ul>
    <li><a href="http://www.harvardcollegeinasia.org/news/upcoming-events/">Upcoming Events</a></li>
    <li><a href="http://www.harvardcollegeinasia.org/news/blog/">Blog</a></li>
    <li><a href="http://www.harvardcollegeinasia.org/news/alumni/">Alumni</a></li>
    </ul>
    </div>

    <div class="map">
    <h6>Contacts</h6>
    <ul>
    <li><a href="http://www.harvardcollegeinasia.org/contact-us/">Contacts</a></li>
  	</ul>
    </div>

    </div>
    <!-- HCAP end -->
 

    <div class="copyright">
     <p>
     <!-- please do not remove this. respect the authors :) -->
     <?php
      printf(__('Arclite theme by %s', 'arclite'), '<a href="http://digitalnature.ro/projects/arclite">digitalnature</a>');?>
      and <a href="mailto:wyao13@college.harvard.edu">wyao</a> 
     <?php print ' | ';
      printf(__('powered by %s', 'arclite'), '<a href="http://wordpress.org/">WordPress</a>');
     ?>
     </p>
     <p>
     <a class="rss" href="<?php bloginfo('rss2_url'); ?>"><?php _e('Entries (RSS)','arclite'); ?></a> <?php _e('and','arclite');?> <a href="<?php bloginfo('comments_rss2_url'); ?>"><?php _e('Comments (RSS)','arclite'); ?></a> <a href="javascript:void(0);" class="toplink">TOP</a>
     <!-- <?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. -->
     <br />
     The Harvard College in Asia Program (HCAP) is a student-run organization at Harvard College. The Harvard College name and/or shield are trademarks of the President and Fellows of Harvard College and are used by permission of Harvard University.
     </p>
    </div>

  </div>
  <!-- /page block -->

 </div>
 <!-- /footer -->

</div>
<!-- /page -->
<?php wp_footer(); ?>
</body>
</html>



