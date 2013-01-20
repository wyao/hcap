<?php
/*
Plugin Name: HCAP FB Login
Description: Restrict certain pages to logged in Facebook users
Author: Matt Martz & Andy Stratton & Willie Yao & Andrew Zhou
Version: 3.0
*/

// if we are in the admin load the admin functionality

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
		$pr_page_content .= '<div class="fb-login-button" data-show-faces="false" data-width="200" data-max-rows="1" scope="user_location"></div>';
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

// Load registration form html (only if user not registered)
function load_registration( $content )
{
    $facebook = new Facebook(array(
        'appId'  => '435704813143438',
        'secret' => '5d66e4638a26eee220a8590f47637245',
    ));

    // If FB ID available
    if($facebook &&($fbUser=$facebook->getUser())){
        global $wpdb;
        $table = $wpdb->prefix . "alum_members";

        $rows = $wpdb->get_results( "SELECT * FROM $table WHERE fb_id = $fbUser" );

        // DB does not contain user
        if ($rows == null){
            $wpdb->insert(
                $table,
                array(
                    'fb_id' => $fbUser
                ),
                array(
                    '%d'
                )
            );
        }

        // Missing registration data
        if ($rows[0]->first_name == null) {
            $content = $content . '<div id="shadowing"></div>
                <div id="box">
              <span id="boxtitle"></span>
              <form name="registration" action="" onsubmit="return validate()" method="post">

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
                    <option value="Bogazici University, Istanbul">Boğaziçi University, Istanbul</option>
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
            </div>';
        }
    }
	return $content;
}

// http://codex.wordpress.org/Creating_Tables_with_Plugins
global $jal_db_version;
$jal_db_version = "1.0";

function jal_install() {
    global $wpdb;
    global $jal_db_version;

    $table_name = $wpdb->prefix . "alum_members";

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        time datetime,
        fb_id INT NOT NULL,
        first_name VARCHAR(50),
        last_name VARCHAR(50),
        email VARCHAR(100),
        school VARCHAR(50),
        year VARCHAR(20),
        gender VARCHAR(10),
        location VARCHAR(100),
        job VARCHAR(200),
        UNIQUE KEY (id),
        UNIQUE KEY (fb_id)
    );";

   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   dbDelta($sql);
 
   add_option("jal_db_version", $jal_db_version);
}

function add_alum_member() {
    $facebook = new Facebook(array(
        'appId'  => '435704813143438',
        'secret' => '5d66e4638a26eee220a8590f47637245',
    ));

    global $wpdb;

	if( isset($_POST['first']) && $facebook && ($fbUser=$facebook->getUser())) {

		$first = $_POST['first'];
		$last = $_POST['last'];
		$email = $_POST['email'];
		$school = $_POST['school'];
		$year = $_POST['year'];
		$gender = $_POST['gender'];

		$table = $wpdb->prefix . "alum_members";

        $wpdb->update(
            $table,
            array(
                'first_name' => $first,
                'last_name' => $last,
                'email' => $email,
                'school' => $school,
                'year' => $year,
                'gender' => $gender
            ),
            array( 'fb_id' =>  $fbUser),
            array(
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s'
            )
        );
	}
}

function load_fb_js() {
	wp_enqueue_script( 'fb-js', plugins_url( 'fb.js', __FILE__ ));
}

function check_selection( $content, $target ) {
    if ($content == $target)
        return 'selected="selected"';
    return '';
}

function check_checked( $content, $target) {
    if ($content == $target)
        return 'checked';
    return '';
}

//[foobar]
function registration_func( $atts ){
    $facebook = new Facebook(array(
        'appId'  => '435704813143438',
        'secret' => '5d66e4638a26eee220a8590f47637245',
    ));

    // TODO: fix null name/email bug
    // If FB ID available
    if($facebook &&($fbUser=$facebook->getUser())){
        global $wpdb;
        $table = $wpdb->prefix . "alum_members";

        $rows = $wpdb->get_results( "SELECT * FROM $table WHERE fb_id = $fbUser" );

        return '<form name="registration" action="" onsubmit="return validate()" method="post">

                <p>First Name:
                  <input type="text" name="first" value=' . $rows[0]->first_name . ' maxlength="60" size="60">
                </p>

                <p>Last Name:
                  <input type="text" name="last" value=' . $rows[0]->last_name . ' maxlength="60" size="60">
                </p>

                <p>Email Address:
                  <input type="text" name="email" value=' . $rows[0]->email . ' maxlength="60" size="60">
                </p>

                <p> Affiliated School:
                  <select name="school">
                    <option ' . check_selection($rows[0]->school, 'Harvard University, Cambridge') . '>Harvard University, Cambridge</option>
                    <option value="Bogazici University, Istanbul" ' . check_selection($rows[0]->school, 'Bogazici University, Istanbul') . '>Boğaziçi University, Istanbul</option>
                    <option ' . check_selection($rows[0]->school, 'American University in Dubai') . '>American University in Dubai</option>
                    <option ' . check_selection($rows[0]->school, 'St Xaviers College, Mumbai') . '>St Xaviers College, Mumbai</option>
                    <option ' . check_selection($rows[0]->school, 'The University of Hong Kong') . '>The University of Hong Kong</option>
                    <option ' . check_selection($rows[0]->school, 'Ewha Womans University, Seoul') . '>Ewha Womans University, Seoul</option>
                    <option ' . check_selection($rows[0]->school, 'University of Tokyo') . '>University of Tokyo</option>
                    <option ' . check_selection($rows[0]->school, 'Chula University, Bangkok') . '>Chula University, Bangkok</option>
                    <option ' . check_selection($rows[0]->school, 'Other') . '>Other</option>
                  </select>
                </p>

                <p>
                    <select name="year">
                        <option value="2012-13" ' . check_selection($rows[0]->year, "2012-13") . '>2012-13</option>
                        <option value="2011-12" ' . check_selection($rows[0]->year, "2011-12") . '>2011-12</option>
                        <option value="2010-11" ' . check_selection($rows[0]->year, "2010-11") . '>2010-11</option>
                        <option value="2009-10" ' . check_selection($rows[0]->year, "2009-10") . '>2009-10</option>
                        <option value="2008-09" ' . check_selection($rows[0]->year, "2008-09") . '>2008-09</option>
                        <option value="2007-08" ' . check_selection($rows[0]->year, "2007-08") . '>2007-08</option>
                        <option value="2006-07" ' . check_selection($rows[0]->year, "2006-07") . '>2006-07</option>
                        <option value="2005-06" ' . check_selection($rows[0]->year, "2005-06") . '>2005-06</option>
                        <option value="2004-05" ' . check_selection($rows[0]->year, "2004-05") . '>2004-05</option>
                        <option value="2003-04" ' . check_selection($rows[0]->year, "2003-04") . '>2003-04</option>
                        <option value="Other" ' . check_selection($rows[0]->year, "Other") . '>Other</option>
                    </select>
                </p>

                <p>
                    Male
                    <input type="radio" name="gender" value="man" ' . check_checked($rows[0]->gender, 'man') . '>
                    Female
                    <input type="radio" name="gender" value="woman" ' . check_checked($rows[0]->gender, 'woman') . '>
                </p>

                <p>
                  <input type="submit" name="submit" value="Submit" >
                  <input type="button" name="cancel" value="Cancel" onClick="closebox()">
                </p>
                <p id="error_msg">
                </p>
              </form>';
    }

    return "";
}

// Add Shortcode
add_shortcode( 'registration', 'registration_func' );

// Activation Hook
register_activation_hook(__FILE__,'jal_install');

// Add Actions
add_action( 'wp_enqueue_scripts', 'load_registration_script' );
add_action ( 'send_headers' , 'pr_no_cache_headers' );
add_action ( 'wp_loaded', 'add_alum_member' );
add_action ('wp_enqueue_scripts', 'load_fb_js');

// Add Filters
add_filter ( 'the_content' , 'pr_page_restrict' , 50 );
add_filter ( 'the_content' , 'load_registration' , 51);
add_filter ( 'the_excerpt' , 'pr_page_restrict' , 50 );
add_filter ( 'comments_array' , 'pr_comment_restrict' , 50 );
?>