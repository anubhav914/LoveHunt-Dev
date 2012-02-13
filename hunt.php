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

	
	$query = "SELECT * from users where uid = $my_id";
	$resource = pg_query($conn, $query);
	
	if(pg_num_rows($resource) == 0)
	{
		$query = "INSERT INTO users (uid, username, name) values ($my_id, '$username', '$name')";
		pg_query($conn, $query);
	}
	else
	{
		$query = "SELECT friend_id from user_choices WHERE uid = $my_id";
		$resource = pg_query($conn, $query);
		$array_tagged_friends = array();
		$tagged_friends = pg_fetch_all_columns($resource, 0);
		//print_r($tagged_friends);
	}
	
	
	$interested_in;
	if($gender == "male")
		$interested_in = "female" ;
	else 
		$interested_in = "male";

		
		

	$query = "SELECT uid, username, name, sex, pic, pic_square,interests, profile_url, movies, books, music , about_me FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = me()) and sex='$interested_in' limit 20" ;

	$friends = FBUtils::fql($query, $token);	
	
	$friends = untagged_friends($friends, $tagged_friends); 
	//	print_r($likes);
	/*
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
 */	  	
/*	echo("<pre>");
	print_r($friends);
	echo("</pre>");
*/
}
else
{
	echo("Invalid credentials");

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
						<a href ="<?=$friends[$counter]['profile_url']?>" target='_blank'><img src = '<?=$friends[$counter]['pic_square']?>' class = 'image_tag'></a>	
					</div>  <!--div friendImage end here -->
					
					<div class="friendInfo">
						<div class= 'friendName'>		
							<?=$friends[$counter]['name']?> 
							<br />
						</div>
						<p>
							<?=$friends[$counter]['about_me']?> <br />
						</p>		
						<div class = 'friendLikes'>
							<p>
							Few things she likes
							<p>	
							<div class="list">
								<ul class="things">
<?
									$friendId = $friends[$counter]['uid'];
									//var_dump($friendId);
									$likes = array_values(idx(FBUtils::fetchFromFBGraph("$friendId/likes?access_token=$token&limit=3"), 'data', null, false));
									//var_dump($likes);
										foreach($likes as $like){
										
										$id = assertNumeric(idx($like, 'id'));
										$item = idx($like, 'name');
?>
										<li class=things>
											<a href = "<?="http://www.facebook.com/$id" ?>" ><img src = "<?="https://graph.facebook.com/$id/picture?type=square"?>" class = "things_images"> <?=$item ?> </a>
										</li>	
<?
										}
									$movies = array_values(idx(FBUtils::fetchFromFBGraph("$friendId/movies?access_token=$token&limit=3"), 'data', null, false));
										//var_dump($likes);
										foreach($movies as $movie){
										
										$id = assertNumeric(idx($movie, 'id'));
										$item = idx($movie, 'name');
?>
										<li class=things>
											<a href = "<?="http://www.facebook.com/$id" ?>" ><img src = "<?="https://graph.facebook.com/$id/picture?type=square"?>" class = "things_images"> <?=$item ?> </a>
										</li>	
<?
										}

?>
				                </ul>
				        	</div>	<!-- div list ends here--> 
						</div> <!-- div friendLikes ends here--> 
					</div> <!--div friendInfo end here -->
					<div class="options">
						<div id='<?php echo "like$counter"; ?>' class='images likeImage' title='Like' onclick="addToFields('<?="$counter" ?>','<?=$friendId; ?>', '<?=$friends[$counter]['name'] ?>','<?=$my_id; ?>','likes', '<?=$count?>')" ></div>
      					<div id ='<?php echo "dislike$counter"; ?>' class='images dislikeImage' title="Dislike" onclick="addToFields('<?="$counter" ?>','<?=$friendId; ?>', '<?=$friends[$counter]['name'] ?>','<?=$my_id; ?>','dislikes', '<?=$count?>')" ></div>
						<div id='<?php echo "remove$counter"; ?>' class='images removeImage' title="Remove" onclick="addToFields('<?="$counter" ?>','<?=$friendId; ?>', '<?=$friends[$counter]['name'] ?>','<?=$my_id; ?>','removed', '<?=$count?>')" ></div>
					</div> <!-- div options end here -->
	
				</div> <!--div friend$counter end here -->
<?	
		}		
	}

?>
</div>		<!-- div content ends here -->
<? require_once("footer_template.php"); ?>


