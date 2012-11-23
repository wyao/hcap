<?php
/*
Plugin Name: Page Restrict
Plugin URI: http://theandystratton.com/pagerestrict
Description: Restrict certain pages to logged in users
Author: Matt Martz & Andy Stratton
Author URI: http://theandystratton.com
Version: 2.06

	Copyright (c) 2008 Matt Martz (http://sivel.net)
        Page Restrict is released under the GNU Lesser General Public License (LGPL)
	http://www.gnu.org/licenses/lgpl-3.0.txt
*/

// ff we are in the admin load the admin functionality
if ( is_admin () )
	require_once( dirname ( __FILE__ ) . '/inc/admin.php' );

require_once( dirname ( __FILE__ ) . '/php-sdk/facebook.php');
	
// get specific option
function pr_get_opt ( $option ) {
	$pr_options = get_option ( 'pr_options' );
	// clean up PHP warning for in_array() later when they have not been saved
	if ( $option == 'posts' || $option == 'pages' ) {
		if ( !is_array($pr_options[$option]) ) {
			$pr_options[$option] = array();
		}
	}
    return $pr_options[$option];
}

// Add headers to keep browser from caching the pages when user not logged in
// Resolves a problem where users see the login form after logging in and need 
// to refresh to see content
function pr_no_cache_headers () {
	if ( !is_user_logged_in() )
		nocache_headers();
}

// gets standard page content when page/post is restricted.
// Need to edit this function to take Facebook login and eventually signup survey
function pr_get_page_content() {

	$pr_page_content = '
		<p>' . pr_get_opt ( 'message' )  . '</p>';
	if ( pr_get_opt ( 'loginform' ) == true ) :
		if ( ! isset ( $user_login ) )
			$user_login = '';
		$pr_page_content .= '<div class="fb-login-button" data-show-faces="true" data-width="200" data-max-rows="1"></div>';
		$post->comment_status = 'closed';
	endif;
	return $pr_page_content;
}


// Perform the restriction and if restricted replace the page content with a login form
function pr_page_restrict ( $pr_page_content ) {
	global $post;
	$pr_check = pr_get_opt('method') == 'all';
	$pr_check = $pr_check || (
		( is_array(pr_get_opt('pages')) || is_array(pr_get_opt('posts')) ) 
		&& ( count(pr_get_opt('pages')) + count(pr_get_opt('posts')) > 0 )
	);
	$pr_check = $pr_check || ( pr_get_opt('pr_restrict_home') && is_home() );
	
	if (!fb_logged_in() && $pr_check) :
		// current post is in either page / post restriction array
		$is_restricted = ( in_array($post->ID, pr_get_opt('pages')) || in_array($post->ID, pr_get_opt('posts')) ) && pr_get_opt ( 'method' ) != 'none';
		// content is restricted OR everything is restricted
		if ( (is_single() || is_page()) && ($is_restricted || pr_get_opt('method') == 'all') ):
			$pr_page_content = pr_get_page_content();
		// home page, archives, search
		elseif ( ( in_array($post->ID, pr_get_opt('pages')) || in_array($post->ID, pr_get_opt('posts')) || pr_get_opt('method') == 'all' ) 
				&& ( is_archive() || is_search() || is_home() ) 
		) :
            $pr_page_content = '<p>' . pr_get_opt ( 'message' )  . '</p>';
            $pr_page_content = str_replace('login', '<a href="' . get_bloginfo ( 'wpurl' ) . '/wp-login.php?redirect_to=' . urlencode($_SERVER['REQUEST_URI'])  . '">login</a>', $pr_page_content);
		endif;
	endif;
	return $pr_page_content;
}

function pr_comment_restrict ( $pr_comment_array ) {
	global $post;
    if ( !is_user_logged_in()  && is_array ( pr_get_opt ( 'pages' ) ) ) :
		$is_restricted = ( in_array($post->ID, pr_get_opt('pages')) || in_array($post->ID, pr_get_opt('posts')) ) && pr_get_opt ( 'method' ) != 'none';
       	if ( $is_restricted || pr_get_opt('method') == 'all' ):
			$pr_comment_array = array();
		endif;
	endif;
	return $pr_comment_array;
}

function fb_logged_in() {
	
	$facebook = new Facebook(array(
		'appId'  => '435704813143438',
		'secret' => '5d66e4638a26eee220a8590f47637245',
	));
	
	if($facebook &&($fbUser=$facebook->getUser())){
		try {
			$fbProfile=$facebook->api('/me');
		} catch (FacebookApiException $e){
			$fbUser=null;
		};
	}
	$fbLoggedIn=!is_null($fbUser) && !($fbUser==0);
	return $fbLoggedIn;
}

function load_registration_script()
{
	// Register script and style
	wp_register_script( 'registration-script', plugins_url( '/inc/register.js', __FILE__ , 1, True) );
	wp_register_style( 'registration-style', plugins_url( '/inc/register.css', __FILE__ ) );
	// Enqueue script and style
	wp_enqueue_script( 'registration-script' );
	wp_enqueue_style( 'registration-style' );
}
add_action( 'wp_enqueue_scripts', 'load_registration_script' );

// Load registration form html (only if user not registered)
function load_registration( $content )
{
	$content = $content . '<div id="shadowing"></div>
	<div id="box">
  <span id="boxtitle"></span>
  <form name="registration" action="" onsubmit="return temp()" method="post">
      
    <p>First Name: 
      <input type="text" name="first" maxlength="60" size="60">
    </p>

    <p>Last Name: 
      <input type="text" name="last" maxlength="60" size="60">
    </p>

    <p>Email Address: 
      <input type="text" name="email" maxlength="60" size="60">
    </p>

    <p> Affiliated School: 
      <select name="school">
       	<option>Harvard University, Cambridge</option>
		<option>Boğaziçi University, Istanbul</option>
		<option>American University in Dubai</option>
		<option>St Xaviers College, Mumbai</option>
		<option>The University of Hong Kong</option>
		<option>Ewha Womans University, Seoul</option>
		<option>University of Tokyo</option>
		<option>Chula University, Bangkok</option>
		<option>Other</option>
      </select>
    </p>

    <p>
	    <select name="year">
    		<option value="2012-13">2012-13</option>
    		<option value="2011-12">2011-12</option>
    		<option value="2010-11">2010-11</option>
    		<option value="2009-10">2009-10</option>
      		<option value="2008-09">2008-09</option>
    		<option value="2007-08">2007-08</option>
    		<option value="2006-07">2006-07</option>
    		<option value="2005-06">2005-06</option>
    		<option value="2004-05">2004-05</option>
    		<option value="2003-04">2003-04</option>
    		<option value="Other">Other</option>
    	</select>
    </p>

    <p>Male 
      <input type="radio" name="gender" value="man" checked>
      Female 
      <input type="radio" name="gender" value="woman">
    </p>

    <p> 
      <input type="submit" name="submit" value="Submit" >
      <input type="button" name="cancel" value="Cancel" onClick="closebox()">
    </p>
    <p id="error_msg">    	
    </p>
  </form>
</div>
<a href="#" onClick="openbox(\'Register\', 1)">click 
  here</a>';
	return $content;
}

// http://codex.wordpress.org/Creating_Tables_with_Plugins
global $jal_db_version;
$jal_db_version = "1.0";

function jal_install() {
   global $wpdb;
   global $jal_db_version;

   $table_name = $wpdb->prefix . "alum_members";

   // Add indices? Make fb_id unique?
   $sql = "CREATE TABLE $table_name (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  fb_id mediumint(9),
  first_name VARCHAR(50) NOT NULL,
  last_name VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL,
  school VARCHAR(50) NOT NULL,
  year YEAR(4) NOT NULL,
  gender CHAR(1) NOT NULL,
  UNIQUE KEY id (id)
    );";

   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   dbDelta($sql);
 
   add_option("jal_db_version", $jal_db_version);
}

function add_alum_member() {
	global $wpdb;

	if( isset($_POST['first']) ) {

		$first = $_POST['first'];
		$last = $_POST['last'];
		$email = $_POST['email'];
		$school = $_POST['school'];
		$year = $_POST['year'];
		$gender = $_POST['gender'];

		$table = $wpdb->prefix . "alum_members";

		$wpdb->insert( 
			$table,
			array(
				'first_name' => $first,
				'last_name' => $last,
				'email' => $email,
				'school' => $school,
				'year' => $year,
				'gender' => $gender
			), 
			array( 
				'%s',
				'%s',
				'%s',
				'%s',
				'%d',
				'%s' 
			) 
		);
	}
}

// Activation Hook
register_activation_hook(__FILE__,'jal_install');

// Add Actions
add_action ( 'send_headers' , 'pr_no_cache_headers' );
add_action ( 'wp_loaded', 'add_alum_member' );

// Add Filters
add_filter ( 'the_content' , 'pr_page_restrict' , 50 );
add_filter ( 'the_content' , 'load_registration' , 51);
add_filter ( 'the_excerpt' , 'pr_page_restrict' , 50 );
add_filter ( 'comments_array' , 'pr_comment_restrict' , 50 );
?>
