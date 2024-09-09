<?php

include('db_config/db_connect.php');
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$recipe = '';
if(isset($_GET['id']))
{
	# Get the id of each recipe
	$id = mysqli_real_escape_string($conn, $_GET['id']);
	$_SESSION['recipe_id'] = $id;
	#echo $id;

	$query = "SELECT * FROM Food WHERE food_id='$id'";
	$res = mysqli_query($conn, $query);

	# fetches a row as an associative array
	$recipe = mysqli_fetch_assoc($res);

	mysqli_free_result($res);
}
#echo "my session id is: ". $_SESSION['user_id'];

#echo "my recipe id is: ". $_SESSION['recipe_id'];
# Insert comments
if(isset($_POST['submit']))
{
	if(!empty($_POST['comment']))
	{	

		$usr_id = $_SESSION['user_id'];
		$rec_id = $_SESSION['recipe_id'];
		#echo "my recipe_id: ". $rec_id;
		# Input validation
		$comment = htmlspecialchars($_POST['comment']);
		$query = "INSERT INTO Comments(user_id, food_id, comment) VALUES($usr_id, $rec_id,'$comment')";
		$result = mysqli_query($conn, $query);
		#echo $query;
		if(!$result)
		{
			echo "Error adding comment: " . mysqli_error($conn);
		}


	}
}

# Display comments of each recipe
if(isset($_GET['id']))
{
	$rec_id = mysqli_real_escape_string($conn, $_GET['id']);
	$query = "SELECT * FROM Comments WHERE food_id='$rec_id'";
	$resullt = mysqli_query($conn, $query);

	if(!$resullt)
	{
		echo "Error displaying comments " . mysqli_error($conn);
	}

	$comments = mysqli_fetch_All($resullt, MYSQLI_ASSOC);

	#print_r($comments);
	
}

?>

<!DOCTYPE html>
<html>
<head>
	<?php include('templates/header.php'); ?>
	<title>recipedetails page</title>
</head>
<body>
	<nav class="navbar fixed-top ">
		<div class="container-fluid">
			<h3>
				<a class="navbar-brand" href="index.php">
					<span class="material-icons"> restaurant</span>RecipeExplorer
				</a>
			</h3>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="true" aria-label="Toggle navigation">
	        	<span class="navbar-toggler-icon"></span>
	        </button>
		    <div class="collapse navbar-collapse" id="navbarNav">
		        <ul class="navbar-nav ms-auto">
		            <li class="nav-item">
		                <a class="nav-link" href="index.php">Home</a>
		            </li>
		            <li class="nav-item">
		            	<button class="btn nav-link" type="btn" onclick="window.location.href='<?php echo isset($_SESSION['username']) ? 'profile.php' : 'login.php'; ?>'">My Profile</button>
		            </li>
		            <li class="nav-item">
		                <a class="nav-link" href="login.php">Login/Register</a>   

		            </li>
		        </ul>
		    </div>
		</div>
    </nav>

	<div class="container">
		<!-- Display info for each recipe -->
		<?php if($recipe): ?>
			<h1><?php echo htmlspecialchars($recipe['title']); ?> </h1>
			<p>By Chef: 
				<?php
				$iduser = $recipe['user_id'];
				$query = "SELECT username FROM Users WHERE id='$iduser'";
				$res = mysqli_query($conn, $query);
				$user=mysqli_fetch_assoc($res);
				echo htmlspecialchars($user['username']);
				?> 
			</p>
			<h5> <strong>Description:</strong> </h5>
			<p><?php echo htmlspecialchars($recipe['description']); ?> </p>
			<h5><strong> Ingredients:</strong> </h5>
			<p>
			<?php 
			$set = htmlspecialchars($recipe['Ingredients']);
			$ing = explode(',', $set);
			foreach($ing as $i){
				echo $i."<br/>";
			}	
			?>	
			</p>
			<h5><strong> Instructions:</strong> </h5>
			<p><?php echo htmlspecialchars($recipe['instructions']); ?></p>
			<h5><strong>Comments:</strong></h5>
			<?php foreach($comments as $comment): ?>
				<div class="container comment-box">
					<!-- Only if you are logged in you can submit comment--> 
					<h5> <?php 

					$usr_id = $comment['user_id'];
					$query = "SELECT username FROM Users WHERE id='$usr_id'";
					$res = mysqli_query($conn, $query);
					$username = mysqli_fetch_assoc($res);
					echo htmlspecialchars($username['username'])	; 
					?> </h5>
					<p> <?php echo htmlspecialchars($comment['comment']); ?></p>
				</div>
			<?php endforeach; ?>
				
			<form class="d-flex flex-column" method="POST" style="width:100%; margin:0px;" action="details.php?id=<?php echo $_SESSION['recipe_id']; ?>">
				<textarea class="form-control" id="comments" rows="4" name="comment" placeholder="Add comment"></textarea>
				<div class="d-flex justify-content-center mt-4 mb-4">
			        <button id="commentsubmit" class="btn submit brand-text nav-link" type="submit" name="submit">Submit Comment</button>
			    </div>
			</form>

		<?php endif; ?>
	</div>
    

</body>
</html>