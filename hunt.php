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
session_start();
$token = $_SESSION['token']; 
if ($token) {

  	// Fetch the viewer's basic information, using the token just provided
  	$basic = FBUtils::fetchFromFBGraph("me?access_token=$token");
	$my_id = assertNumeric(idx($basic, 'id'));
	

	// This fetches some things that you like . 'limit=*" only returns * values.
  // To see the format of the data you are retrieving, use the "Graph API
  // Explorer" which is at https://developers.facebook.com/tools/explorer/
  $likes = array_values(
    idx(FBUtils::fetchFromFBGraph("me/likes?access_token=$token&limit=4"), 'data', null, false)
  );

  // This fetches 4 of your friends.
  $friends = array_values(
    idx(FBUtils::fetchFromFBGraph("me/friends?access_token=$token"), 'data', null, false)
  );

  $data = FBUtils::fql(
	  "SELECT uid, username, name, pic_square, sex, relationship_status, interests, movies, books, about_me, profile_url FROM user where uid = me()", $token
  );

  $uid = $data[0]['uid'];

  $books = array_values(idx(FBUtils::fetchFromFBGraph("$uid/books?access_token=$token"), 'data', null, false));
  
  $family_data = FBUtils::fql(
	  "SELECT profile_id, relationship FROM family WHERE profile_id = me()", $token
  ); 
}
else
{
	echo("Invalid credentials");

}
?>


<html lang="en">
	<head>
		<meta charset="utf-8">

		<!-- We get the name of the app out of the information fetched -->
		<title><?php echo(idx($app_info, 'name')) ?></title>
		<link rel="stylesheet" href="stylesheets/screen.css" media="screen">
		<link rel="icon" type="image/png" href="images/logo.png" /> 
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

		<link rel='stylesheet' type='text/css' href='stylesheets/style.css'>
		<script type='text/javascript' src='javascript.js'></script>
		<script src="http://connect.facebook.net/en_US/all.js"></script>
	</head>

	<body>
		<div id='container'>
			<div id='header'>
				<div id='logo'><img src="https://graph.facebook.com/me/picture?type=square&access_token=<?php echoEntity($token) ?>"></div>
				<div id="appContent">
					<div id='appName'>Welcome, <strong><?php echo idx($basic, 'name'); ?></strong></div>
					<div id="appInfo">Find love among Your Friends</div>
				</div>   <!--div appContent ends here -->
			</div>		<!--div header ends here -->

			<div class='clear'></div>
			<div id='menubar'>
				<div id='home' class="menuLinks" >Home</div>
				<div id='hunt' class="menuLinks" ><a href="hunt.php">Hunt! </a></div>
				<div id='matches' class="menuLinks" >Matches</div>
				<div id='myLikes' class="menuLinks" >My Likes</div>
				<div id='dislikes' class="menuLinks" >Dislikes</div>
				<div id='removed' class="menuLinks" >Removed</div>
			</div >   <!--div menubar ends here -->

			<div class='clear'></div>
			<div id='loadcontent' style="display:none;"><img id="loadimage" src="images/preloader.gif"/> </div>
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
		</div>
	</body>
</html>

