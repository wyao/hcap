window.fbAsyncInit = function() {
    // init the FB JS SDK
    FB.init({
      appId      : '435704813143438', // App ID from the App Dashboard
      channelUrl : '//www.harvardcollegeinasia.org/wp-content/plugins/pagerestrict/channel.html', // Channel File for x-domain communication
      status     : true, // check the login status upon init?
      cookie     : true, // set sessions cookies to allow your server to access the session?
      xfbml      : true  // parse XFBML tags on this page?
    });

    // Additional initialization code such as adding Event Listeners goes here
	FB.Event.subscribe('auth.login', function(response) {
          window.location.reload();
        });

  };

  // Load the SDK's source Asynchronously
  (function(d){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     ref.parentNode.insertBefore(js, ref);
   }(document));