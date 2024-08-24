<?php

include('db_config/db_connect.php');
session_start();

if(isset($_GET['id']))
{
	# Delete the recipe with the provided id
	$id = mysqli_real_escape_string($conn, $_GET['id']);
	
	$query = "DELETE FROM Food WHERE food_id='$id'";
	$result = mysqli_query($conn, $query);

	if(!$result)
	{
		die('Could not delete data: ' . mysql_error());
	}

	header('Location: profile.php');
}
?>