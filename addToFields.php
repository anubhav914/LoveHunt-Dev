<?
	require_once("connection.php");
	require_once("FBUtils.php");

	session_start();
	$token = $_SESSION['token'];
	
	$friendId = $_POST['friendId'];
	$friendName = $_POST['friendName'];
	$my_id = $_POST['my_id'];
	$choice = $_POST['choice'];

	$query = "DELETE FROM user_choices where uid = $my_id AND friend_id = $friendId ";
//	echo (pg_query($conn, $query));

	$query = "INSERT INTO user_choices (uid, friend_id, friend_name, choice) VALUES ($my_id, $friendId, '$friendName', '$choice')";
	//echo($query);
	pg_query($conn, $query);

	
	if($choice == 'likes')
	{
		$query = "SELECT * from user_choices WHERE uid = $friendId AND friend_id = $my_id AND choice = 'likes'";
		$resource = pg_query($conn, $query);
		if(pg_num_rows($resource) != 0)
		{
			$query = "UPDATE user_choices SET match = true WHERE uid = $my_id AND friend_id = $friendId";
			pg_query($conn, $query);

			$query = "UPDATE user_choices SET match = true WHERE uid = $friendId AND friend_id = $my_id";
			pg_query($conn, $query);
		}
	}
	
	$url = "https://api.facebook.com/method/dashboard.incrementCount?uid=" . $my_id . "&access_token=" . $token ; 
	//echo($url);
	FBUtils::curl($url);	
	 
	//echo "Everythng Completed";	
?>
