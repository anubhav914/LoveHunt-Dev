<?php

//header('X-Frame-Options: GOFORIT'); 

// Enforce https on production

if ($_SERVER['HTTP_X_FORWARDED_PROTO'] == "http" && $_SERVER['REMOTE_ADDR'] != '127.0.0.1') {
  header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
  exit();
}

// Provides access to Facebook specific utilities defined in 'FBUtils.php'
require_once('FBUtils.php');
// Provides access to app specific values such as your app id and app secret.
// Defined in 'AppInfo.php'
require_once('AppInfo.php');
// This provides access to helper functions defined in 'utils.php'
require_once('utils.php');

/*****************************************************************************
 *
 * The content below provides examples of how to fetch Facebook data using the
 * Graph API and FQL.  It uses the helper functions defined in 'utils.php' to
 * do so.  You should change this section so that it prepares all of the
 * information that you want to display to the user.
 *
 ****************************************************************************/

// Log the user in, and get their access token
$token = FBUtils::login(AppInfo::getHome());
if ($token) {

	session_start();
	$_SESSION['token'] = $token;

	// Fetch the viewer's basic information, using the token just provided
  	$basic = FBUtils::fetchFromFBGraph("me?access_token=$token");
	$my_id = assertNumeric(idx($basic, 'id'));

	// Fetch the basic info of the app that they are using
	$app_id = AppInfo::appID();
	$app_info = FBUtils::fetchFromFBGraph("$app_id?access_token=$token");



	// This formats our home URL so that we can pass it as a web request
	$encoded_home = urlencode(AppInfo::getHome());
	$redirect_url = $encoded_home . 'close.php';

	// These two URL's are links to dialogs that you will be able to use to share
	// your app with others.  Look under the documentation for dialogs at
	// developers.facebook.com for more information
	$send_url = "https://www.facebook.com/dialog/send?redirect_uri=$redirect_url&display=popup&app_id=$app_id&link=$encoded_home";
	$post_to_wall_url = "https://www.facebook.com/dialog/feed?redirect_uri=$redirect_url&display=popup&app_id=$app_id";
} 

else {
  // Stop running if we did not get a valid response from logging in
  exit("Invalid credentials");
}
?>

<!-- This following code is responsible for rendering the HTML   -->
<!-- content on the page.  Here we use the information generated -->
<!-- in the above requests to display content that is personal   -->
<!-- to whomever views the page.  You would rewrite this content -->
<!-- with your own HTML content.  Be sure that you sanitize any  -->
<!-- content that you will be displaying to the user.  idx() by  -->
<!-- default will remove any html tags from the value being      -->
<!-- and echoEntity() will echo the sanitized content.  Both of  -->
<!-- these functions are located and documented in 'utils.php'.  -->
<!DOCTYPE html>

<? require_once("header_template.php"); ?>

<div id="content">
	<div id="bigLogo"></div>
	<div id="description">
		<div id="text">
			<p>Love hunt helps you find love among friends<br />without the risk losing their friendship.</p><p>Your choices are private. We share 'Mutual<br /> Likes' with the couple only.</p>
		</div> <!-- div text ends here-->
		<input id="startButton" type = 'button' value = 'Start Hunting' />
	</div> 		<!-- div description ends here -->
	<div class="clear"></div>
	<div id="bottomimage"></div>
</div>		<!-- div content ends here -->

<? require_once("footer_template.php");
?>






























<?
/*
<html lang="en">
  <head>
    <meta charset="utf-8">

    <!-- We get the name of the app out of the information fetched -->
    <title><?php echo(idx($app_info, 'name')) ?></title>
    <link rel="stylesheet" href="stylesheets/screen.css" media="screen">

    <!-- These are Open Graph tags.  They add meta data to your  -->
    <!-- site that facebook uses when your content is shared     -->
    <!-- over facebook.  You should fill these tags in with      -->
    <!-- your data.  To learn more about Open Graph, visit       -->
    <!-- 'https://developers.facebook.com/docs/opengraph/'       -->
    <meta property="og:title" content=""/>
    <meta property="og:type" content=""/>
    <meta property="og:url" content=""/>
    <meta property="og:image" content=""/>
    <meta property="og:site_name" content=""/>
    <?php echo('<meta property="fb:app_id" content="' . AppInfo::appID() . '" />'); ?>
    <script>
      function popup(pageURL, title,w,h) {
        var left = (screen.width/2)-(w/2);
        var top = (screen.height/2)-(h/2);
        var targetWin = window.open(
          pageURL,
          title,
          'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left
          );
      }
    </script>
    <!--[if IE]>
      <script>
        var tags = ['header', 'section'];
        while(tags.length)
          document.createElement(tags.pop());
      </script>
    <![endif]-->
  </head>
  <body>
    <header class="clearfix">
      <!-- By passing a valid access token here, we are able to display -->
      <!-- the user's images without having to download or prepare -->
      <!-- them ahead of time -->
      <p id="picture" style="background-image: url(https://graph.facebook.com/me/picture?type=normal&access_token=<?php echoEntity($token) ?>)"></p>

      <div>
        <h1>Welcome, <strong><?php echo idx($basic, 'name'); ?></strong></h1>
        <p class="tagline">
          This is your app
          <a href="<?php echo(idx($app_info, 'link'));?>"><?php echo(idx($app_info, 'name')); ?></a>
        </p>
        <div id="share-app">
          <p>Share your app:</p>
          <ul>
            <li>
              <a href="#" class="facebook-button" onclick="popup('<?php echo $post_to_wall_url ?>', 'Post to Wall', 580, 400);">
                <span class="plus">Post to Wall</span>
              </a>
            </li>
            <li>
              <a href="#" class="facebook-button speech-bubble" onclick="popup('<?php echo $send_url ?>', 'Send', 580, 400);">
                <span class="speech-bubble">Send to Friends</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </header>
    <section id="get-started">
      <p>This is my App called Lovehunt</p>
      <a href="http://devcenter.heroku.com/articles/facebook" class="button">Learn How to Edit This App</a>
    </section>

    <section id="samples" class="clearfix">
      <h1>Examples of the Facebook Graph API</h1>

      <div class="list">
        <h3>A few of your friends</h3>
        <ul class="friends">
          <?php
            foreach ($friends as $friend) {
              // Extract the pieces of info we need from the requests above
              $id = assertNumeric(idx($friend, 'id'));
              $name = idx($friend, 'name');
              // Here we link each friend we display to their profile
              echo('
                <li>
                  <a href="#" onclick="window.open(\'http://www.facebook.com/' . $id . '\')">
                    <img src="https://graph.facebook.com/' . $id . '/picture?type=square" alt="' . $name . '">'
                    . $name . '
                  </a>
                </li>');
            }
          ?>
        </ul>
      </div>

      
	  </section>

	<section class = "clearfix">
		<div class = "list">
		<?
			echo('<pre>');
			print_r($data);
			echo('<br />');
			print_r($family_data);
			echo('<br />');
			print_r($books);
			echo('</pre>')
		?>
		</div>
	</section>

    </body>
	</html>
*/
?>
