<?php

//header('X-Frame-Options: GOFORIT'); 

// Enforce https on production

/*
if ($_SERVER['HTTP_X_FORWARDED_PROTO'] == "http" && $_SERVER['REMOTE_ADDR'] != '127.0.0.1') {
  header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
  exit();
}
 */
// Provides access to Facebook specific utilities defined in 'FBUtils.php'
require_once('FBUtils.php');
// Provides access to app specific values such as your app id and app secret.
// Defined in 'AppInfo.php'
require_once('AppInfo.php');
// This provides access to helper functions defined in 'utils.php'
require_once('utils.php');

// This is the file used to establish connection to the database
require_once("connection.php");



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
	$username = idx($basic, 'username');
	$name = idx($basic, 'name');
	$gender = idx($basic, 'gender');
	
	$query = "SELECT friend_id from user_choices where choice = 'dislikes' AND uid = $my_id";
	$resource = pg_query($conn, $query);

	$disliked_friends = pg_fetch_all_columns($resource, 0);
	$key = 0;
	$friends = array();
	foreach($disliked_friends as $k => $value)
	{
		$friends[$key] =  FBUtils::fetchFromFBGraph("$value?access_token=$token");
		$key++;
	}
}
else
{
	echo "Invalid Credentials";
}
?>
<? require_once("header_template.php"); ?>
<div id="content">
	<? $count = count($friends);
	for ($key = 0; $key < 5; $key++)
	{
		for($i = 0 ; $key + $i < $count; $i = $i + 5)
		{
			$counter = $key + $i;
			$friendId = $friends[$counter]['id'];
			if($i == 0) :
?>
				<div id = '<?="friend$counter"?>' class='friends'>
<?
			else :
?>
				<div id = '<?="friend$counter"?>' class='hidden_friends'>
<?
			endif;
?>

					<div class = 'friendImage'>
						<a href ="<?=$friends[$counter]['link']?>" target='_blank'><img src="https://graph.facebook.com/<?=$friendId?>/picture?type=square&access_token=<?php echoEntity($token) ?>" class = "image_tag" ></a>	
					</div>  <!--div friendImage end here -->
					
					<div class="friendInfo">
						<div class= 'friendName'>		
							<?=$friends[$counter]['name']?> 
							<br />
						</div> <!-- div friend name ends here -->
						<p>
							<?=$friends[$counter]['bio']?> <br />
						</p>		
					</div> <!--div friendInfo end here -->
					<div class="options">
						<div id='<?php echo "like$counter"; ?>' class='images likeImage' title='Like' onclick="addToFields('<?="$counter" ?>','<?=$friendId; ?>', '<?=$friends[$counter]['name'] ?>','<?=$my_id; ?>','likes', '<?=$count?>')" ></div>
      					<div id ='<?php echo "dislike$counter"; ?>' class='images dislikedImage' title="Dislike" onclick="addToFields('<?="$counter" ?>','<?=$friendId; ?>', '<?=$friends[$counter]['name'] ?>','<?=$my_id; ?>','NULL', '<?=$count?>')" ></div>
						<div id='<?php echo "remove$counter"; ?>' class='images removeImage' title="Remove" onclick="addToFields('<?="$counter" ?>','<?=$friendId; ?>', '<?=$friends[$counter]['name'] ?>','<?=$my_id; ?>','removed', '<?=$count?>')" ></div>
					</div> <!-- div options end here -->
	
				</div> <!--div friend$counter end here -->
<?	
		}		
	}

	require_once("no_content_template.php");
?>


</div>		<!-- div content ends here -->
<? require_once("footer_template.php"); ?>

