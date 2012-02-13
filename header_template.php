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
		<script type='text/javascript' src='lovehunt.js'></script>
		<script src="http://code.jquery.com/jquery-latest.js"></script>
		<script src="http://connect.facebook.net/en_US/all.js"></script>
	</head>

	<body>
		<div id='container'>
			<div id='header'>
				<div id='logo'><img src="https://graph.facebook.com/me/picture?type=square&access_token=<?php echoEntity($token) ?>" class = "image_tag" ></div>
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

